<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $guarded = ['id'];

    // Tambahkan ini agar otomatis mengubah JSON dari database menjadi Array di PHP
    protected $casts = [
        'images_url' => 'array',
    ];
}