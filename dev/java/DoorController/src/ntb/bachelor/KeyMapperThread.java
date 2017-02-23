package ntb.bachelor;


import java.util.logging.Level;
import com.pi4j.io.gpio.*;
import com.pi4j.io.gpio.event.GpioPinDigitalStateChangeEvent;
import com.pi4j.io.gpio.event.GpioPinListenerDigital;
/**
 * Created by Geo on 20.02.2017.
 *
 * Example Class for an independent Thread
 */
public class KeyMapperThread extends Thread
{
    //Varable declaration
    String threadName; //name of the thread
    private final String BUTTON1_PIN = "GPIO_18";
    //public static final Pin GPIO_03 = createDigitalPin(3, "GPIO 3");
    private final int BUTTON2_PIN = 15;
    private final int BUTTON3_PIN = 16;

    //Constructor
    public KeyMapperThread(String name)
    {
        this.threadName = name;
    }

    public void run()
    {
        // create gpio controller
        final GpioController gpio = GpioFactory.getInstance();

        // provision gpio pin as an input pin with its internal pull down resistor enabled
        final GpioPinDigitalInput button1 = gpio.provisionDigitalInputPin(RaspiPin.GPIO_18, PinPullResistance.PULL_DOWN);
        final GpioPinDigitalInput button2 = gpio.provisionDigitalInputPin(RaspiPin.GPIO_18, PinPullResistance.PULL_DOWN);
        final GpioPinDigitalInput button3 = gpio.provisionDigitalInputPin(RaspiPin.GPIO_18, PinPullResistance.PULL_DOWN);

        // set shutdown state for this input pin
        button1.setShutdownOptions(true);
        button2.setShutdownOptions(true);
        button3.setShutdownOptions(true);

        // create and register gpio pin listener
        button1.addListener(new GpioPinListenerDigital() {
            @Override
            public void handleGpioPinDigitalStateChangeEvent(GpioPinDigitalStateChangeEvent event) {
                // display pin state on console
                System.out.println(" --> GPIO PIN STATE CHANGE: " + event.getPin() + " = " + event.getState());//TODO delete debug purposes only
            }

        });

        while(true)
        {
            Logging.log(Level.INFO, "Keymapper thread running"); //TODO delete debug purposes only
            try
            {
                Thread.sleep(10000);
            }
            catch (InterruptedException e)
            {
                e.printStackTrace();
            }
        }

    }
}
