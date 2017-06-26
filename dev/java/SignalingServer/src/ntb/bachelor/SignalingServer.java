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
    private final String version = "0.0617b";
    private final String DOOR_ONLINE = "DOOR_ONLINE";
    private final String DOOR_REQUEST = "DOOR_REQUEST";
    private final String DOOR_AVAILABLE = "1";
    private final String DOOR_OFFLINE = "-1";
    private final String DOOR_BUSY = "0";

    private int doorId;
    //private RoomsController roomsController;
    private Map<Integer,ArrayList<WebSocket>> rooms = new HashMap<>();
    private Map<WebSocket, Integer> clientsConnectedToIntercom = new HashMap<>();



    /**
     * Constructor
     */
    public SignalingServer()
    {
        super(new InetSocketAddress(7007));
        //roomsController = new RoomsController();
        //roomsController.start();
        Logging.log(Level.INFO, "WebSocket server started! Version: " + version);
    }


    /**
     * When the connection is opened
     */
    @Override
    public void onOpen(WebSocket conn, ClientHandshake handshake)
    {
        Logging.log(Level.INFO, "New inbound connection: " + conn.getRemoteSocketAddress() + " hash " + conn.getRemoteSocketAddress().hashCode());
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
                case DOOR_ONLINE: // When a door comes online, a room for this door is created
                    doorId = obj.getInt("doorId");
                    if (rooms.containsKey(doorId)) // Replace old connection with the new one if an older is present
                    {
                        rooms.remove(doorId);
                        Logging.log(Level.INFO, "A door came online and was already in the list. Older one deleted. ID: " + doorId);
                    }
                    socketConnections = new ArrayList<>();
                    socketConnections.add(connection);
                    rooms.put(doorId, socketConnections);
                    Logging.log(Level.INFO, "Door online and room created. ID: " + doorId);
                    connection.send("{\"type\":\""+DOOR_ONLINE+"\",\"value\":\"true\"}"); // ACK reply
                    break;

                case DOOR_REQUEST: // When a clientsConnectedToIntercom connects and requires to join the room of a specific door.
                    doorId = obj.getInt("doorId");
                    Logging.log(Level.INFO, "A client wants to communicate with the door " + doorId);
                    if(rooms.get(doorId) != null)
                    {
                        Logging.log(Level.INFO, "The requested door (" + doorId + ") is online. Try to join the room...");

                        if(rooms.get(doorId).size() > 1) // When someone is already communicating with the door
                        {
                            Logging.log(Level.INFO, "The door is busy. Connection denied!");
                            connection.send("{\"type\":\"" + DOOR_REQUEST + "\",\"value\":" + DOOR_BUSY + "}"); // Replies with 0 if the door is online but busy
                        }
                        else // Door available
                        {
                            Logging.log(Level.INFO, "Room joined successfully!");
                            socketConnections = rooms.get(doorId);
                            socketConnections.add(connection);
                            rooms.put(doorId, socketConnections); // Updates the list with the new added connection
                            clientsConnectedToIntercom.put(connection, doorId); // Adds an entry for the current client. This is needed to keep track of who is communicating with who
                            //roomsController.clientsConnectionTime.put(connection, new Date()); // This is needed to check if a connection expires
                            connection.send("{\"type\":\"" + DOOR_REQUEST + "\",\"value\":" + DOOR_AVAILABLE + "}"); // Replies with 1 if the door is online and available
                        }
                    }
                    else
                    {
                        Logging.log(Level.INFO, "The requested door is offline. (Door ID: " + doorId + ")");
                        connection.send("{\"type\":\""+DOOR_REQUEST+"\",\"value\":"+DOOR_OFFLINE+"}"); // Replies with -1 if the door is offline
                    }
                    break;

                default: // Straight data exchange between the clientsConnectedToIntercom
                    Logging.log(Level.INFO, "Forwarded message: " + message);
                    forwardMessage(connection, message);
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
        // Clear client connection
        leaveRoom(conn);
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
     * When a client disconnects, his connection needs to be removed from any room in which it was present. This methods searches the Hashmaps for the connection and removes it
     * @param connection The connection that needs to be cleared
     */
    public void leaveRoom(WebSocket connection)
    {
        try
        {
            // Get the id of the door with which it was communicating
            int doorId = clientsConnectedToIntercom.get(connection);
            clientsConnectedToIntercom.remove(connection);
            //clientsConnectionTime.remove(connection);

            // Remove the connection from the connection-list with this door and updates the rooms list
            ArrayList<WebSocket> socketConnections = rooms.get(doorId);
            socketConnections.remove(connection);
            rooms.put(doorId, socketConnections);

            Logging.log(Level.INFO, "Client removed from the connection list of the door " + doorId);
        }
        catch(Exception e)
        {
            Logging.log(Level.INFO, "Unable to remove the client connection. Not a client or maybe never connected?");
        }
    }
}
