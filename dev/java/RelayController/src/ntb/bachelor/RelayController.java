package ntb.bachelor;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.logging.Level;

import com.pi4j.io.gpio.*;
import org.json.JSONException;
import org.json.JSONObject;


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

    //Pins
    final GpioController gpio = GpioFactory.getInstance();
    final GpioPinDigitalOutput gong1 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_07, PinState.LOW);
    final GpioPinDigitalOutput gong2 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_00, PinState.LOW);
    final GpioPinDigitalOutput gong3 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_02, PinState.LOW);
    final GpioPinDigitalOutput gong4 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_03, PinState.LOW);
    final GpioPinDigitalOutput gong5 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_21, PinState.LOW);
    final GpioPinDigitalOutput gong6 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_22, PinState.LOW);
    final GpioPinDigitalOutput door1 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_01, PinState.LOW);
    final GpioPinDigitalOutput door2 = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_04, PinState.LOW);

    public RelayController() throws Exception
    {
        Logging.log(Level.INFO, "Relay Controller Started");
        startSocketServer();
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
                                RelayDriver rd1 = new RelayDriver( msgType, doorId, this);
                                rd1.start();
                                break;
                            case PLAY_GONG:
                                int residentId = obj.getInt("residentId");
                                RelayDriver rd2 = new RelayDriver( msgType, residentId, this);
                                rd2.start();
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
