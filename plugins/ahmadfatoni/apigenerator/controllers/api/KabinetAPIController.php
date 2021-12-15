<?php

namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;

class KabinetAPIController extends Controller
{
    /**
     * Get user's transactions
     */

    protected $user;

    public function __construct()
    {
        parent::__construct();

        if (!$this->user = \JWTAuth::parseToken()->authenticate()) {
            return response()->json('Unauthorized', 401);
        }
    }
}
