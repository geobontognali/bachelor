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
     * Show the list of the doors
     */
    public function getDoorList()
    {
        $doors = DB::table('tbl_door')->get();

        foreach ($doors as $door) {
            echo '
            <tr>
                <th scope="row">'.$door->door_id.'</th>
                <td>'.$door->door_name.'</td>
                <td>'.$door->door_desc.'</td>
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
        $validator =  Validator::make($filledData->all(), [
            'inputName' => 'required|max:255',
            'inputDescription' => 'required|max:255',
        ]);

        if($validator->fails() & isset($filledData->moduleId) & $filledData->moduleId != '')
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else if ($validator->fails())
        {
            return redirect('/door')
                ->withErrors($validator)
                ->withInput();
        }

        $door = new Door;

        $door->door_name = $filledData->inputName;
        $door->door_desc = $filledData->inputDescription;
        $door->save();

        return redirect('/door');

    }

    /**
     * Delete doors
     */
    public function deleteDoor($doorId)
    {
        DB::table('tbl_door')->where('door_id', '=', $doorId)->delete();

        return redirect('/door');
    }

    /**
     * Edit the selected door
     */
    public function editDoor($doorId){
        // Generate the modal content
        $doors = new Door();
        $doors = $doors->where('door_id', $doorId)->first();

        echo'
            <input type="hidden" name="inputId" value="'.$doorId.'">
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

        $validator =  Validator::make($filledData->all(), [
            'inputName' => 'required|max:255',
            'inputDescription' => 'required|max:255',
        ]);

        if($validator->fails() & isset($filledData->doorId) & $filledData->doorId != '')
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else if ($validator->fails())
        {
            return redirect('/door')
                ->withErrors($validator)
                ->withInput();
        }

        $door = App\Door::find($filledData->inputId);

        $door->door_name = $filledData->inputName;
        $door->door_desc = $filledData->inputDescription;
        $door->save();

        return redirect('/door');
    }
}