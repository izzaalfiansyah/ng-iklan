<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    public $table = 'user';

    public $fillable = [
        'username',
        'password',
        'role',
        'nama',
        'telepon',
        'email',
        'foto',
    ];

    public $appends = [
        'role_detail',
        'foto_url',
    ];

    public function getFotoUrlAttribute()
    {
        return asset('/asset/user/foto/' . ($this->foto ? $this->foto : 'default.png'));
    }

    public function getRoleDetailAttribute()
    {
        if ($this->role == '1') {
            $role = 'Admin';
        } else if ($this->role == '2') {
            $role = 'User';
        } else if ($this->role == '3') {
            $role = 'Pimpinan';
        } else {
            $role = 'Tidak diketahui';
        }

        return $role;
    }

    const rules = [
        'role' => 'required|in:1,2,3',
        'nama' => 'required',
        'telepon' => 'required|numeric',
        'email' => 'nullable|email',
    ];

    const authRules = [
        'username' => 'required|min:5|max:50',
        'password' => 'nullable|min:8|max:20',
    ];
}
