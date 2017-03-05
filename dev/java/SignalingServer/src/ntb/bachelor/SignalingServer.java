package ntb.bachelor;

import org.java_websocket.WebSocket;
import org.java_websocket.handshake.ClientHandshake;
import org.java_websocket.server.DefaultSSLWebSocketServerFactory;
import org.java_websocket.server.WebSocketServer;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.net.InetSocketAddress;
import java.util.*;
import java.util.logging.Level;




/**
 * Created by Geo on 24.02.2017.
 */
public class SignalingServer extends WebSocketServer
{
    private final String DOOR_ONLINE = "DOOR_ONLINE";
    private final String CALL_REQUEST = "CALL_REQUEST";

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
     * Override Methods from the WebSocketServer Class
     */
    @Override
    public void onOpen(WebSocket conn, ClientHandshake handshake)
    {
        Logging.log(Level.INFO, "New client connected: " + conn.getRemoteSocketAddress() + " hash " + conn.getRemoteSocketAddress().hashCode());
    }

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
                        Logging.log(Level.INFO, "A door became online. Older one deleted. ID: " + doorId);
                    }
                    onlineDoors.put(doorId, connection);
                    Logging.log(Level.INFO, "New door online. ID: " + doorId);
                    connection.send("{\"type\":\"DOOR_ONLINE\",\"value\":\"true\"}"); // ACK reply
                    break;

                case CALL_REQUEST:
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
                        connection.send("{\"type\":\"CALL_REQUEST\",\"value\":\"true\"}"); // Replies with true if the door is online
                        //doorConnection.send("{\"type\":\"PICK_UP\",\"value\":\"true\"}"); // Sends a signal to the door pi to pick up the call
                    }
                    else
                    {
                        Logging.log(Level.INFO, "The door is offline. Door ID: " + doorId);
                        connection.send("{\"type\":\"CALL_REQUEST\",\"value\":\"false\"}"); // Replies with false if the door is offline
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

    @Override
    public void onClose(WebSocket conn, int code, String reason, boolean remote)
    {
        Logging.log(Level.INFO, "Client disconnected. " + reason);

    }

    @Override
    public void onError(WebSocket conn, Exception exc)
    {
        Logging.log(Level.WARNING, "Error happened: " + exc);
        conn.send("ERRORACCIO"); // Replies with false if the door is offline
    }

}
