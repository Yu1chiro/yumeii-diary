<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerProfile extends Model
{
    protected $guarded = ['id'];

    // Ini penting agar tags berubah menjadi Array di PHP dari JSON di database
    protected $casts = [
        'favorite_music' => 'array',
        'traits' => 'array',
    ];
}