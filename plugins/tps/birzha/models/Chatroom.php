<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Chatroom extends Model
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
    public $table = 'tps_birzha_chatrooms';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsToMany = [
        'users' => ['RainLab\User\Models\User','table'=>'tps_birzha_chatrooms_users']
    ];

    public $hasMany = [
        'messages' => ['TPS\Birzha\Models\Message'],
    ];
}
