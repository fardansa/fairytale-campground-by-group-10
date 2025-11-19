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
        'camp_id',
        'harga_per_malam',
        'subtotal'
    ];

    public function pemesanan()
    {
        return $this->belongsTo(PemesananMaster::class, 'pemesanan_id');
    }

    public function camp()
    {
        return $this->belongsTo(Camp::class, 'camp_id');
    }
}
