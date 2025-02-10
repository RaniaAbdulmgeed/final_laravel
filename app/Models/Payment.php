<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define the table name explicitly (optional if following Laravel conventions).
     */
    protected $table = 'payments';

    /**
     * Mass assignable attributes (columns).
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'payment_method',
        'amount',
        'status',
        'state'
    ];

    /**
     * Enable soft deletes (`deleted_at`).
     */
    protected $dates = ['deleted_at'];

    /**
     * Relationship: A Payment belongs to an Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Relationship: A Payment belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
