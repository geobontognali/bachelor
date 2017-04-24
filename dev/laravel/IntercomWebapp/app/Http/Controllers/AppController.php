<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function showView()
    {
        return view('doorHome');
    }

    public function client()
    {
        return view('clientApp');
    }

    public function server()
    {
        return view('serverTest');
    }

    public function door()
    {
        return view('doorApp');
    }

}
