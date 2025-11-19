<?php

namespace App\Http\Controllers;

use App\Models\PemesananMaster;

class CheckController extends Controller
{
    public function myBookings($user_id)
    {
        return PemesananMaster::where('user_id', $user_id)->get();
    }
}
