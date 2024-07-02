<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenant;

class LeaveType extends Model
{
    use HasFactory, Multitenant;

    protected $fillable = ['type','days'];
}
