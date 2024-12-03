<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function offlineOrder()
    {
        return $this->belongsTo(OfflineOrder::class, 'offline_order_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Validasi: Salah satu harus null
            if (is_null($model->order_id) === is_null($model->offline_order_id)) {
                throw new \Exception('Either order_id or offline_order_id must be set, but not both.');
            }
        });
    }
}
