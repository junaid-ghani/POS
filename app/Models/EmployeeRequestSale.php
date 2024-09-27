<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmployeeRequestSale extends Model
{
    protected $table = 'employee_request_sales';

    protected $primaryKey = 'employee_request_sale_id';

    public $timestamps = false;

    protected $fillable = ['emp_request_id','user_id','location_id','loss_amount','discount','sub_total','total','request_sale_added_on','request_sale_updated_on'];

    public function requestSale()
    {
        return $this->hasMany(EmployeeRequestSaleItem::class,'employee_request_sale_id');
    }
    public function requestUser()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}