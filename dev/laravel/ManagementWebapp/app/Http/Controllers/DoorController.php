<?php
/**
 * Created by PhpStorm.
 * User: Federico
 * Date: 21.06.2017
 * Time: 12:41
 */

namespace App\Http\Controllers;


class DoorController extends Controller
{
    /**
     * Show the Door view
     */
    public function showView()
    {
        return view('door');
    }
}