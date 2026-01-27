<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'product_image'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}
