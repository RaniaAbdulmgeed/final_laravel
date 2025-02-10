<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define the table name explicitly (optional if following Laravel conventions).
     */
    protected $table = 'orders';

    /**
     * Mass assignable attributes (columns).
     */
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'payment_status',
        'state'
    ];


    /**
     * Enable soft deletes (`deleted_at`).
     */
    protected $dates = ['deleted_at'];

    /**
     * Relationship: An Order belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: An Order has many Products (through OrderItems).
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price_at_purchase')
                    ->withTimestamps();
    }

    /**
     * Relationship: An Order has one Payment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
}
