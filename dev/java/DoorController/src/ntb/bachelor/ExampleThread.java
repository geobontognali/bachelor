package ntb.bachelor;

/**
 * Created by Geo on 20.02.2017.
 *
 * Example Class for an independent Thread
 */
public class ExampleThread extends Thread {

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
            try
            {
                Thread.sleep(1000);
            } catch (InterruptedException e)
            {
                e.printStackTrace();
            }
        }

    }
}
