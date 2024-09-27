<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $primaryKey = 'setting_id';

    public $timestamps = false;

    protected $fillable = ['business_id','business_name','company_name','company_email','company_password','contact_name','contact_address','contact_phone','contact_email'];
    
    public function request_user()
    {

        return $this->hasMany(SalesApprovalRequestUser::class,'setting_id');

    }
}