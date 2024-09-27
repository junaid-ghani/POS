<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    protected $primaryKey = 'category_id';

    public $timestamps = false;

    protected $fillable = ['category_name','category_image','category_added_on','category_added_by','category_updated_on','category_updated_by','category_status'];
}