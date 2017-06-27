<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resident;
use App;
use App\Door;
use DB;
use Validator;

/**
 * Class ResidentController
 * @package App\Http\Controllers
 * This class handles all the backend task of the Resident view such as showing the view and performing CRUD operation
 * on the database using the eloquent ORM
 */
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
     * generate a list of all the residents
     */
    public function getResidentList()
    {
        //Eloquent model object
        $residents = App\Resident::all();

        //iterating through the residents
        foreach ($residents as $resident) {
            echo '
            <tr>
                <th scope="row">'.$resident->res_id.'</th>
                <td>'.$resident->res_name.'</td>
                <td class="visible-md visible-lg">'.$resident->res_username.'</td>
                <td class="visible-md visible-lg">'.$resident->res_displayedName.'</td>
                <td class="visible-md visible-lg">'.$resident->res_apartment.'</td>
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

        //Naming the input attributes for the outputted errors
        $attributeNames = array(
            'inputName' => 'Name',
            'inputUsername' => 'Benutzername',
            'inputDisplayedname' => 'Angezeigte Name',
            'inputApartment' => 'Wohnung',
            'inputPassword' => 'Passwort',
            'inputPasswordConfirmation' => 'Passwort Bestätigung',
        );

        //validator rules
        $rules = array(
            'inputName' => 'required|max:45',
            'inputUsername' => 'required|max:45',
            'inputDisplayedname' => 'required|max:16',
            'inputApartment' => 'required|max:16',
            'inputPassword' => array('required', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/'), //Minimum eight characters, at least one letter and one number:
            'inputPasswordConfirmation' => 'required|same:inputPassword'
        );

        $validator =  Validator::make($filledData->all(), $rules);
        $validator->setAttributeNames($attributeNames);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //inserting data into the database
        $resident = new Resident;
        $resident->res_pw = md5($filledData->inputPassword);
        $resident->res_name = $filledData->inputName;
        $resident->res_username = $filledData->inputUsername;
        $resident->res_displayedName = $filledData->inputDisplayedname;
        $resident->res_apartment = $filledData->inputApartment;
        $resident->save();

        return redirect('/resident?residentSuccessfullyAdded=true');
    }

    /**
     * Generate the modal popup dialog and insert the data from the database
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

        //Naming the input attributes for the outputted errors
        $attributeNames = array(
            'inputName' => 'Name',
            'inputUsername' => 'Benutzername',
            'inputDisplayedname' => 'Angezeigte Name',
            'inputApartment' => 'Wohnung',
            'inputPassword' => 'Passwort',
            'inputPasswordConfirmation' => 'Passwort Bestätigung',
        );

        //validator rules
        $rules = array(
            'inputName' => 'required|max:45',
            'inputUsername' => 'required|max:45',
            'inputDisplayedname' => 'required|max:16',
            'inputApartment' => 'required|max:16',
            'inputPassword' => array('regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/'), //Minimum eight characters, at least one letter and one number:
            'inputPasswordConfirmation' => 'same:inputPassword'
        );

        $validator =  Validator::make($filledData->all(), $rules);
        $validator->setAttributeNames($attributeNames);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //update database content
        $resident = App\Resident::find($filledData->inputId);
        $resident->res_pw = md5($filledData->inputPassword);
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
     * function for filling the db with some data
     */
    public function populateDummy()
    {
        DB::statement("SET foreign_key_checks=0");
        Resident::truncate();
        Door::truncate();
        DB::statement("SET foreign_key_checks=1");

        $residents = new Resident;
        $residents->res_pw = md5("DefaultPassword1");
        $residents->res_name = "Federico Crameri";
        $residents->res_username = "fcrameri";
        $residents->res_displayedName = "F. Crameri";
        $residents->res_apartment = "3 OG Rechts";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = md5("DefaultPassword1");
        $residents->res_name = "Geo Bontognali";
        $residents->res_username = "gbontognali";
        $residents->res_displayedName = "G. Bontognali";
        $residents->res_apartment = "1 OG Links";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = md5("DefaultPassword1");
        $residents->res_name = "Jöri Gredig";
        $residents->res_username = "ggredig";
        $residents->res_displayedName = "J. Gredig";
        $residents->res_apartment = "2 OG Links";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = md5("DefaultPassword1");
        $residents->res_name = "Ulrich Hauser";
        $residents->res_username = "uhauser";
        $residents->res_displayedName = "U. Hauser";
        $residents->res_apartment = "4 OG Links";
        $residents->save();

        $residents = new Resident;
        $residents->res_pw = md5("DefaultPassword1");
        $residents->res_name = "Lukas Toggenburger";
        $residents->res_username = "ltoggenburger";
        $residents->res_displayedName = "L. Toggenburger";
        $residents->res_apartment = "2 OG Rechts";
        $residents->save();

        $doors = new Door;
        $doors->door_name = "Garage";
        $doors->door_desc = "Garage unterirdisch nord";
        $doors->door_nr = 23;
        $doors->save();

        $doors = new Door;
        $doors->door_name = "Süd";
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
