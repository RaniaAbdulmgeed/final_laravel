<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes; // Enables factory support & soft deletes

    /**
     * Define the table name explicitly (not needed if it follows Laravel naming convention).
     */
    protected $table = 'categories';

    /**
     * Mass assignable attributes (columns).
     */
    protected $fillable = ['name', 'description', 'state'];


    /**
     * Attributes that should be mutated to dates (for soft deletes).
     */
    protected $dates = ['deleted_at'];

    /**
     * Relationship: A Category has many Products.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}

