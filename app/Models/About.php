<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'story',
        'vision',
        'mission',
        'about_laif',
        'email_laif',
        'phone_laif',
        'instagram',
        'image'
    ];
}
