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
     * Show the list of the residents
     */
    public function getResidentList()
    {
        $residents = DB::table('tbl_resident')->get();

        foreach ($residents as $resident) {
            echo '
            <tr>
                <th scope="row">'.$resident->res_id.'</th>
                <td>'.$resident->res_name.'</td>
                <td>'.$resident->res_secondName.'</td>
                <td>'.$resident->res_displayedName.'</td>
                <td>'.$resident->res_apartment.'</td>
                <td>
                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                    <form action="resident/deleteResident/'.$resident->res_id.'" method="GET">
                        <button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" type="submit"><span class="glyphicon glyphicon-pencil"></span></button>
                    </form>
                    </p>
                </td>
                <td>
                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                    <form action="home/deleteResident/'.$resident->res_id.'" method="GET">
                        <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
                    </form>
                    </p>
                </td>
            </tr>';
        }
    }

    /**
     * Delete Residents
     */
    public function deleteResident($residentId)
    {
        DB::table('tbl_resident')->where('res_id', '=', $residentId)->delete();

        return redirect('/home');
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
