<?php

namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use LaravelSmpp\SmppServiceInterface;

class SmsController extends Controller
{
    public function index(SmppServiceInterface $smpp) {
        $this->smpp->sendOne(99363432211, 'Hi, this SMS was send via SMPP protocol');
        return 'success';
    }
}
