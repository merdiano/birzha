<?php

namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use LaravelSmpp\SmppServiceInterface;
use TPS\Birzha\Classes\SMPP as SMPPV2;
use TPS\Birzha\Classes\SMS;

class SmsController extends KabinetAPIController
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

    public function sendSmsCode()
    {
        if($this->user->dial_code != '+993') {
                return response()->json([
                        'dial_code' => $this->user->dial_code,
                        'message' => 'This user is not a resident of Turkmenistan.'
                ], 400);
        }

        if($this->user->phone_verified && $this->user->dial_code == '+993') {
                return response()->json('User phone already verified', 200);
        }
        
        $code = random_int(1000, 9999);

        $result = SMS::send(str_replace(array('+', ' ', '(' , ')', '-'), '', $this->user->username), $code);
        // $result = 0;

        switch ($result) {
                case 0:
                        $this->user->phone_activation_code = $code;
                        $this->user->save();
                        return response()->json([
                                'result' => $result,
                                'message' => 'Message has been succesfully sent'
                        ], 201);
                        break;

                case 1:
                        return response()->json([
                                'result' => $result,
                                'message' => 'Error'
                        ], 500);
                        break;
                
                default:
                        return response()->json([
                                'result' => $result,
                                'message' => 'Error'
                        ], 500);
                        break;
        }
    }

    public function checkSmsCode(Request $request)
    {
        if($this->user->dial_code != '+993') {
                return response()->json([
                        'dial_code' => $this->user->dial_code,
                        'message' => 'This user is not a resident of Turkmenistan.'
                ], 400);
        }

        if($this->user->phone_verified && $this->user->dial_code == '+993') {
                return response()->json('User phone already verified', 200);
        }
        
        $validator = Validator::make($request->all(), [
                'sms_code' => 'required|digits:4',
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if($this->user->phone_activation_code == $request->get('sms_code')) {
                $this->user->phone_verified = true;
                $this->user->phone_activation_code = null;
                $this->user->save();
                return response()->json('User phone has been succesfully verified', 201);
        } else {
                return response()->json('Wrong sms code', 400);
        }
    }
}
