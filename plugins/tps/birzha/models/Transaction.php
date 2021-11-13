<?php

namespace TPS\Birzha\Models;

use Model;
class Transaction extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $table = 'tps_birzha_transactions';

    protected $dates = ['created_at','ends_at'];

    public $morphTo = [
        'transactable' => []
    ];

    protected $fillable = ['amount','user_id'];

    public $belongsTo = [
        'user' => 'RainLab\User\Models\User'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'amount' => 'required|numeric'
    ];
}
