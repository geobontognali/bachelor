<?php
/**
 * Created by PhpStorm.
 * User: Geo
 * Date: 20.06.2017
 * Time: 16:40
 */

namespace app;


class Config
{
    // CONFIGURATION FILE
    public $relayControllerServer = "127.0.0.1"; // IP Address of the Relay Controller (Should always be localhost for best security)
    public $relayControllerServerPort = 7743; // Port of the Relay Controller (Default 7743)

    public $signalingServerAddress = "192.168.0.18"; // IP Address of the Signaling Server (Be aware: This IP needs to be the public ip of the server)
    public $signalingServerPort = 7007; // Port of the Signaling Server (Default 7007)

    public $defaultDoor = 2; // ID of the door that is selected by default when the client app is opened
    public $defaultUser = 1; // ID of the door that is selected by default when the client app is opened
}