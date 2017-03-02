package ntb.bachelor;

import org.java_websocket.WebSocket;
import org.java_websocket.handshake.ClientHandshake;
import org.java_websocket.server.DefaultSSLWebSocketServerFactory;
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
    private final String PICK_UP = "PICK_UP";
    private final String CALL_REQUEST = "CALL_REQUEST";

    private int doorId;
    private ArrayList callRequests = new ArrayList();
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
            WebSocket conn = (WebSocket)connections.next();
            if(conn != currentConnection)
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
        ArrayList<WebSocket> socketConnections; // Used to store the connections of the partners in case multiple users are connected
        try
        {
            // Parse the JSON message to an object
            JSONObject obj = new JSONObject(message);
            //Logging.log(Level.INFO, "JSON Received: " + obj.toString());

            String msgType = obj.getString("type");
            switch (msgType)
            {
                case CALL_REQUEST: // A client tries to call up the door, it add a call request and creates a new room
                    doorId = obj.getInt("doorId");
                    if(!callRequests.contains(doorId))
                    {
                        callRequests.add(doorId);
                    }
                    socketConnections = new ArrayList<>();
                    socketConnections.add(connection);
                    rooms.put(doorId, socketConnections);
                    connection.send("{\"type\":\"CALL_REQUEST\",\"value\":\"ACK\"}"); // Replies with ACK
                    Logging.log(Level.INFO, "A client made a call request for the door: " + doorId + ".");
                    break;

                case PICK_UP: // The web app at the door checks if someone wants to start a call
                    doorId = obj.getInt("doorId");
                    if(callRequests.contains(doorId))
                    {
                        socketConnections = rooms.get(doorId);
                        socketConnections.add(connection);
                        rooms.put(doorId, socketConnections);
                        connection.send("{\"type\":\"PICK_UP\",\"value\":\"true\"}"); // Replies with true if a call has been found
                        callRequests.remove(callRequests.indexOf(doorId)); // Remove the call request
                        Logging.log(Level.INFO, "Door " + doorId + " has found a pending call and joined the room");
                    }
                    else
                    {
                        connection.send("{\"type\":\"PICK_UP\",\"value\":\"false\"}"); // Replies with false if no call is incoming
                        Logging.log(Level.INFO, "Door " + doorId + " is checking for new incoming calls. No pending call has been found");
                    }
                    break;
                default: // Straight data exchange between the clients
                    Logging.log(Level.INFO, "Forwarded message: " + message);
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
    }

}
