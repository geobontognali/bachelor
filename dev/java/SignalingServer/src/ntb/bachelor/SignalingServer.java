package ntb.bachelor;

import org.java_websocket.WebSocket;
import org.java_websocket.handshake.ClientHandshake;
import org.java_websocket.server.WebSocketServer;
import org.json.JSONException;
import org.json.JSONObject;

import java.net.InetSocketAddress;
import java.util.*;
import java.util.logging.Level;



/**
 * Created by Geo on 24.02.2017.
 */
public class SignalingServer extends WebSocketServer
{
    private final String DOOR_ONLINE = "DOOR_ONLINE";
    private final String DOOR_REQUEST = "DOOR_REQUEST";

    private int doorId;
    private Map<Integer, WebSocket> onlineDoors = new HashMap<>();
    private Map<Integer,ArrayList<WebSocket>> rooms = new HashMap<>();


    /**
     * Constructor
     */
    public SignalingServer()
    {
        super(new InetSocketAddress(7007));

        Logging.log(Level.INFO, "WebSocket server started");
    }


    /**
     * It forwards the received message to all the other memebers connected in the same room
     * @param currentConnection The current connection with the client, used to avoid resending the message from where it came from
     * @param message The actual message to send
     */
    private void forwardMessage(WebSocket currentConnection, String message)
    {
        Iterator connections = rooms.get(doorId).iterator();
        while (connections.hasNext())
        {
            WebSocket conn = (WebSocket) connections.next();
            if (conn != currentConnection)
            {
                conn.send(message);
            }
        }
    }


    /**
     * When the connection is opened
     */
    @Override
    public void onOpen(WebSocket conn, ClientHandshake handshake)
    {
        Logging.log(Level.INFO, "New client connected: " + conn.getRemoteSocketAddress() + " hash " + conn.getRemoteSocketAddress().hashCode());
    }

    /**
     * When a message is received from a client.
     * This is mostly just forwarding the incoming messages to the connected clients. When a door comes online, or a client makes a door requests
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
                case DOOR_ONLINE:
                    doorId = obj.getInt("doorId");
                    if (onlineDoors.containsKey(doorId)) // Replace old connection with the new one if an older is presents
                    {
                        onlineDoors.remove(doorId);
                        Logging.log(Level.INFO, "A door came online. Older one deleted. ID: " + doorId);
                    }
                    onlineDoors.put(doorId, connection);
                    Logging.log(Level.INFO, "New door online. ID: " + doorId);
                    connection.send("{\"type\":\""+DOOR_ONLINE+"\",\"value\":\"true\"}"); // ACK reply
                    break;

                case DOOR_REQUEST:
                    doorId = obj.getInt("doorId");
                    Logging.log(Level.INFO, "A client wants to call the door " + doorId);
                    if(onlineDoors.get(doorId) != null)
                    {
                        Logging.log(Level.INFO, "The requested door is online. Both partner are in the room");
                        // Creating and joining a room
                        WebSocket doorConnection = onlineDoors.get(doorId);
                        socketConnections = new ArrayList<>();
                        socketConnections.add(connection); // Add the client calling connection
                        socketConnections.add(doorConnection); // Add the door connection
                        rooms.put(doorId, socketConnections);
                        // Signaling
                        connection.send("{\"type\":\""+DOOR_REQUEST+"\",\"value\":1}"); // Replies with 1 if the door is online and available
                    }
                    else
                    {
                        Logging.log(Level.INFO, "The door is offline. Door ID: " + doorId);
                        connection.send("{\"type\":\""+DOOR_REQUEST+"\",\"value\":-1}"); // Replies with -1 if the door is offline
                    }
                    break;

                default: // Straight data exchange between the clients
                    Logging.log(Level.INFO, "Forwarded message: " + message);
                    System.out.println("Door ID:" + doorId );
                    forwardMessage(connection, message);
                    break;
            }
        }
        catch (JSONException e)
        {
            Logging.log(Level.WARNING, "An invalid JSON payload has been received");
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

}
