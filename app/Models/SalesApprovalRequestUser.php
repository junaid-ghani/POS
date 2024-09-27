<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesApprovalRequestUser extends Model
{
    protected $table = 'sales_approval_request_user';

    protected $primaryKey = 'request_user_id';

    public $timestamps = false;

    protected $fillable = ['setting_id','user_id'];
    
    public function setting()
    {
        return $this->belongsTo(Setting::class,'setting_id');
    }
}