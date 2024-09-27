<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $primaryKey = 'stock_id';

    public $timestamps = false;

    protected $fillable = ['stock_location','stock_product','stock_quantity','reason','comment','stock_added_on','stock_added_by','stock_updated_on','stock_updated_by','stock_status'];

    public static function getDetails($where)
    {
        $stock = new Stock;

        return $stock->select('*')
                        ->join('locations', 'locations.location_id','=', 'stocks.stock_location')
                        ->join('products', 'products.product_id','=', 'stocks.stock_product')
                        ->join('category', 'category.category_id','=', 'products.product_category')
                        ->where($where)
                        ->orderby('stock_id','desc')
                        ->get();
    }

public static function getProducts($where)
{
    return Stock::select('*')
                ->join('products', 'products.product_id', '=', 'stocks.stock_product')
                ->join('category', 'category.category_id', '=', 'products.product_category')
                ->where($where)
                ->orderBy('products.product_priority', 'asc')
                ->orderBy('products.product_name', 'desc')            
                ->get();
}

}