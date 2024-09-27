<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $fillable = ['user_role','user_name','user_email','user_password','user_vpassword','user_phone','user_salary_type','user_commission','user_image','user_added_on','user_added_by','user_updated_on','user_updated_by','user_status','pin'];

    protected $hidden = ['user_password'];

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public static function getDetails()
    {
        $user = new User;

        return $user->select('*')
                    ->join('roles','roles.role_id','users.user_role')
                    ->where('user_id','!=',1)
                    ->orderby('user_id','desc')
                    ->get();
    }
    public function get_Seller()
    {
        return $this->hasMany(CustomerSeller::class, 'seller_id');
    }

    public function getCommission()
    {
        return $this->belongsTo(Commission::class,'seller_id');
    }

    public function Requestsales()
    {
        return $this->EmployeeRequestSale(Sale::class,'user_id');
    }
}