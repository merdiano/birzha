<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Slider extends Model
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
    public $table = 'tps_birzha_sliders';
    protected $jsonable = ['slide_items'];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    // relati
}
