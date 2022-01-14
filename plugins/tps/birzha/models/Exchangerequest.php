<?php namespace TPS\Birzha\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class Exchangerequest extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_exchange_requests';

    public $fillable = ['payed_for_request', 'content', 'status'];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * Relations
     */
    public $belongsTo = [
        'user' => User::class,
    ];

    public $morphOne = [
        'transaction' => [Transaction::class, 'name' => 'transactable']
    ];

    private function createTransaction(){
        $transaction = new Transaction([
            'user_id' => $this->user_id,
            'amount' => 0 - $this->payed_for_request,
            'description' => "Kotirowkalar (import bahalar) #{$this->id} ucin arzaň tutumy."
        ]);
        $this->transaction()->save($transaction);
    }

    public function beforeCreate()
    {
        $this->createTransaction();
        $this->status = 'success';
    }
}
