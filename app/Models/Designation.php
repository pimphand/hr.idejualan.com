<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Multitenant;

class Designation extends Model
{
    use HasFactory, Multitenant;

    protected $fillable=[
        'name',
        'department_id',
    ];

    public function department(){
        return $this->belongsTo(Department::class);
    }
}
