<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_category';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $translatable = ['name','slug'];

    public $hasMany = [
        'products' => 'TPS\Birzha\Models\Product'
    ];

}
