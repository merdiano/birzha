<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Contactmessage extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'surname', 'email', 'mobile', 'content'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_contactform_messages';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
