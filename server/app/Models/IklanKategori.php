<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IklanKategori extends Model
{
    use HasFactory;

    public $table = 'iklan_kategori';
    
    public $fillable = [
        'nama',
    ];

    const rules = [
        'nama' => 'required',
    ];
}
