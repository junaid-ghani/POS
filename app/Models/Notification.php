<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $primaryKey = 'notification_id';

    public $timestamps = false;

    protected $fillable = ['name'];    

    public function notificationItem()
    {
        return $this->hasOne(NotificationItem::class,'notification_id');
    }

    
}