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
    public $relayControllerServer = "192.168.0.213"; // IP Address of the Relay Controller (Should always be localhost for best security)
    public $relayControllerServerPort = 7743; // Port of the Relay Controller
    public $defaultDoor = 2; // ID of the door that is selected by default when the client app is opened
}