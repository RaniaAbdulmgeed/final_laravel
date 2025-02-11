<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Explicit table name (optional if following Laravel naming conventions).
     */
    protected $table = 'products';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'discount',
        'stock_quantity',
        'size_options',
        'color_options',
        'image_url',
        'state'
    ];

    /**
     * Cast JSON columns to arrays.
     */
    protected $casts = [
        'size_options' => 'array',
        'color_options' => 'array',
        'image_url' => 'string' // ✅ Ensure image_url is treated as a string
    ];

    /**
     * Relationship: A Product belongs to a Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
