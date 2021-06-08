<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Currency extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];
    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_currency';

    /**
     * @var array Validation rules
     */
    public $rules = [

    ];

    public $translatable = ['name'];

    public $hasMany = [
        'offers' => 'TPS\Birzha\Models\Offer'
    ];
}
