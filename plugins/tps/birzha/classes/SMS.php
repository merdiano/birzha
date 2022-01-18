<?php

namespace TPS\Birzha\Classes;

use Exception;
use Illuminate\Support\Facades\Log;
use October\Rain\Support\Facades\Http;

class SMS
{
    public static function send($to, $content){
        $url = urlencode(env('SMS_API')."$to/{$content}");
        try {
            $response = Http::get($url);
        }
        catch (Exception $exception){
            Log::error($exception);
        }
    }
}
