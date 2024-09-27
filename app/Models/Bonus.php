<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $table = 'bonus';

    protected $primaryKey = 'bonus_id';

    public $timestamps = false;

    protected $fillable = ['bonus_id','bonus','seller_id','commission_id','commission_date','bonus_added_on','bonus_updated_on'];

}