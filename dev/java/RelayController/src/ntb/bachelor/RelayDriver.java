package ntb.bachelor;

import com.pi4j.io.gpio.*;

import java.util.logging.Level;

/**
 * Created by Federico on 06.08.2017.
 */
public class RelayDriver extends Thread {


    public void run(){
        final GpioController gpio = GpioFactory.getInstance();
        final GpioPinDigitalOutput pin = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_07, "MyLED", PinState.LOW);

        pin.setState(PinState.HIGH);
        pin.high();

        Logging.log(Level.INFO, "thread started----Waiting 3 sec");
        try {
            Thread.sleep(3000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        pin.low();
        gpio.shutdown();
        Logging.log(Level.INFO, "after 3 sec thread is going to be closed");
    }
}
