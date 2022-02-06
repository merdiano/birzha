<?php

namespace TPS\Birzha\Classes;

use Exception;
use Illuminate\Support\Facades\Log;
use October\Rain\Support\Facades\Http;
use TPS\Birzha\Classes\SMPP;

class SMS
{
    public static function send($to, $content){

        try {
            $tx = new SMPP(env('SMPP_IP','217.174.228.218'), env('SMPP_PORT',5019)); // make sure the port is integer

            $tx->bindTransmitter(env('SMPP_USER',"birja"),env('SMPP_PASS',"Birj@1"));

            $result = $tx->sendSMS(env('SMPP_SENDER',"0773"),$to,$content);

            $tx->close();
            unset($tx);
            return $result;
        }
        catch (\Throwable $th){
            Log::info($th);
            return 1;
        }

    }
}
