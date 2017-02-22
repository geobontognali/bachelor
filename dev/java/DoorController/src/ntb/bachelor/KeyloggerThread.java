package ntb.bachelor;


import java.util.logging.Level;
/**
 * Created by Geo on 20.02.2017.
 *
 * Example Class for an independent Thread
 */
public class KeyloggerThread extends Thread
{
    String threadName;


    public KeyloggerThread(String name)
    {
        this.threadName = name;
    }

    public void run()
    {
        while(true)
        {
            System.out.println("Hi from the " + threadName + " thread");
            Logging.log(Level.INFO, "daemon log");

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
