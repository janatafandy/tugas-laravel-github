<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // INI BARIS PENTING! KASIH TAU PAKE TABEL 'employees'
    protected $table = 'employees';

    protected $fillable = [
        'name',
        'email',
        'password',
        'position',
        'department',
        'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
