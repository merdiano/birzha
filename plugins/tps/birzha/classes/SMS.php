<?php

namespace TPS\Birzha\Classes;

use Exception;
use Illuminate\Support\Facades\Log;
use October\Rain\Support\Facades\Http;

class SMS
{
    public static function send($to, $content){
        $url = env('SMS_API')."+99363432211/{$content}";
        try {
            $response = Http::get($url);
            Log::info($response);
        }
        catch (Exception $exception){
            Log::error($exception);
        }
    }
}
