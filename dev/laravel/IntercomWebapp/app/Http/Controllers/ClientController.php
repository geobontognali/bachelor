<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Door;
use App\Config;
use App\Notification;
use DB;

class ClientController extends Controller
{
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
        // TODO: Add Security by checking if the user is logged

        $config = new Config;
        $relayControllerServer = $config->relayControllerServer;
        $relayControllerServerPort = $config->relayControllerPort;
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

        $config = new Config;
        if(isset($_GET['userId']))
        {
            $userId = $_GET['userId'];
        }
        else
        {
            $userId = $config->defaultUser.';';
        }

        if(isset($_GET['doorId']))
        {
            $this->activeDoor = $_GET['doorId'];
        }
        else
        {
            $this->activeDoor = $config->defaultDoor;
        }
        foreach($doors as $door)
        {
            if($door->door_id == $this->activeDoor) { $active = "active"; } else { $active = ""; }
            echo '<a href="?doorId='.$door->door_id.'&userId='.$userId.'"><div class="col-xs-4 naviEntry '.$active.'">'.$door->door_name.'</div></a>';
        }
    }

    /**
     * Check the DB for new notifications. Triggered via AJAX by the client-app
     *
     * @param $userId
     * @return The notification itself as JSON string
     */
    public function checkNotification($userId)
    {
        // Get the lines
        $notification = DB::table('tbl_notification')
            ->join('tbl_door', 'door_id', '=', 'not_door')
            ->where('not_resident', '=', $userId)
            ->where('not_received', '=', '0')
            ->get();
        // Return as json
        return  $notification->toJson();
    }

    /**
     * Marks a notification as received when the client confirms that has been received
     * @param $userId
     * @return true
     */
    public function clearNotification($userId)
    {
        // Update the received field
        $editNotification = new Notification();
        $editNotification->where('not_resident', '=', $userId)->where('not_received', '=', '0')->update(['not_received' => 1]);
        // Return as json
        return 'true';
    }


    /**
     * Inject the Javascript code containing the ID of the door. ID passed by get parameter
     */
    public function setDoorId()
    {
        if(isset($_GET['doorId']))
        {
            echo 'const doorId = '.$_GET['doorId'].';';
        }
        else
        {
            $config = new Config;
            echo 'const doorId = '.$config->defaultDoor.';';
        }
    }

    /**
     * Inject the Javascript code containing the ID of the current logged user. ID passed by get parameter
     */
    public function setUserId()
    {
        if(isset($_GET['userId']))
        {
            echo 'const userId = '.$_GET['userId'].';';
        }
        else
        {
            $config = new Config;
            echo 'const userId = '.$config->defaultUser.';';
        }
    }

    /**
     * Inject the Javascript code containing the IP of the server and stuff
     */
    public function setConfig()
    {
        $config = new Config;
        echo 'const signalingSrvAddr = "'.$config->signalingServerAddress.'"; const signalingSrvPort = "'.$config->signalingServerPort.'";';
    }


}
