<?php

namespace TPS\Birzha\Models;

use Model;
class Transaction extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\Sortable;

    public $morphTo = [
        'transactable' => []
    ];

    protected $fillable = ['amount'];

    public $belongsTo = [
        'user' => 'RainLab\User\Models\User'
    ];
}
