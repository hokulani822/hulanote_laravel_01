<?php

namespace App\Helpers;

use Illuminate\Support\Facades\URL;

class SecureAssetHelper
{
    public static function secureUrl($path)
    {
        if (config('app.env') === 'production') {
            return URL::secure($path);
        }
        return url($path);
    }
}