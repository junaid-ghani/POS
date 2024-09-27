<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Location extends Authenticatable
{
    protected $table = 'locations';

    protected $primaryKey = 'location_id';

    public $timestamps = false;

    protected $fillable = ['location_name','location_tax','location_address','location_city','location_state','location_zip','location_phone_number','location_password','location_vpassword','location_image','location_added_on','location_added_by','location_updated_on','location_updated_by','location_status'];

    protected $hidden = ['location_password'];

    public function getAuthPassword()
    {
        return $this->location_password;
    }
}