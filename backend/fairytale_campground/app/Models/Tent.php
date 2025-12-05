<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tent extends Model
{
    use HasFactory;

    protected $table = 'tent';
    protected $primaryKey = 'tent_id';
    public $timestamps = false; // karena tabel tidak punya created_at & updated_at

    protected $fillable = [
        'paket_id',
        'nomor_tent',
        'nomor_loker',
        'status'
    ];

    // Relasi ke Paket
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id', 'paket_id');
    }

    // Relasi ke DetailPemesanan
    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'tent_id', 'tent_id');
    }

    // Fungsi untuk cek ketersediaan tenda berdasarkan tanggal
    public function isAvailable($checkin, $checkout)
    {
        return !$this->detailPemesanan()
            ->whereHas('pemesanan', function($query) use ($checkin, $checkout) {
                $query->where('tanggal_checkin', '<=', $checkout)
                      ->where('tanggal_checkout', '>=', $checkin)
                      ->where('status_pemesanan', '!=', 'ditolak');
            })
            ->exists();
    }
}
