<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'pembayaran_id';
    public $timestamps = false;

    protected $fillable = [
        'pemesanan_id',
        'total_pembayaran',
        'status_pembayaran',
        'tanggal_pembayaran',
        'bukti_tf'
    ];

    public function pemesanan()
    {
        return $this->belongsTo(PemesananMaster::class, 'pemesanan_id');
    }
}
