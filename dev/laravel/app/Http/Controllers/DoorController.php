<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoorController extends Controller
{
    public function showView()
    {
        return view('doorHome');
    }
}
