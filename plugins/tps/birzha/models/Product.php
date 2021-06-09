<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Product extends Model
{

    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_product';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsToMany = [
        'categories' => ['TPS\Birzha\Models\Category','table' => 'tps_birzha_product_categories']
    ];

    public $hasMany = [
        'offers' => 'TPS\Birzha\Models\Offer'
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $translatable = ['name',['slug', 'index' => true],'mark'];

}
