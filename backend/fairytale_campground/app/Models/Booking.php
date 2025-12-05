<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tent;

class Booking extends Model
{
    use HasFactory;
protected $fillable = [
    'user_id',
    'tenda_id',
    'status',
    'bukti_pembayaran',
    'expired_at',
];

public function Tent()
{
    return $this->belongsTo(Tent::class);
}

}