<?php
/**
 * Created by PhpStorm.
 * User: Federico
 * Date: 18.06.2017
 * Time: 04:26
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Resident
 * @package App
 * This class represent the eloquent model
 * allowing to interact with the database.
 */
class Resident extends Model
{
    // Defining table name and primary key of the table
    protected $table = 'tbl_resident';
    protected $primaryKey = 'res_id';
}