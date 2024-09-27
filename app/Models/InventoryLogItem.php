<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLogItem extends Model
{
    protected $table = 'inventory_log_items';

    protected $primaryKey = 'inventory_log_item_id';

    public $timestamps = false;

    protected $fillable = ['inventory_log_id','product_id','stock','count','inventory_log_item_added_on','inventory_log_item_updated_on'];

}