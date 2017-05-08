<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoorController extends Controller
{
    /**
     * Returns the page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showView()
    {
        return view('doorApp');
    }

    /**
     * Inject the Javascript code containing the ID of the door. ID passed by get parameter
     */
    public function setDoorId()
    {
        if(isset($_GET['id']))
        {
            echo 'const intercomId = '.$_GET['id'].';';
        }
        else
        {
            echo 'alert("No door ID defined");';
        }
    }

    /**
     * Send the order to play the gong to the Relay controller
     **/
    public function playGong($residentId)
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
            $msg = "{ type: 'PLAY_GONG', residentId: '".$residentId."', play: 'true'}";
            fwrite($socket, $msg);
        }
        fclose($socket);

        return "true";
    }

}
