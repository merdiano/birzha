<?php namespace TPS\Birzha\Models;

use Model;

/**
 * Model
 */
class Payment extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_payments';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'amount' => 'required'
    ];

    public $morphOne = [
        'transaction' => [Transaction::class, 'name' => 'transactable']
    ];

    public $belongsTo = [
        'user' => 'RainLab\User\Models\User'
    ];

    public $attachOne = [
        'bank_file' => 'System\Models\File'
    ];

    public function beforeUpdate() {
        if($this->status == 'payed') {
            $user = $this->user;
            $user->balance +=$this->amount;
            $user->save();
        }
    }

    public function beforeValidate()
    {
        if(\App::runningInBackend()) {
            $this->rules['amount'] = 'required|gt:0';
        } else {
            $this->rules['amount'] = 'required';
        }

    }
}
