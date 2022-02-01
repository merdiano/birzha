<?php

namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use LaravelSmpp\SmppServiceInterface;
use SmppClient;
use SmppException;
use SocketTransport;
use TPS\Birzha\Classes\SMPP;

class SmsController extends Controller
{
    public function index(SmppServiceInterface $smpp) {
//        $smpp->sendOne(99363432211, 'Hi, this SMS was send via SMPP protocol');
//        $tx=new SMPP('217.174.228.218', 5019); // make sure the port is integer
//        $tx->debug=true;
//        $tx->bindTransmitter("birja","Birj@1");
//        dump('bind transmitter');
//        $result = $tx->sendSMS("0773",request('phone'),request('message'));
//        dump('send sms attempt');
//        echo $tx->getStatusMessage($result);
//        $tx->close();
//        unset($tx);
//        return 'success';

        return $this->setupSMPP();

    }

    private function setupSMPP(){
        $transport = new SocketTransport(['217.174.228.218'], 5019);

        try {
            $transport->setRecvTimeout(10000);
            $smpp = new SmppClient($transport);
            $smpp->debug = true;

            $transport->open();
            $smpp->bindTransmitter('birja', 'Birj@1');
            return 'ok';

        }
            // Skipping unavailable
        catch (SmppException $ex) {
            $transport->close();


            throw $ex;
        }
    }
}
