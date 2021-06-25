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
    public $table = 'tps_birzha_products';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'categories' => 'required',
        'status' => 'required',
        'name' => 'required',
        'slug' => 'required'
    ];

    public $customMessages = [
        'status_note.required' => 'Status Note is required when status is set to Denied',
    ];

    public $belongsToMany = [
        'categories' => ['TPS\Birzha\Models\Category','table' => 'tps_birzha_product_categories']
    ];

    public $belongsTo = [
        'country' => ['TPS\Birzha\Models\Country']
    ];

    public $hasMany = [
        'offers' => 'TPS\Birzha\Models\Offer'
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $translatable = [
        ['name', 'index' => true],
        ['slug', 'index' => true],
    ];

    public function beforeCreate()
    {
        if(!$this->status)
            $this->status = 'draft';
    }

    public function beforeValidate()
    {
        if ($this->status && $this->status =='denied') {
            $this->rules['status_note'] = 'required';
        }
    }

    public static function getMenuTypeInfo($type){

    }

    public static function resolveMenuItem($item, $url, $theme){

    }
}
