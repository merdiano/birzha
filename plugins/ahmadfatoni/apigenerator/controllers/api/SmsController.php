<?php

namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use LaravelSmpp\SmppServiceInterface;
use TPS\Birzha\Classes\SMPP;

class SmsController extends Controller
{
    public function index(SmppServiceInterface $smpp) {
//        $smpp->sendOne(99363432211, 'Hi, this SMS was send via SMPP protocol');
        $tx=new SMPP('217.174.228.218', 5019); // make sure the port is integer
        $tx->debug=true;
        $tx->bindTransmitter("birja","Birj@1");
        dump('bind transmitter');
        $result = $tx->sendSMS("0773","99363432211","h");
        dump('send sms attempt');
        echo $tx->getStatusMessage($result);
        $tx->close();
        unset($tx);
        return 'success';
    }
}
