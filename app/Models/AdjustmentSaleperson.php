<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdjustmentSaleperson extends Model
{
    protected $table = 'adjustment_salepersons';

    protected $primaryKey = 'adjustment_saleperson_id';

    public $timestamps = false;

    protected $fillable = ['adjustment_saleperson_id','adjustment_id'];
        
    public function adjustment()
    {
        return $this->belongsTo(Adjustment::class,'adjustment_id');
    }
}