<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Country extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_countries';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $translatable = ['name'];

    public $hasMany = [
        'products' => ['TPS\Birzha\Models\Product'],
        'offers' => ['TPS\Birzha\Models\Offer'],
    ];
}
