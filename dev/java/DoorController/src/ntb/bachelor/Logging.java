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
    private static Level logLevel = Level.INFO; // Change this for production release
    private static Logger logger;

    /**
     * Configures the logging
     * @return Logger
     * @throws IOException Logger exception
     */
    private static Logger configLogger() throws IOException
    {
        // Instance the logger
        logger = Logger.getLogger(Logging.class.getName());
        // Instance the FileHandler
        Handler fileHandler = new FileHandler("myLog.log",true);
        // Instance formatter, set formatting, and handler
        Formatter plainText = new SimpleFormatter();
        fileHandler.setFormatter(plainText);
        logger.addHandler(fileHandler);
        logger.setLevel(logLevel);

        return logger;
    }


    /**
     * Actual Log Method
     * @param level Set log level of this message
     * @param msg The actual message
     */
    static void log(Level level, String msg)
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
    }
}
