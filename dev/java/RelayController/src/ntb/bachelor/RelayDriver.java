package ntb.bachelor;

import com.pi4j.io.gpio.*;

import java.util.logging.Level;

/**
 * Created by Federico on 06.08.2017.
 */
public class RelayDriver extends Thread {

    String msgType;
    int deviceId;

    final GpioController gpio = GpioFactory.getInstance();
    final GpioPinDigitalOutput gong1 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_07, PinState.LOW);
    final GpioPinDigitalOutput gong2 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_00, PinState.LOW);
    final GpioPinDigitalOutput gong3 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_02, PinState.LOW);
    final GpioPinDigitalOutput gong4 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_03, PinState.LOW);
    final GpioPinDigitalOutput gong5 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_21, PinState.LOW);
    final GpioPinDigitalOutput gong6 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_22, PinState.LOW);
    final GpioPinDigitalOutput door1 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_01, PinState.LOW);
    final GpioPinDigitalOutput door2 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_04, PinState.LOW);

    public RelayDriver(String type, int id) {
        msgType = type;
        deviceId = id;
    }

    private void gpioController(GpioPinDigitalOutput pin) {
        pin.high();
        //System.out.println("The type "+type+" and id "+id);
        //Logging.log(Level.INFO, "thread aiting 3 sec");
        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        pin.low();
    }

    public void run(){

        if (msgType == "gong") {
            switch (deviceId) {
                case 1:
                    gpioController(gong1);
                    break;
                case 2:
                    gpioController(gong2);
                    break;
                case 3:
                    gpioController(gong3);
                    break;
                case 4:
                    gpioController(gong4);
                    break;
                case 5:
                    gpioController(gong5);
                    break;
                case 6:
                    gpioController(gong6);
                    break;
                default:
                    System.out.println("mismatch msgType/deviceId");
                    Logging.log(Level.INFO, "mismatch msgType/deviceId");
                    break;
            }

        } else if (msgType == "door") {
            switch (deviceId) {
                case 1:
                    gpioController(door1);
                    break;
                case 2:
                    gpioController(door2);
                    break;
                default:
                    System.out.println("mismatch msgType/deviceId");
                    Logging.log(Level.INFO, "mismatch msgType/deviceId");
                    break;
            }
        }

        Logging.log(Level.INFO, "after 3 sec thread is going to be closed");
    }


}
