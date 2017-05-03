<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoorController extends Controller
{
    public function showView()
    {
        return view('doorApp');
    }

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

}
