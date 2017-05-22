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
     * Generate the HTML for the bottom navigation bar
     */
    public function generateNavi()
    {
        // TODO: Get door list from DB
        $tmpArray1 = array(1211, 1212, 1213);
        $tmpArray2 = array("Garage", "Türe Nord", "Türe Süd");

        if(isset($_GET['id']))
        {
            $this->activeDoor = $_GET['id'];
        }
        else
        {
            $this->activeDoor = $this->defaultDoor;
        }
        for($i = 0; $i<3; $i++)
        {
            if($tmpArray1[$i] == $this->activeDoor) { $active = "active"; } else { $active = ""; }
            echo '<a href="?id='.$tmpArray1[$i].'"><div class="col-xs-4 naviEntry '.$active.'">'.$tmpArray2[$i].'</div></a>';
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
