package ntb.bachelor;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.logging.Level;
import org.json.JSONException;
import org.json.JSONObject;

import com.pi4j.io.gpio.GpioController;
import com.pi4j.io.gpio.GpioFactory;
import com.pi4j.io.gpio.GpioPin;
import com.pi4j.io.gpio.GpioPinDigitalInput;
import com.pi4j.io.gpio.GpioPinDigitalOutput;
import com.pi4j.io.gpio.PinDirection;
import com.pi4j.io.gpio.PinMode;
import com.pi4j.io.gpio.PinPullResistance;
import com.pi4j.io.gpio.PinState;
import com.pi4j.io.gpio.RaspiPin;
import com.pi4j.io.gpio.trigger.GpioCallbackTrigger;
import com.pi4j.io.gpio.trigger.GpioPulseStateTrigger;
import com.pi4j.io.gpio.trigger.GpioSetStateTrigger;
import com.pi4j.io.gpio.trigger.GpioSyncStateTrigger;
import com.pi4j.io.gpio.event.GpioPinListener;
import com.pi4j.io.gpio.event.GpioPinDigitalStateChangeEvent;
import com.pi4j.io.gpio.event.GpioPinEvent;
import com.pi4j.io.gpio.event.GpioPinListenerDigital;
import com.pi4j.io.gpio.event.PinEventType;


/**
 * Created by Geo on 22.02.2017.
 */
public class RelayController
{
    // Vars
    private final String PERMITTED_HOST = "127.0.0.1";
    private final int PORT = 7743;
    private final int DOOR_OPEN_TIME = 3; // Time that the door stays open (seconds)
    private final int GONG_PLAY_TIME = 4; // Time that the door stays open (seconds)

    private final String OPEN_DOOR = "OPEN_DOOR";
    private final String PLAY_GONG = "PLAY_GONG";

    public RelayController() throws Exception
    {
        Logging.log(Level.INFO, "Relay Controller Started");
        startSocketServer();
    }

    /**
     * Controls the relay board depending on the incoming payload
     * Payload: The first number is the address of the relay and the second is the value
     * i.E. 04-1 -> Relay 4 Status On
     *
     * @param type Defines if its a gong or a door
     * @param id The actual id of the element (appartment gong or door)
     */
    private void relayDriver(String type, int id)
    {
        Logging.log(Level.INFO, "Action: " + type);
        // TODO: Do the shit with the GPIOs
        /*
        GpioController gpio = GpioFactory.getInstance();
        gpio.provisionDigitalOutputPin(RaspiPin.GPIO_06).high();
        */
    }

    /**
     * Start the socket server that waits for incoming messages from the Intercom Webapp
     * @throws Exception Socket exception
     */
    private void startSocketServer() throws Exception
    {
        // Awaits connection forever, restart if connection closed
        while(true)
        {
            // Start socket (Accepts only from localhost)
            ServerSocket serverSocket = new ServerSocket(PORT, 1);
            Socket socket = serverSocket.accept();

            // Checks that the connection comes only from localhost
            String connectionAddr = socket.getInetAddress().toString().replace("/","");


            if(connectionAddr.equals(PERMITTED_HOST))
            {
                Logging.log(Level.INFO, "New inbound connection from " + connectionAddr);
            }
            else // Block connections that are not allowed
            {
                Logging.log(Level.SEVERE, "Inbound connection from " + connectionAddr + " - Connection denied!");
                socket.close();
                serverSocket.close();
                continue;
            }

            // Init Readers/Writers
            BufferedReader socketReader = new BufferedReader(new InputStreamReader(socket.getInputStream()));
            // As long as connection is open, still waiting for commands
            while (true)
            {
                // Read line from input
                String payload = socketReader.readLine();
                // Act accordingly
                if (payload != null)
                {
                    //System.out.println(payload);
                    try
                    {
                        JSONObject obj = new JSONObject(payload);
                        String msgType = obj.getString("type");
                        switch (msgType)
                        {
                            case OPEN_DOOR: // Controls the relay to open the door
                                int doorId = obj.getInt("doorId");
                                relayDriver(msgType, doorId);
                                break;
                            case PLAY_GONG:
                                int residentId = obj.getInt("residentId");
                                relayDriver(msgType, residentId);
                                break;
                        }
                    }
                    catch (JSONException e)
                    {
                        Logging.log(Level.WARNING, "An invalid JSON payload has been received");
                    }
                }
                else
                {
                    // Close the socket
                    socket.close();
                    serverSocket.close();
                    break;
                }
            }
        }
    }
}
