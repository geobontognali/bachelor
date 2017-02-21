package ntb.bachelor;

import java.io.IOException;
import java.util.logging.*;

/**
 * Created by Geo on 21.02.2017.
 *
 * Class that configures the logging
 * The log method is public and static, so it can be accessed from everywhere in a static way
 *
 * Java Logging levels:
 *  SEVERE (highest value)
 *  WARNING
 *  INFO
 *  CONFIG
 *  FINE
 *  FINER
 *  FINEST (lowest value)
 */
public class Logging
{
    static Logger logger;
    static Formatter plainText;
    public static Handler fileHandler;

    /**
     * Configures the logging
     * @return
     * @throws IOException
     */
    private static Logger configLogger() throws IOException
    {
        // Instance the logger
        logger = Logger.getLogger(Logging.class.getName());
        // Instance the FileHandler
        fileHandler = new FileHandler("myLog.log",true);
        // Instance formatter, set formatting, and handler
        plainText = new SimpleFormatter();
        fileHandler.setFormatter(plainText);
        logger.addHandler(fileHandler);

        return logger;
    }


    /**
     * Actual Log Method
     * @param level
     * @param msg
     */
    public static void log(Level level, String msg)
    {
        if(logger == null) // Configure the logging if not already done
        {
            try
            {
                logger = configLogger();
            }
            catch (IOException e)
            {
                e.printStackTrace();
            }
        }

        logger.log(level, msg);
        //System.out.println(msg);
    }
}
