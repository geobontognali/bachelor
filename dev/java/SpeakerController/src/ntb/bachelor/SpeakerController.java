package ntb.bachelor;

import java.io.*;
import java.net.*;
import java.util.Date;
import java.util.logging.Level;

import org.java_websocket.WebSocket;
import org.java_websocket.handshake.ClientHandshake;
import org.java_websocket.server.WebSocketServer;
import org.json.JSONException;
import org.json.JSONObject;

import java.net.InetSocketAddress;
import java.util.*;
import java.util.logging.Level;

import com.pi4j.io.gpio.*;


/**
 * Created by Federico on 16.03.2017.
 *
 * Turns the speaker on and off when it receives the command from the Webapp
 */
public class SpeakerController extends WebSocketServer
{

    // Vars
    private final String PERMITTED_HOST = "127.0.0.1";
    private final String SPEAKER_STATUS = "SPEAKER_STATUS";
    private final int TURN_ON = 1;
    private final int TURN_OFF = 0;
    final GpioPinDigitalOutput relay;

    public SpeakerController()
    {
        super(new InetSocketAddress(7000));
        try
        {
            Logging.log(Level.INFO, "Speaker controller started");
        }
        catch (Exception e)
        {
            e.printStackTrace();
        }

        final GpioController gpio = GpioFactory.getInstance();
        relay = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_26, PinState.LOW);
    }

    /**
     * When the connection is opened
     */
    @Override
    public void onOpen(WebSocket conn, ClientHandshake handshake)
    {
        Logging.log(Level.INFO, "New inbound connection: " + conn.getRemoteSocketAddress());
        /*if(connectionAddr.equals(PERMITTED_HOST))
        {
            Logging.log(Level.INFO, "New inbound connection from " + connectionAddr);
        }
        else // Block connections that are not allowed
        {
            Logging.log(Level.SEVERE, "Inbound connection from " + connectionAddr + " - Connection denied");
            socket.close();
            serverSocket.close();
            continue;
        }*/
    }

    /**
     * When a message is received from a client.
     * This is mostly just forwarding the incoming messages to the connected clientsConnectedToIntercom. When a door comes online, or a client makes a door requests
     * saves the information and manages the rooms in which the partners are communicating
     */
    @Override
    public void onMessage(WebSocket connection, String message)
    {
        ArrayList<WebSocket> socketConnections; // Used to store the connections of the partners inside a room
        try
        {
            // Parse the JSON message to an object
            JSONObject obj = new JSONObject(message);
            //Logging.log(Level.INFO, "JSON Received: " + obj.toString());
            String msgType = obj.getString("type");
            switch (msgType)
            {
                case SPEAKER_STATUS: // Only do something if its truly a message to turn on or off the speaker
                    int value = obj.getInt("value");
                    if(value == TURN_OFF)
                    {
                        Logging.log(Level.INFO, "TURNING SPEAKER OFF");
                        relay.low();
                    }
                    else if(value == TURN_ON)
                    {
                        Logging.log(Level.INFO, "TURNING SPEAKER ON");
                        relay.high();
                    }
                    else
                    {
                        Logging.log(Level.INFO, "Unsupported value received");
                    }
                    //connection.send("{\"type\":\""+DOOR_ONLINE+"\",\"value\":\"true\"}"); // ACK reply
                    break;
                default: // Unknow message type
                    Logging.log(Level.INFO, "Unsupported message received: " + message);
                    break;
            }
        }
        catch (JSONException e)
        {
            Logging.log(Level.WARNING, "An invalid JSON payload has been received");
            Logging.log(Level.WARNING, "Payload: " + message);
        }
    }

    /**
     * When the connection closes
     */
    @Override
    public void onClose(WebSocket conn, int code, String reason, boolean remote)
    {
        Logging.log(Level.INFO, "Client disconnected. " + reason);

    }

    /**
     * Error handling
     */
    @Override
    public void onError(WebSocket conn, Exception exc)
    {
        Logging.log(Level.WARNING, "Error happened: " + exc);
    }



    /**
     * Start the socket server that waits for incoming messages from the Intercom Webapp
     * @throws Exception Socket exception

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
                Logging.log(Level.SEVERE, "Inbound connection from " + connectionAddr + " - Connection denied");
                socket.close();
                serverSocket.close();
                continue;
            }




            // Init Readers/Writers
            BufferedReader socketReader = new BufferedReader(new InputStreamReader(socket.getInputStream()));
            //PrintStream socketWriter = new PrintStream(socket.getOutputStream()); // For testing

            // As long as connection is open, still waiting for commands
            while (true)
            {
                // Read line from input
                String payload = socketReader.readLine();
                // Act accordingly
                if (payload != null)
                {
                    // System.out.println(payload);
                    // socketWriter.println("ACK: " + payload);
                    if(payload.equals(String.valueOf(TURN_ON)))
                    {
                        // TODO: Do the shit with the GPIO and stuff

                        Logging.log(Level.INFO, "Speaker turned ON");
                    }
                    else if(payload.equals(String.valueOf(TURN_OFF)))
                    {
                        // TODO: Do the shit with the GPIO and stuff

                        Logging.log(Level.INFO, "Speaker turned OFF");
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
    } */
}
