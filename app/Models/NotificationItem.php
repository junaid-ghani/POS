<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationItem extends Model
{
    protected $table = 'notification_items';

    protected $primaryKey = 'notification_item_id';

    public $timestamps = false;

    protected $fillable = ['notification_id','trigger_value','user_ids'];  
   
    public function notification()
    {
        return $this->belongsTo(Notification::class,'notification_id');
    } 
}