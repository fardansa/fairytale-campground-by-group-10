<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camp extends Model
{
    protected $table = 'camp';
    protected $primaryKey = 'camp_id';
    public $timestamps = false;

    protected $fillable = [
        'paket_id',
        'nomor_camp',
        'nomor_loker',
        'status'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'camp_id');
    }
}
