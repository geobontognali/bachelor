package ntb.bachelor;

import com.pi4j.io.gpio.*;

import java.util.Objects;
import java.util.logging.Level;

/**
 * Created by Federico on 06.08.2017.
 *
 * Controls the relay board depending on the incoming payload
 * Payload: The first number is the address of the relay and the second is the value
 * i.E. 04-1 -> Relay 4 Status On
 */
public class RelayDriver extends Thread {

    //var
    String msgType;
    int deviceId;
    RelayController relayController;

    /**
     * @param controller Object of the calling class
     * @param type Defines if its a gong or a door
     * @param id The actual id of the element (appartment gong or door)
     */
    public RelayDriver(String type, int id, RelayController controller) {
        msgType = type;
        deviceId = id;
        relayController = controller;
        Logging.log(Level.INFO, "RelayDriver started");
    }

    /**
     * Activates the given pin for 3 secons
     *
     * @param pin the gpio pin that should be activated. It correspond to a gong or a door
     */
    private void gpioController(GpioPinDigitalOutput pin) {
        pin.high();
        Logging.log(Level.INFO, "New input from type: "+msgType+" with id :"+deviceId);
        try {
            Thread.sleep(3000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        pin.low();
    }

    public void run(){
        //for gongs
        //id-pin association
        if (Objects.equals("PLAY_GONG", msgType)) {
            System.out.println("enter if play gong");
            switch (deviceId) {
                case 1:
                    gpioController(relayController.gong1);
                    break;
                case 2:
                    gpioController(relayController.gong2);
                    break;
                case 3:
                    gpioController(relayController.gong3);
                    break;
                case 4:
                    gpioController(relayController.gong4);
                    break;
                case 5:
                    gpioController(relayController.gong5);
                    break;
                case 6:
                    gpioController(relayController.gong6);
                    break;
                default:
                    Logging.log(Level.INFO, "mismatch msgType/deviceId");
                    break;
            }
        //for door
        //id-pin association
        } else if (Objects.equals("OPEN_DOOR", msgType)) {
            switch (deviceId) {
                case 1:
                    gpioController(relayController.door1);
                    break;
                case 2:
                    gpioController(relayController.door2);
                    break;
                default:
                    System.out.println("mismatch msgType/deviceId");
                    Logging.log(Level.INFO, "mismatch msgType/deviceId");
                    break;
            }
        }
    }


}
