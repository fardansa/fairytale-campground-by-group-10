<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'paket';
    protected $primaryKey = 'paket_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'fasilitas',
        'kapasitas',
        'harga'
    ];

    public function camp()
    {
        return $this->hasMany(Tent::class, 'paket_id');
    }
}
