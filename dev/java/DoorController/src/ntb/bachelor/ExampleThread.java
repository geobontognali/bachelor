package ntb.bachelor;


import java.util.logging.Level;

/**
 * Created by Geo on 20.02.2017.
 *
 * Example Class for an independent Thread
 */
public class ExampleThread extends Thread
{
    String threadName;


    public ExampleThread(String name)
    {
        this.threadName = name;
    }

    public void run()
    {
        while(true)
        {
            System.out.println("Hi from the " + threadName + " thread");
            Logging.log(Level.INFO, "Hello logging");

            try
            {
                Thread.sleep(5000);
            }
            catch (InterruptedException e)
            {
                e.printStackTrace();
            }
        }

    }
}
