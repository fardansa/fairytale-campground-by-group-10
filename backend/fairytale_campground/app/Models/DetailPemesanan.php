<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    protected $table = 'detail_pemesanan';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    protected $fillable = [
        'pemesanan_id',
        'tent_id',
        'harga_per_malam',
        'subtotal'
    ];

    /**
     * Relasi ke PemesananMaster
     * Satu DetailPemesanan milik satu PemesananMaster
     */
    public function pemesanan()
    {
        return $this->belongsTo(PemesananMaster::class, 'pemesanan_id', 'pemesanan_id');
    }

    /**
     * Relasi ke Tent
     * Satu DetailPemesanan memiliki satu Tent
     */
    public function tent()
    {
        return $this->belongsTo(Tent::class, 'tent_id', 'tent_id');
    }
}
