package ntb.bachelor;

import java.util.logging.Logger;

/**
 * Main Deamon for the services on the Pi at the door.
 * This starts and controls the services needed at the door intercom.
 *
 * Authors: G. Bontognali
 *          F. Crameri
 */

public class Main
{
    // Starts logging handlers
    private static final Logger logger = Logger.getLogger("DoorLogger");

    /**
     * Static Initializer
     * @param args
     */
    public static void main(String[] args)
    {
        // Starts the service for turning the speaker on and off
        System.out.println("Hello hello");

        // Start the speaker controller
        SpeakerController speakerController = new SpeakerController();
        speakerController.start();

        // Start some example threads
        ExampleThread exampleThread1 = new ExampleThread("Primo");
        //exampleThread1.start();

        //ExampleThread exampleThread2 = new ExampleThread("Secondo");
        //exampleThread2.start();


    }
}
