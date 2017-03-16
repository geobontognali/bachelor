package ntb.bachelor;

//pi4j imports for handling the Raspberry pi GPIOs
import java.awt.*;
import com.pi4j.io.gpio.*;
import com.pi4j.io.gpio.event.GpioPinDigitalStateChangeEvent;
import com.pi4j.io.gpio.event.GpioPinListenerDigital;
import com.pi4j.io.gpio.GpioController;
import com.pi4j.io.gpio.GpioFactory;
import com.pi4j.io.gpio.RaspiPin;
//Keyevent import for simulate a keypress
import java.awt.event.KeyEvent;
import java.util.logging.Level;
import java.util.logging.Logger;


/**
 * TasterController for simulating the keypress on the Pi at the Aussensprechstelle
 * This listen at the Raspberry GPIO and when a pin is triggerd it simulate a keypress (arrows)
 * The arrows are used for controlling the browser-webpage with the contacts
 * Authors:     F. Crameri
 *              G. Bontognali
 */
public class TasterController
{
    // Starts logging handlers
    private static final Logger logger = Logger.getLogger("TasterLogger");

    /**
     * main method witch is executed at first
     * @param args
     */
    public static void main(String[] args) {
        try
        {
            Logging.log(Level.INFO, "Taster Controller started");
            startKeyListener(); // Start the listener
        }
        catch (Exception e)
        {
            e.printStackTrace();
        }
    }


    /**
     * GPIO listener
     * @throws Exception catch the exceptions, they are redirected to logs file
     */
    private static void startKeyListener() throws Exception
    {

        // create gpio controller
        final GpioController gpio = GpioFactory.getInstance();
        // provision gpio pin as an input pin with its internal pull down resistor enabled
        final GpioPinDigitalInput btnleft = gpio.provisionDigitalInputPin(RaspiPin.GPIO_02, PinPullResistance.PULL_DOWN); //first button for switching left
        final GpioPinDigitalInput btnmiddle = gpio.provisionDigitalInputPin(RaspiPin.GPIO_15, PinPullResistance.PULL_DOWN); //second button for making a call
        final GpioPinDigitalInput btnright = gpio.provisionDigitalInputPin(RaspiPin.GPIO_16, PinPullResistance.PULL_DOWN); //third button for switching right

        // set shutdown state for this input pin
        btnleft.setShutdownOptions(true);
        btnmiddle.setShutdownOptions(true);
        btnright.setShutdownOptions(true);

        Robot robot = new Robot();

        // create and register gpio pin listener for btnleft
        btnleft.addListener(new GpioPinListenerDigital() {
            @Override
            public void handleGpioPinDigitalStateChangeEvent(GpioPinDigitalStateChangeEvent event)
            {
                String state = event.getState().toString();
                if(state == "HIGH")
                {
                    robot.keyPress(KeyEvent.VK_KP_LEFT);
                    robot.keyRelease(KeyEvent.VK_KP_LEFT);
                    Logging.log(Level.INFO, "Button left pressed.");
                }
            }
        });

        // create and register gpio pin listener for btnmiddle
        btnmiddle.addListener(new GpioPinListenerDigital() {
            @Override
            public void handleGpioPinDigitalStateChangeEvent(GpioPinDigitalStateChangeEvent event)
            {
                String state = event.getState().toString();
                if(state == "HIGH")
                {
                    robot.keyPress(KeyEvent.VK_KP_UP);
                    robot.keyRelease(KeyEvent.VK_KP_UP);
                    Logging.log(Level.INFO, "Button middle pressed.");
                }
            }
        });

        // create and register gpio pin listener for btnmiddle
        btnright.addListener(new GpioPinListenerDigital() {
            @Override
            public void handleGpioPinDigitalStateChangeEvent(GpioPinDigitalStateChangeEvent event)
            {
                String state = event.getState().toString();
                if(state == "HIGH")
                {
                    robot.keyPress(KeyEvent.VK_KP_RIGHT);
                    robot.keyRelease(KeyEvent.VK_KP_RIGHT);
                    Logging.log(Level.INFO, "Button right pressed.");
                }
            }
        });

        /**
         * This loop will prevent the program to end so
         * that it will always listen to the GPIOs
         */
        while(true)
        {
            try
            {
                Thread.sleep(500);
            }
            catch (InterruptedException e)
            {
                e.printStackTrace();
            }
        }
    }
}
