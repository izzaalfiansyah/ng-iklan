<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IklanRequest extends Model
{
    use HasFactory;

    public $table = 'iklan_request';

    public $fillable = [
        'iklan_id',
        'link_video',
        'status',
        'bukti_pembayaran',
        'user_id',
        'jumlah_tayang',
    ];

    public $appends = [
        'status_detail',
        'bukti_pembayaran_url',
        'tarif_total',
    ];

    public function getBuktiPembayaranUrlAttribute()
    {
        return $this->bukti_pembayaran ? asset('/asset/iklan/bukti-pembayaran/' . $this->bukti_pembayaran) : '';
    }

    public function getStatusDetailAttribute()
    {
        if ($this->status == '1') {
            $status = 'disetujui';
        } else if ($this->status == '2') {
            $status = 'ditolak';
        } else {
            $status = 'menunggu';
        }

        return $status;
    }

    public function getTarifTotalAttribute()
    {
        return 'Rp.' . ($this->iklan ? number_format($this->iklan->tarif * $this->jumlah_tayang, 0, ',', '.') : 0);
    }

    public $with = [
        'iklan',
        'user',
    ];

    public function iklan()
    {
        return $this->belongsTo(Iklan::class, 'iklan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    const rules = [
        'iklan_id' => 'required|integer',
        'link_video' => 'required',
        'user_id' => 'required|integer',
        'jumlah_tayang' => 'required|integer',
        'status' => 'nullable|in:1,2,0',
        // 'bukti_pembayaran' => 'required',
    ];
}
