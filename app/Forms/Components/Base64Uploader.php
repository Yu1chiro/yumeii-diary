<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class Base64Uploader extends Field
{
    // Mengarahkan ke file view blade UI uploader
    protected string $view = 'filament.components.base64-uploader';
}