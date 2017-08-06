package ntb.bachelor;

/**
 * Created by Geo on 06.08.2017.
 */
public class Main
{
    public static void main(String[] args) throws Exception
    {
        //new SignalingServer(); // Start this directly if no SSL is needed
        SpeakerController sc = new SpeakerController();
        sc.start(); // Starts the server
    }
}
