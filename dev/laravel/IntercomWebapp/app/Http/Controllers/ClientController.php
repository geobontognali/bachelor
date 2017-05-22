<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    private $defaultDoor = "1212";

    public function showView()
    {
        return view('clientApp');
    }

    // Send the order to open the door to the Relay controller
    public function openDoor($doorId)
    {
        $relayControllerServer = "192.168.0.213";
        $relayControllerServerPort = 7743;
        // Connects to the server
        $socket = fsockopen($relayControllerServer, $relayControllerServerPort);
        if(!$socket)
        {
            return "false";
        }
        else
        {
            // Sends the order to the Relay Server
            $msg = "{ type: 'OPEN_DOOR', doorId: '".$doorId."', open: 'true'}";
            fwrite($socket, $msg);
        }
        fclose($socket);

        return "true";
    }

    /**
     * Inject the Javascript code containing the ID of the door. ID passed by get parameter
     */
    public function setDoorId()
    {
        if(isset($_GET['id']))
        {
            echo 'const doorId = '.$_GET['id'].';';
        }
        else
        {
            echo 'const doorId = '.$this->defaultDoor.';';
            //echo 'alert("No door ID defined");';
        }
    }


}
