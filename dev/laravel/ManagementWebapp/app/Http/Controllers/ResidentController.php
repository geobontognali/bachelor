<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resident;
use App;
use App\Door;
use DB;
use Validator;

class ResidentController extends Controller
{
    /**
     * Show the Resident view
     */
    public function showView()
    {
        return view('resident');
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
                <td>'.$resident->res_username.'</td>
                <td>'.$resident->res_displayedName.'</td>
                <td>'.$resident->res_apartment.'</td>
                <td>
                    <form action="" method="GET">
                        <button class="btn btn-primary btn-xs" type="submit" name="editResident" value="'.$resident->res_id.'"><span class="glyphicon glyphicon-pencil"></button>
                    </form>                
                </td>
                <td>
                    <form action="resident/deleteResident/'.$resident->res_id.'" method="GET">
                        <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
                    </form>
                </td>
            </tr>';
        }
    }

    /**
     * Add a new resident to the database
     */
    public function addResident(Request $filledData){

        $attributeNames = array(
            'inputName' => 'Name',
            'inputUsername' => 'Benutzername',
            'inputDisplayedname' => 'Angezeigte Name',
            'inputApartment' => 'Wohnung',
            'inputPassword' => 'Passwort',
            'inputPasswordConfirmation' => 'Passwort Bestätigung',
        );

        $validator =  Validator::make($filledData->all(), [
            'inputName' => 'required|max:45',
            'inputUsername' => 'required|max:45',
            'inputDisplayedname' => 'required|max:16',
            'inputApartment' => 'required|max:16',
            'inputPassword' => 'required|regex:^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$^', //Minimum eight characters, at least one letter and one number:
            'inputPasswordConfirmation' => 'required|same:inputPassword'
        ]);

        $validator->setAttributeNames($attributeNames);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
            //return redirect('/settings')
                //->withErrors($validator)
                //->withInput();
        }

        $resident = new Resident;

        $resident->res_pw = $filledData->inputPassword;
        $resident->res_name = $filledData->inputName;
        $resident->res_username = $filledData->inputUsername;
        $resident->res_displayedName = $filledData->inputDisplayedname;
        $resident->res_apartment = $filledData->inputApartment;
        $resident->save();

        return redirect('/resident?residentSuccessfullyAdded=true');
    }

    /**
     * Edit the selected resident
     */
    public function editResident($residentId){
        // Generate the modal content
        $residents = new Resident();
        $residents = $residents->where('res_id', $residentId)->first();

        echo'
            <input type="hidden" name="inputId" value="'.$residentId.'">
            <div class="form-group">
                <label class="col-sm-2 control-label">Name</label>
                <input type="text" class="form-control" name="inputName" id="inputName" value="'.$residents->res_name.'">
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Benutzername</label>
                <input type="text" class="form-control" name="inputUsername" id="inputUsername" value="'.$residents->res_username.'">
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Angezeigte Name</label>
                <input type="text" class="form-control" name="inputDisplayedname" id="inputDisplayedname" value="'.$residents->res_displayedName.'">
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Wohnung</label>
                <input type="text" class="form-control" name="inputApartment" id="inputApartment" value="'.$residents->res_apartment.'">
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label">Neue Passwort</label>
                <input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="passwort">
            </div>
            <div class="form-group">
                <label class="col-sm-5 control-label">Neue Passwort bestätigen</label>
                <input type="password" class="form-control" name="inputPasswordConfirmation" id="inputPasswordConfirmation" placeholder="passwort">
            </div>
        ';
    }

    /**
     * saves the modified resident to the database
     */
    public function updateResident(Request $filledData){

        $validator =  Validator::make($filledData->all(), [
            'inputName' => 'required|max:45',
            'inputUsername' => 'required|max:45',
            'inputDisplayedname' => 'required|max:16',
            'inputApartment' => 'required|max:16',
            'inputPassword' => 'regex:^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$^', //Minimum eight characters, at least one letter and one number:
            'inputPasswordConfirmation' => 'same:inputPassword'
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $resident = App\Resident::find($filledData->inputId);

        if($filledData->inputPassword != ""){
            $resident->res_pw = $filledData->inputPassword;
        }
        $resident->res_name = $filledData->inputName;
        $resident->res_username = $filledData->inputUsername;
        $resident->res_displayedName = $filledData->inputDisplayedname;
        $resident->res_apartment = $filledData->inputApartment;
        $resident->save();

        return redirect('/resident?residentSuccessfullyUpdated=true');
    }

    /**
     * Delete Residents
     */
    public function deleteResident($residentId)
    {
        DB::table('tbl_resident')->where('res_id', '=', $residentId)->delete();

        return redirect('/resident?residentSuccessfullyDeleted=true');
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
        $residents->res_name = "Federico Crameri";
        $residents->res_username = "cuciu";
        $residents->res_displayedName = "F. Crameri";
        $residents->res_apartment = "3 OG Rechts";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = "1234";
        $residents->res_name = "Geo";
        $residents->res_username = "Bontognali";
        $residents->res_displayedName = "G. Bontognali";
        $residents->res_apartment = "1 OG Links";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = "1234";
        $residents->res_name = "Jöri";
        $residents->res_username = "Gredig";
        $residents->res_displayedName = "J. Gredig";
        $residents->res_apartment = "2 OG Links";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = "1234";
        $residents->res_name = "Ulrich";
        $residents->res_username = "Hauser";
        $residents->res_displayedName = "U. Hauser";
        $residents->res_apartment = "4 OG Links";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = "1234";
        $residents->res_name = "Lukas";
        $residents->res_username = "Toggenburger";
        $residents->res_displayedName = "L. Toggenburger";
        $residents->res_apartment = "2 OG Rechts";
        $residents->save();

        $doors = new Door;
        $doors->door_name = "Türe 1";
        $doors->door_desc = "Garage untererdisch";
        $doors->door_nr = 23;
        $doors->save();

        $doors = new Door;
        $doors->door_name = "Türe 2";
        $doors->door_desc = "Eingang Süden";
        $doors->door_nr = 30;
        $doors->save();

        $doors = new Door;
        $doors->door_name = "Türe 3";
        $doors->door_desc = "Eingang Westen";
        $doors->door_nr = 56;
        $doors->save();

        return $this->showView();
    }
}
