<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $table = 'sale_items';

    protected $primaryKey = 'sale_item_id';

    public $timestamps = false;

    protected $fillable = ['sale_item_sale','sale_item_product','sale_item_price','sale_item_quantity','sale_item_total','sale_item_added_on','sale_item_added_by','sale_item_updated_on','sale_item_updated_by','sale_item_status'];
    
    public function getProduct()
    {
        return $this->belongsTo(Product::class,'sale_item_product');
    }
}