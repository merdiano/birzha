<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Term extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];
    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_terms';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required'
    ];

    public $translatable = ['name'];

    public function scopePayment($query){
        return $query->where('type','payment');
    }

    public function scopeDelivery($query){
        return $query->where('type','delivery');
    }
}
