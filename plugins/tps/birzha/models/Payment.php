<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Payment extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_payments';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'amount' => 'required'
    ];

    public $hasMany = [
        'offers' => 'TPS\Birzha\Models\Offer'
    ];

    public $belongsTo = [
        'user' => 'RainLab\User\Models\User'
    ];

    public $attachOne = [
        'bank_file' => 'System\Models\File'
    ];
}
