<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    protected $table = 'inventory_logs';

    protected $primaryKey = 'inventory_log_id';

    public $timestamps = false;

    protected $fillable = ['sale_id','location_id','user_ids','status','inventory_log_added_on','inventory_log_updated_on'];

}