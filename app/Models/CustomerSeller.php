<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class CustomerSeller extends Model
{
    protected $table = 'customer_seller';

    protected $primaryKey = 'customer_seller_id';

    public $timestamps = false;

    public function get_Customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function get_User()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}