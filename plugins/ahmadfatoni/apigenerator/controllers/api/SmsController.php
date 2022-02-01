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
        $tx->debug=true;
        $tx->bindTransmitter("birja","Birj@1");
        dump('bind transmitter');
        $result = $tx->sendSMS("0773",'99363432211','message');
        dump('send sms attempt');
        echo $tx->getStatusMessage($result);
        $tx->close();
        unset($tx);
        return 'success';

//        return $this->setupSMPP();

    }

    private function setupSMPP(){
        $transport = new SocketTransport(['217.174.228.218'], 5019);

        try {
            $transport->setRecvTimeout(10000);
            SmppClient::$sms_null_terminate_octetstrings = false;
            $smpp = new SmppClient($transport);
            $smpp->debug = true;
            $transport->debug = true;

            $transport->open();
            $smpp->bindTransmitter('birja', 'Birj@1');
            $message = 'H€llo world';
            $encodedMessage = GsmEncoder::utf8_to_gsm0338($message);
            $from = new SmppAddress('0773');
            $to = new SmppAddress('99363432211');

// Send
            $response = $smpp->sendSMS($from,$to,$message,null,SMPP::DATA_CODING_ISO8859_1);

// Close connection
            $smpp->close();
            dd($response);

        }
            // Skipping unavailable
        catch (SmppException $ex) {
            $transport->close();
            throw $ex;
        }
    }


}
