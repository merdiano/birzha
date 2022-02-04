<?php

namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailVerificationController extends KabinetAPIController
{
    public function sendEmailVerificationLink()
    {
        if(!$this->user->email_verified) {
            $code = sha1(time());
            $vars = [
                'verification_link' => url('verify-email', ['code' => $code])
            ];

            try {
                \Mail::send('rainlab.user::mail.email_verification', $vars, function($message) {
                    $message->to($this->user->email, 'Birzha User');
                    $message->subject('Подтверждение Email');
                });
            } catch(Throwable $th) {
                \Log::info($th);
                
                return response()->json('Cannot verify. Invalid email address.', 400);
            }

            $this->user->email_activation_code = $code;
            $this->user->save();

            return response()->json('Verification link has been sent. Log in to tmex.gov.tm before checking your email.', 201);

        } else {
            return response()->json('You have already verified your email address.', 200);
        }
    }
}
