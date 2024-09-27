<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Sale extends Model
{
    protected $table = 'sales';

    protected $primaryKey = 'sale_id';

    public $timestamps = false;

    protected $fillable = ['sale_location','sale_invoice_no','sale_customer','sale_sellers','sale_sub_total','sale_discount_type','sale_discount','sale_discount_amount','sale_tax','sale_tax_amount','sale_grand_total','change_amount','sale_pay_cash','sale_pay_ccard','sale_pay_dcard','sale_pay_payment_app','sale_pay_check','sale_pay_split_payment','sale_pay_camount','sale_pay_ccamount','sale_pay_pa_amount','sale_pay_ckamount','sale_pay_cc_transaction','sale_pay_dc_transaction','sale_pay_cc_last_no','sale_pay_pa_transaction','sale_pay_check_transaction','sale_pay_check_no','sale_pay_sp_transaction','sale_added_on','sale_added_by','sale_updated_on','sale_updated_by','sale_status'];


    public static function getDetails($where)
    {
        $sale = new Sale;

        return $sale->select('*')
                        // ->join('customers', 'customers.customer_id','=', 'sales.sale_customer')
                        ->join('locations', 'locations.location_id','=', 'sales.sale_location')
                        ->join('users', 'users.user_id','=', 'sales.sale_added_by')
                        ->whereRaw($where)
                        ->orderby('sale_id','desc')
                        ->get();
    }
    public static function getDetails2($where)
    {
        $sale = new Sale;

        return $sale->select('*')
                        // ->join('customers', 'customers.customer_id','=', 'sales.sale_customer')
                        ->join('locations', 'locations.location_id','=', 'sales.sale_location')
                        ->join('users', 'users.user_id','=', 'sales.sale_added_by')
                        ->whereRaw($where)
                        ->orderby('sale_id','desc')
                        ->first();
    }

    public static function getDetails3($where)
    {
        $sale = new Sale;

        return $sale->select('*')
                        // ->join('customers', 'customers.customer_id','=', 'sales.sale_customer')
                        ->join('locations', 'locations.location_id','=', 'sales.sale_location')
                        ->join('users', 'users.user_id','=', 'sales.sale_added_by')
                        ->whereRaw($where)
                        ->with('commission_seller')
                        ->orderby('sale_id','desc')
                        ->get();
    }

    public function commission_seller()
    {
        return $this->hasMany(Commission::class,'sale_id');
    }



}