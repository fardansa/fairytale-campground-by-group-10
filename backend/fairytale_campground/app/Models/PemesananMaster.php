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

    /**
     * Relasi ke DetailPemesanan
     * Satu PemesananMaster bisa punya banyak DetailPemesanan
     */
    public function detailTenda()
    {
        return $this->hasMany(DetailPemesanan::class, 'pemesanan_id', 'pemesanan_id');
    }
}
