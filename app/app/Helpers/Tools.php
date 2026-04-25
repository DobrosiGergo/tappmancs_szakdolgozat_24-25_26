<?php

namespace App\Helpers;

class Tools
{
    public static function flash(string $message, string $type = 'success'): void
    {
        session()->flash('flash', [
            'message' => $message,
            'type'    => $type,
        ]);
    }
}
