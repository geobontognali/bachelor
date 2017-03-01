package ntb.bachelor;

import java.io.IOException;
import java.security.KeyManagementException;
import java.security.KeyStoreException;
import java.security.NoSuchAlgorithmException;
import java.security.UnrecoverableKeyException;
import java.security.cert.CertificateException;

public class Main {

    public static void main(String[] args) throws Exception
    {
        //new SignalingServer();
        new SSLServer();
    }
}
