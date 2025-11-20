<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tent extends Model
{
    protected $table = 'tent';
    protected $primaryKey = 'tent_id';
    public $timestamps = false;

    protected $fillable = [
        'paket_id',
        'nomor_tent',
        'nomor_loker',
        'status'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'tent_id');
    }
}
