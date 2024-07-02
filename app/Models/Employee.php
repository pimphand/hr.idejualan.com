<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Multitenant;

class Employee extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes, Multitenant;

    protected $guard = 'employee';
    protected $fillable = [
        'firstname','lastname','uuid',
        'email','phone',
        'department_id','designation_id','company','avatar','password','device_identifier','birthday', 'leaves_quota','role',
        'pin','is_pin_active', 'is_flagged', 'beliefs', 'marital_status', 'address', 'work_status', 'gender'
    ];
    protected $hidden = ['password', 'pin'];

    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)->where('is_flagged', '=', 0);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function designation(){
        return $this->belongsTo(Designation::class);
    }

    public function getAvatarAttribute($value){
        if($value){
            return url('storage/employees/'.$value);
        }else{
            return url('storage/employees/default.png');
        }
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function appeal(){
        return $this->hasMany(Appeal::class,'employee_id');
    }
}
