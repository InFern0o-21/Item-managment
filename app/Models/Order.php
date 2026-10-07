<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_number', 'customer_id', 'total_amount', 'status', 'notes', 'created_by'];

    // The customer who owns the order
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // The staff member who generated the order
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // The line items belonging to this order
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}