<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananMaster extends Model
{
    protected $table = 'pemesanan_master';
    protected $primaryKey = 'pemesanan_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'tanggal_checkin',
        'tanggal_checkout',
        'total_harga',
        'status_pemesanan',
        'created_at',
        'expired_at'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke detail pemesanan (hasMany)
    public function details()
    {
        return $this->hasMany(DetailPemesanan::class, 'pemesanan_id', 'pemesanan_id');
    }

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pemesanan_id', 'pemesanan_id');
    }
}
