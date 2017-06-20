<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Door;

class ClientController extends Controller
{
    private $defaultDoor = "2";

    public function showView()
    {
        return view('clientApp');
    }

    public function showDebugView()
    {
        return view('clientAppDebug');
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
     * Generate the HTML for the bottom navigation bar
     */
    public function generateNavi()
    {
        // Connect to the model
        $doors = new Door;
        $doors = $doors->get();

        if(isset($_GET['id']))
        {
            $this->activeDoor = $_GET['id'];
        }
        else
        {
            $this->activeDoor = $this->defaultDoor;
        }
        foreach($doors as $door)
        {
            if($door->door_id == $this->activeDoor) { $active = "active"; } else { $active = ""; }
            echo '<a href="?id='.$door->door_id.'"><div class="col-xs-4 naviEntry '.$active.'">'.$door->door_name.'</div></a>';
        }
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
