package ntb.bachelor;

import org.java_websocket.WebSocket;

import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;
import java.util.logging.Level;

/**
 * Created by Geo on 26.03.2017.
 *
 * This class is used to store the active connections of the clients connected to an intercom and the rooms.
 * It has a thread that removes client that were connected for too long (Connections that weren't close properly)
 */
public class RoomsController extends Thread
{
    private int threadSleepTime = 4000;
    private int clientConnTimeLimit = 60_000;
    public Map<Integer,ArrayList<WebSocket>> rooms = new HashMap<>();
    public Map<WebSocket, Integer> clientsConnectedToIntercom = new HashMap<>();
    public Map<WebSocket, Date> clientsConnectionTime = new HashMap<>();



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
            clientsConnectionTime.remove(connection);

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

    /**
     * Thread that every 4 seconds checks if a client has been connected for too long and removes it.
     */
    @Override
    public void run()
    {
        while(true)
        {
            try
            {
                Thread.sleep(threadSleepTime);
            }
            catch (InterruptedException e)
            {
                e.printStackTrace();
            }
            Date now = new Date();

            for (Map.Entry<WebSocket, Date> entry : clientsConnectionTime.entrySet())
            {
                if(now.getTime() - entry.getValue().getTime() > clientConnTimeLimit)
                {
                    leaveRoom(entry.getKey());

                    Logging.log(Level.INFO, "Client removed for inactivity.");
                }
            }
        }
    }
}
