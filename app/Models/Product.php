<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'product_id';

    public $timestamps = false;

    protected $fillable = ['product_category','product_name','product_code','product_cost_price','product_min_price','product_retail_price','product_priority','product_image','product_added_on','product_added_by','product_updated_on','product_updated_by','product_status'];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class,'sale_item_product');
    }
    public function saleRequestItems()
    {
        return $this->hasMany(EmployeeRequestSaleItem::class,'product_id');
    }

    public static function getDetails()
    {
        $product = new Product;

        return $product->select('*')
                        ->join('category','category.category_id','products.product_category')
                        ->orderby('product_id','desc')
                        ->get();
    }

    public static function getDetail($where)
    {
        $product = new Product;

        return $product->select('*')
                        ->join('category','category.category_id','products.product_category')
                        ->where($where)
                        ->first();
    }

    public static function getStockDetails($where)
    {
        $product = new Product;

        return $product->select('*')
                        ->join('stocks', 'stocks.stock_product','=', 'products.product_id')
                        ->where($where)
                        ->orderby('product_id','desc')
                        ->get();
    }
}