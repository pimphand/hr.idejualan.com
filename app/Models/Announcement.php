<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenant;

class Announcement extends Model
{
    use HasFactory, Multitenant;

    protected $table = 'announcement';
    protected $fillable = [
        'title',
        'description',
        'image'
    ];
}
