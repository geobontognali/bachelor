package ntb.bachelor;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.security.*;
import java.security.cert.CertificateException;

import javax.net.ssl.KeyManagerFactory;
import javax.net.ssl.SSLContext;
import javax.net.ssl.TrustManagerFactory;

import org.java_websocket.server.DefaultSSLWebSocketServerFactory;

public class SSLServer
{
    /**
     * INFO:
     * Create self-signed certificate:
     * keytool -genkey -validity 3650 -keystore "keystore.jks" -storepass "storepassword" -keypass "keypassword" -alias "default" -dname "CN=127.0.0.1, OU=MyOrgUnit, O=MyOrg, L=MyCity, S=MyRegion, C=MyCountry"
     *
     * Match keystore data with the data in the variables
     */

    // Vars
    private final String STORE_TYPE = "JKS";
    private final String KEYSTORE_FILE = "keystore.jks";
    private final String KEYSTORE_PASSWORD = "marendon";
    private final String KEY_PASSWORD = "marendon";

    /**
     * Constructor - Starts up the WebSocket server with TLS support
     */
    public SSLServer() throws Exception
    {

        SignalingServer signalingServer = new SignalingServer(); // Initialise the WebSocket server class
        SSLContext sslContext = configSSL(); // Configures TLS
        signalingServer.setWebSocketFactory(new DefaultSSLWebSocketServerFactory(sslContext)); // Applies config
        signalingServer.start(); // Starts the server
    }

    /**
     * Import the Keystore file that contains the certificate needed to establish a TLS connection (WSS)
     *
     * @return The configuration object needed for the TLS connection
     * @throws KeyStoreException Keystore Exception
     * @throws UnrecoverableKeyException Unrecoverable Key Exception
     * @throws NoSuchAlgorithmException No such algorithm exception
     * @throws KeyManagementException Key Management Exception
     */
    private SSLContext configSSL() throws Exception
    {
        // Import and load the keystore file
        KeyStore keyStore = KeyStore.getInstance(STORE_TYPE);
        File keystoreFile = new File(KEYSTORE_FILE);
        keyStore.load( new FileInputStream(keystoreFile), KEYSTORE_PASSWORD.toCharArray());

        // Implement the keystore file and certificate
        KeyManagerFactory keyManagerFactory = KeyManagerFactory.getInstance("SunX509");
        keyManagerFactory.init(keyStore, KEY_PASSWORD.toCharArray() );
        TrustManagerFactory trustManagerFactory = TrustManagerFactory.getInstance("SunX509");
        trustManagerFactory.init(keyStore);

        // Build the SSLContext object to be returned
        SSLContext sslContext;
        sslContext = SSLContext.getInstance("TLS");
        sslContext.init( keyManagerFactory.getKeyManagers(), trustManagerFactory.getTrustManagers(), null );

        return sslContext;
    }

}
