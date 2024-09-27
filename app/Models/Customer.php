<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $primaryKey = 'customer_id';

    public $timestamps = false;

    protected $fillable = ['customer_name','customer_email','customer_phone','customer_gender','customer_dob','customer_address','location_added_by','location_updated_by','customer_added_on','customer_added_by','customer_updated_on','customer_updated_by','customer_status'];

    public function get_Customer_Seller()
    {
        return $this->hasMany(CustomerSeller::class,'customer_id');
    }
    
}