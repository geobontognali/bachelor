<?php
/**
 * Created by PhpStorm.
 * User: Federico
 * Date: 20.06.2017
 * Time: 13:57
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Door
 * @package App
 * This class represent the eloquent model
 * allowing to interact with the database.
 */
class Door extends Model
{
    // Defining table name and primary key of the table
    protected $table = 'tbl_door';
    protected $primaryKey = 'door_id';
}