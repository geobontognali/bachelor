<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resident;
use App\Door;
use DB;

class ResidentController extends Controller
{
    /**
     * Show the Resident view
     */
    public function showView()
    {
        return view('home');
    }

    /**
     * Test function for filling the db with some data
     */
    public function populateDummy()
    {
        DB::statement("SET foreign_key_checks=0");
        Resident::truncate();
        Door::truncate();
        DB::statement("SET foreign_key_checks=1");

        $residents = new Resident;
        $residents->res_pw = "1234";
        $residents->res_name = "Federico";
        $residents->res_secondName = "Crameri";
        $residents->res_displayedName = "F. Crameri";
        $residents->res_apartment = "3 OG Rechts";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = "1234";
        $residents->res_name = "Geo";
        $residents->res_secondName = "Bontognali";
        $residents->res_displayedName = "G. Bontognali";
        $residents->res_apartment = "1 OG Links";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = "1234";
        $residents->res_name = "Jöri";
        $residents->res_secondName = "Gredig";
        $residents->res_displayedName = "J. Gredig";
        $residents->res_apartment = "2 OG Links";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = "1234";
        $residents->res_name = "Ulrich";
        $residents->res_secondName = "Hauser";
        $residents->res_displayedName = "U. Hauser";
        $residents->res_apartment = "4 OG Links";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = "1234";
        $residents->res_name = "Lukas";
        $residents->res_secondName = "Toggenburger";
        $residents->res_displayedName = "L. Toggenburger";
        $residents->res_apartment = "2 OG Rechts";
        $residents->save();

        $doors = new Door;
        $doors->door_name = "Türe 1";
        $doors->door_desc = "Garage untererdisch";
        $doors->save();

        $doors = new Door;
        $doors->door_name = "Türe 2";
        $doors->door_desc = "Eingang Süden";
        $doors->save();

        $doors = new Door;
        $doors->door_name = "Türe 3";
        $doors->door_desc = "Eingang Westen";
        $doors->save();

        return $this->showView();
    }
}
