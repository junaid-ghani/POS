<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    protected $table = 'employee_request';

    protected $primaryKey = 'emp_request_id';
    public $timestamps = false;

}