package ntb.bachelor;

import java.io.*;
import java.net.*;
import java.util.Date;
import java.util.logging.Level;

/**
 * Created by Federico on 16.03.2017.
 *
 * Turns the speaker on and off when it receives the command from the Webapp
 */
public class SpeakerController
{

    // Vars
    private final String PERMITTED_HOST = "127.0.0.1";
    private final int TURN_ON = 1;
    private final int TURN_OFF = 0;

    public void main(String[] args) {
        try
        {
            Logging.log(Level.INFO, "Speaker controller started");
            startSocketServer(); // Start the socket
        }
        catch (Exception e)
        {
            e.printStackTrace();
        }
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
            ServerSocket serverSocket = new ServerSocket(7000, 1);
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
    }
}
