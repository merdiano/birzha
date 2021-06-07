<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Offer extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_offer';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'product' => 'TPS\Birzha\Models\Product'
    ];

    public $attachMany = [
        'images' => 'TPS\Birzha\Models\Product'
    ];

    public $hasOne = [
        'payment' => 'TPS\Birzha\Models\Term',
        'delivery' => 'TPS\Birzha\Models\Term'
    ];
}
