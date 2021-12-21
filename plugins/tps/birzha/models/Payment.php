<?php namespace TPS\Birzha\Models;

use Carbon\Carbon;
use Model;
use October\Rain\Support\Facades\Event;

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
        'transaction' => [Transaction::class, 'name' => 'transactable','delete'=>true]
    ];

    public $belongsTo = [
        'user' => 'RainLab\User\Models\User'
    ];

    public $attachOne = [
        'bank_file' => 'System\Models\File'
    ];

    public function beforeUpdate() {
        if($this->status == 'approved' || $this->payment_type == 'gift' ) {
            if(!$transaction = $this->transaction)
            {
                $this->createTransaction($this->payment_type != 'gift'? $this->patment_type:'sowgat');
            }
            else{
                $transaction->amount = $this->amount;
                $this->payment_type == 'gift' ? $desc = 'sowgat' : $desc = '';
                $transaction->description = "Balansyn doldurulmagy {$desc} {$this->amount} manat";
                $transaction->save();
            }
        }

    }
    public function afterUpdate()
    {
        if($this->status == 'approved' && $this->payment_type == 'bank' ){
            Event::fire('tps.payment.reviewed',[$this,$this->user]);
        }
    }

    public function beforeValidate()
    {
        if(\App::runningInBackend()) {
            $this->rules['amount'] = 'required|gte:0';
        } else {
            $this->rules['amount'] = 'required';
        }

    }

    protected function beforeCreate()
    {
        parent::beforeCreate();
        if(\App::runningInBackend()) {
            $this->payment_type = 'gift';
            $this->created_at = Carbon::now();
            $this->updated_at = Carbon::now();

        }
        else{
            $this->status = 'new';
        }
    }

    protected function afterCreate()
    {
        parent::afterCreate();

        if($this->payment_type == 'gift'){
            $this->createTransaction();
            Event::fire('tps.payment.reviewed',[$this,$this->user]);
        }
    }

    private function createTransaction($desc='sowgat'){
        $transaction = new Transaction([
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'description' => "Balansyn doldurulmagy {$desc} {$this->amount} manat"
        ]);
        $this->transaction()->save($transaction);
    }
    public function filterFields($fields, $context = null){
        if($this->payment_type == 'online'){
            $fields->amount->disabled = true;
            $fields->user->disabled = true;
            $fields->created_at->disabled = true;
            $fields->status->disabled = true;

        }

        if ($this->payment_type == 'gift') {
            $fields->status->hidden = true;
            $fields->bank_file->hidden = true;
        }

        if($this->payment_type == 'bank' && $this->status == 'approved') {
            $fields->status->disabled = true;
        }

    }
}
