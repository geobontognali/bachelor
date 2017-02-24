<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoorController extends Controller
{
    public function showView()
    {
        return view('doorHome');
    }

    public function client()
    {
        return view('loginRtc');
    }

    public function server()
    {
        return view('testRtc');
    }
}
