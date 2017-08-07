<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resident;
use App\Notification;
use App\Config;

class DoorController extends Controller
{
    private $doorId;
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
            echo 'const doorId = '.$_GET['id'].';';
            $this->doorId = $_GET['id'];
        }
        else
        {
            echo 'alert("No door ID defined! Use GET Parameter id=DOOR_ID");';
        }
    }

    /**
     * Send the order to play the gong to the Relay controller
     **/
    public function playGong($residentId, $doorId)
    {
        // Add notification to the DB
        $notify = new Notification;
        $notify->not_resident = $residentId;
        $notify->not_door = $doorId;
        $notify->not_time = time();
        $notify->not_img = "";
        $notify->not_notificationcol = "";
        $notify->not_received = 0;
        $notify->save();

        // Send signal to the relay controller
        $config = new Config;
        $relayControllerServer = $config->relayControllerServer;
        $relayControllerServerPort = $config->relayControllerServerPort;
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


    /**
     * Generate the HTML for resident sliders
     */
    public function listResidents()
    {
        // Connect to the model
        $residents = new Resident;
        $residents = $residents->get();


        $first = true;
        foreach($residents as $resident)
        {
            if($first)
            {
                $active = 'active';
                $first = false;
            }
            else
            {
                $active = '';
            }

            echo '
                <li class="item '.$active.'">
                    <p class="name">'.$resident->res_displayedName.'</p>
                    <p class="apartment">'.$resident->res_apartment.'</p>
                    <div style="display:none;" class="residentId">'.$resident->res_id.'</div>
                </li>
                ';
        }
    }

    /**
     * Inject the Javascript code containing the IP of the server and stuff
     */
    public function setConfig()
    {
        $config = new Config;
        echo 'const signalingSrvAddr = "'.$config->signalingServerAddress.'"; const signalingSrvPort = "'.$config->signalingServerPort.'"; ';
        echo 'const speakerControllerServer = "'.$config->speakerControllerServer.'"; const speakerControllerServerPort = "'.$config->speakerControllerServerPort.'";';
    }

}
