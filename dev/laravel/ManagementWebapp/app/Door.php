<?php
/**
 * Created by PhpStorm.
 * User: Federico
 * Date: 20.06.2017
 * Time: 13:57
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Door extends Model
{
    // Table Name
    protected $table = 'tbl_door';
    protected $primaryKey = 'door_id';
}