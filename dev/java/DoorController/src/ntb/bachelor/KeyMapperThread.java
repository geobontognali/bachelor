package ntb.bachelor;

import java.awt.*;
import java.util.logging.Level;
import com.pi4j.io.gpio.*;
import com.pi4j.io.gpio.event.GpioPinDigitalStateChangeEvent;
import com.pi4j.io.gpio.event.GpioPinListenerDigital;
import com.pi4j.io.gpio.GpioController;
import com.pi4j.io.gpio.GpioFactory;
import com.pi4j.io.gpio.RaspiPin;

import java.awt.event.KeyEvent;
/**
 * Created by Geo on 20.02.2017.
 *
 * Example Class for an independent Thread
 */
public class KeyMapperThread extends Thread
{


    //Varable declaration
    private String threadName; //name of the thread

    // create gpio controller
    private final GpioController gpio = GpioFactory.getInstance();



    //Constructor
    public KeyMapperThread(String name)
    {
        this.threadName = name;
    }

    public void run()
    {
        System.setProperty("java.awt.headless", "false");
        try
        {
            Logging.log(Level.INFO, "Keymapping controller started");
            startKeyListener(); // Start the socket
        }
        catch (Exception e)
        {
            e.printStackTrace();
        }
    }

    private void startKeyListener() throws Exception
    {
        // provision gpio pin as an input pin with its internal pull down resistor enabled
        final GpioPinDigitalInput button1 = gpio.provisionDigitalInputPin(RaspiPin.GPIO_02, PinPullResistance.PULL_DOWN);
        final GpioPinDigitalInput button2 = gpio.provisionDigitalInputPin(RaspiPin.GPIO_16, PinPullResistance.PULL_DOWN);
        final GpioPinDigitalInput button3 = gpio.provisionDigitalInputPin(RaspiPin.GPIO_15, PinPullResistance.PULL_DOWN);

        // set shutdown state for this input pin
        button1.setShutdownOptions(true);
        button2.setShutdownOptions(true);
        button3.setShutdownOptions(true);

        // create and register gpio pin listener
        button1.addListener(new GpioPinListenerDigital() {
            @Override
            public void handleGpioPinDigitalStateChangeEvent(GpioPinDigitalStateChangeEvent event) {
                String state = event.getState().toString();
                if(state == "HIGH"){
                    System.out.println("switch pressed");//TODO delete debug purposes only
                    try
                    {
                        Robot robot = new Robot();
                        robot.keyPress(KeyEvent.VK_C);
                        robot.keyRelease(KeyEvent.VK_C);
                    }
                    catch (AWTException e)
                    {
                        e.printStackTrace();
                    }
                }
            }

        });

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
