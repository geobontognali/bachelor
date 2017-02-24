package ntb.bachelor;

import java.io.*;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.URL;
import java.security.MessageDigest;
import java.util.Scanner;
import java.util.logging.Level;

import jdk.nashorn.internal.parser.JSONParser;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import javax.xml.bind.DatatypeConverter;
import java.util.Scanner;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * Created by Geo on 23.02.2017.
 */
public class SignalingServer
{

    public SignalingServer() throws Exception
    {
        startSignalingServer();

    }


    public void testJson() throws JSONException
    {
        String out = "";
        JSONObject json = null;

        try
        {
            out = new Scanner(new URL("http://lineup.app/js/json.html").openStream(), "UTF-8").useDelimiter("\\A").next();
        } catch (IOException e)
        {
            e.printStackTrace();
        }


        try
        {
            json = new JSONObject(out);
        }
        catch (JSONException e)
        {
            e.printStackTrace();
            Logging.log(Level.SEVERE, "Unable to parse the received string to a JSON object. Terminating connection.");
        }

        // get the title
        System.out.println(json.get("title"));
        // get the data
        JSONArray genreArray = (JSONArray) json.get("dataset");
        // get the first genre
        JSONObject firstGenre = (JSONObject) genreArray.get(0);
        System.out.println(firstGenre.get("genre_title"));
    }


    /**
     * Start the socket server that waits for incoming messages from the Intercom Webapp
     * @throws Exception Socket exception
     */
    private void startSignalingServer() throws Exception
    {
        // Awaits connection forever, restart if connection closed
        while(true)
        {
            // Start socket (Accepts only from localhost)
            ServerSocket serverSocket = new ServerSocket(9090, 1);
            Socket client = serverSocket.accept();


            System.out.println("A client connected.");

            InputStream in = client.getInputStream();
            OutputStream out = client.getOutputStream();

            String data = new Scanner(in,"UTF-8").useDelimiter("\\r\\n\\r\\n").next();
            Matcher get = Pattern.compile("^GET").matcher(data);

            if (get.find()) {
                Matcher match = Pattern.compile("Sec-WebSocket-Key: (.*)").matcher(data);
                match.find();
                byte[] response = ("HTTP/1.1 101 Switching Protocols\r\n"
                        + "Connection: Upgrade\r\n"
                        + "Upgrade: websocket\r\n"
                        + "Sec-WebSocket-Accept: "
                        + DatatypeConverter
                        .printBase64Binary(
                                MessageDigest
                                        .getInstance("SHA-1")
                                        .digest((match.group(1) + "258EAFA5-E914-47DA-95CA-C5AB0DC85B11")
                                                .getBytes("UTF-8")))
                        + "\r\n\r\n")
                        .getBytes("UTF-8");

                out.write(response, 0, response.length);
            }
            System.out.println("Connected");




            // Checks that the connection comes only from localhost
            String connectionAddr = client.getInetAddress().toString().replace("/","");
            Logging.log(Level.INFO, "Inbound connection from " + connectionAddr );

            // Init Readers/Writers
            BufferedReader socketReader = new BufferedReader(new InputStreamReader(client.getInputStream()));
            PrintStream socketWriter = new PrintStream(client.getOutputStream());

            // As long as connection is open, still waiting for commands
            while (true)
            {
                // Read line from input
                System.out.println("Here");
                String payload = socketReader.readLine();
                System.out.println(payload);
                // Prepare the Json Object
                JSONObject jsonObj = null;

                // System.out.println(payload);
                // socketWriter.println("ACK: " + payload);

                // Act
                if (payload != null)
                {
                    try
                    {
                        jsonObj = new JSONObject(payload);
                    }
                    catch(Exception e)
                    {
                        Logging.log(Level.WARNING, "Unable to parse the received string to a JSON object. Discarding...");
                        continue;
                    }

                    // Handles the Session Descritption (SDP)
                    if(jsonObj.get("type") == "login")
                    {
                        Logging.log(Level.INFO, "Login SDP Message from " + jsonObj.get("name"));
                        socketWriter.println("{ type: \"login\", success: \"true\" "); // ACK the login
                    }

                    // get the title
                    //System.out.println(jsonObj.get("title"));
                    // get the data
                    //JSONArray genreArray = (JSONArray) jsonObj.get("dataset");


                }
                else
                {
                    // Close the socket
                    Logging.log(Level.INFO, "Terminating connection with " + connectionAddr);
                    client.close();
                    serverSocket.close();
                    break;
                }
            }
        }
    }
}
