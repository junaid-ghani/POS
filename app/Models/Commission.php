<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $table = 'commission';

    protected $primaryKey = 'commission_id';

    public $timestamps = false;    
    protected $fillable = ['sale_id ','seller_id','commission','amount','sale_added_on','sale_updated_on'];
    
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'user_id');
    }

    public function sale_seller()
    {
        return $this->belongsTo(Sale::class,'sale_id');
    }

}