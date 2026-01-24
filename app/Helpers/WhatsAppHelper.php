<?php

namespace App\Helpers;

class WhatsAppHelper
{
    public static function send(string $phone, string $message)
    {
        $phone = preg_replace('/^0/', '62', $phone);
        $message = urlencode($message);

        return redirect("https://wa.me/{$phone}?text={$message}");
    }
}
