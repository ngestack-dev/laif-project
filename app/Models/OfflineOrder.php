<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineOrder extends Model
{
    use HasFactory;

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'offline_order_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
