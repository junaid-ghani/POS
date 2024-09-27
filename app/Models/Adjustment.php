<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    protected $table = 'adjustments';

    protected $primaryKey = 'adjustment_id';

    public $timestamps = false;

    protected $fillable = ['adjustment_id','type','date','description','adjustment_added_on','adjustment_updated_on'];

    public function adjustment_saleperson()
    {
        return $this->hasMany(AdjustmentSaleperson::class,'adjustment_id','adjustment_id');
    }
}