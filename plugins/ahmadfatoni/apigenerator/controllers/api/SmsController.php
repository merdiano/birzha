<?php

namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use GsmEncoder;
use Illuminate\Support\Facades\Log;
use LaravelSmpp\SmppServiceInterface;
use SMPP;
use SmppAddress;
use SmppClient;
use SmppException;
use SocketTransport;
use TPS\Birzha\Classes\SMPP as SMPPV2;

class SmsController extends Controller
{
    public function index(SmppServiceInterface $smpp) {
//        $smpp->sendOne(99363432211, 'Hi, this SMS was send via SMPP protocol');
        $tx=new SMPPV2('217.174.228.218', 5019); // make sure the port is integer
//        $tx->debug=true;
        $tx->bindTransmitter("birja","Birj@1");
//        dump('bind transmitter');
        $result = $tx->sendSMS("0773",'99363432211','message');
//        dump('send sms attempt');
//        echo $tx->getStatusMessage($result);
        $tx->close();
        unset($tx);
        return $result;

    }

}
