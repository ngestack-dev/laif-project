<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'activity'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
