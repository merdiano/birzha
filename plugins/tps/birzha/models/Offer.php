<?php namespace TPS\Birzha\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class Offer extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];
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
        'product' => 'TPS\Birzha\Models\Product',
        'payment' => 'TPS\Birzha\Models\Payment',
        'currency' => 'TPS\Birzha\Models\Currency',
        'measure' => 'TPS\Birzha\Models\Measure',
        'payment_term' => ['TPS\Birzha\Models\Term','key' => 'payment_term_id'],
        'delivery_term' => ['TPS\Birzha\Models\Term','key' => 'delivery_term_id'],
        'vendor' => User::class
    ];

    public $attachMany = [
        'images' => 'TPS\Birzha\Models\Product'
    ];

    public $translatable = ['name','description','mark','place'];
    /**
     * Allows filtering for specifc categories.
     * @param  Illuminate\Query\Builder  $query      QueryBuilder
     * @param  array                     $categories List of category ids
     * @return Illuminate\Query\Builder              QueryBuilder
     */
    public function scopeFilterUsers($query, $categories)
    {
        return $query->whereHas('categories', function($q) use ($categories) {
            $q->whereIn('id', $categories);
        });
    }
}
