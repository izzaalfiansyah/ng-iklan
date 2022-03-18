<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iklan extends Model
{
    use HasFactory;

    public $table = 'iklan';

    public $fillable = [
        'jenis',
        'sub_jenis',
        'kategori_id',
        'durasi',
        'tarif',
        'keterangan',
    ];

    public $with = [
        'kategori',
    ];

    public function kategori()
    {
        return $this->belongsTo(IklanKategori::class, 'kategori_id');
    }

    public $appends = [
        'durasi_detail',
        'tarif_detail',
    ];

    public function getDurasiDetailAttribute()
    {
        return $this->durasi . ' detik';
    }

    public function getTarifDetailAttribute()
    {
        return 'Rp.' . number_format($this->tarif, 0, ',', '.');
    }

    const rules = [
        'jenis' => 'required',
        'sub_jenis' => 'nullable',
        'kategori_id' => 'required|integer',
        'durasi' => 'required|integer',
        'tarif' => 'required|integer',
        'keterangan' => 'nullable',
    ];
}
