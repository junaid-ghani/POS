<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmployeeRequestSaleItem extends Model
{
    protected $table = 'employee_request_sale_items';

    protected $primaryKey = 'employee_request_sale_item_id';

    public $timestamps = false;

    protected $fillable = ['employee_request_sale_id','product_id','price','quantity','total','request_sale_item_added_on','request_sale_item_updated_on'];

    public function requestSaleItem()
    {
        return $this->belongsTo(EmployeeRequestSale::class,'employee_request_sale_id');
    }

    public function getRequestProduct()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}