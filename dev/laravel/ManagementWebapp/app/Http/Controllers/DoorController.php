<?php
/**
 * Created by PhpStorm.
 * User: Federico
 * Date: 21.06.2017
 * Time: 12:41
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Door;
use DB;
use Validator;

/**
 * Class DoorController
 * @package App\Http\Controllers
 * This class handles all the backend task of the Door view such as showing the view and performing CRUD operation
 * on the database using the eloquent ORM
 */
class DoorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('door');
    }

    /**
     * Show the Door view
     */
    public function showView()
    {
        return view('door');
    }

    /**
     * generate a list of all the doors
     */
    public function getDoorList()
    {
        //Eloquent model object
        $doors= Door::orderBy('door_nr')->get();

        //iterating through the doors
        foreach ($doors as $door) {
            echo '
            <tr>
                <th scope="row">'.$door->door_nr.'</th>
                <td>'.$door->door_name.'</td>
                <td class="visible-md visible-lg">'.$door->door_desc.'</td>
                <td>
                    <form action="" method="GET">
                        <button class="btn btn-primary btn-xs" type="submit" name="editDoor" value="'.$door->door_id.'"><span class="glyphicon glyphicon-pencil"></button>
                    </form>                
                </td>
                <td>
                    <form action="door/deleteDoor/'.$door->door_id.'" method="GET">
                        <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
                    </form>
                </td>
            </tr>';
        }
    }

    /**
     * Add a new door to the database
     */
    public function addDoor(Request $filledData){

        //Naming the input attributes for the outputted errors
        $attributeNames = array(
            'inputDoorId' => 'Türe Id',
            'inputName' => 'Name',
            'inputDescription' => 'Beschreibung'
        );

        //validator rules
        $rules = array(
            'inputDoorId' => 'required|integer',
            'inputName' => 'required|max:9',
            'inputDescription' => 'required|max:255'
        );

        $validator =  Validator::make($filledData->all(), $rules);
        $validator->setAttributeNames($attributeNames);


        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //inserting data into the database
        $door = new Door;
        $door->door_nr = $filledData->inputDoorId;
        $door->door_name = $filledData->inputName;
        $door->door_desc = $filledData->inputDescription;
        $door->save();

        return redirect('/door?doorSuccessfullyAdded=true');
    }

    /**
     * Delete doors
     */
    public function deleteDoor($doorId)
    {
        DB::table('tbl_door')->where('door_id', '=', $doorId)->delete();
        return redirect('/door?doorSuccessfullyDeleted=true');
    }

    /**
     * Generate the modal popup dialog and insert the data from the database
     */
    public function editDoor($doorId){
        // Generate the modal content
        $doors = new Door();
        $doors = $doors->where('door_id', $doorId)->first();

        echo'
            <input type="hidden" name="inputId" value="'.$doorId.'">
            <div class="form-group">
                <label class="col-sm-2 control-label">Türe Id</label>
                <input type="number" class="form-control" name="inputDoorId" id="inputDoorId" placeholder="z.B 8" value="'.$doors->door_nr.'">
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Name</label>
                <input type="text" class="form-control" name="inputName" id="inputName" value="'.$doors->door_name.'">
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Beschreibung</label>
                <input type="text" class="form-control" name="inputDescription" id="inputDescription" value="'.$doors->door_desc.'">
            </div>
        ';
    }

    /**
     * saves the modified door to the database
     */
    public function updateDoor(Request $filledData){

        //Naming the input attributes for the outputted errors
        $attributeNames = array(
            'inputDoorId' => 'Türe Id',
            'inputName' => 'Name',
            'inputDescription' => 'Beschreibung'
        );

        //validator rules
        $rules = array(
            'inputDoorId' => 'required|integer',
            'inputName' => 'required|max:9',
            'inputDescription' => 'required|max:255'
        );

        $validator =  Validator::make($filledData->all(), $rules);
        $validator->setAttributeNames($attributeNames);


        if ($validator->fails())
        {
            return redirect('/door')->withErrors($validator)->withInput();
        }

        //update database content
        $door = App\Door::find($filledData->inputId);
        $door->door_name = $filledData->inputName;
        $door->door_desc = $filledData->inputDescription;
        $door->door_nr = $filledData->inputDoorId;
        $door->save();

        return redirect('/door?doorSuccessfullyUpdated=true');
    }
}