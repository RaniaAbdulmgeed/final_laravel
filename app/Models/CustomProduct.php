<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'product_type', 'size', 'color', 'image_url', 'notes'
    ];
}

