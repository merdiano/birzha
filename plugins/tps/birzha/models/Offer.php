<?php namespace TPS\Birzha\Models;

use Carbon\Carbon;
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
        'price' => 'required|numeric',
        'quantity' => 'required|numeric',
        'currency' => 'required',
        'name' => 'required',
        'mark' => 'required'
    ];

    public $customMessages = [
        'status_note.required' => 'Status Note is required when status is set to Denied',
    ];

    public $belongsTo = [
        'product'       => ['TPS\Birzha\Models\Product'],
        'payment'       => ['TPS\Birzha\Models\Payment'],
        'currency'      => ['TPS\Birzha\Models\Currency'],
        'measure'       => ['TPS\Birzha\Models\Measure','key' => 'measure_id'],
        'payment_term'  => ['TPS\Birzha\Models\Term','key' => 'payment_term_id'],
        'delivery_term' => ['TPS\Birzha\Models\Term','key' => 'delivery_term_id'],
        'vendor'        => User::class
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $translatable = ['name','description','mark','place'];
    /**
     * Allows filtering for specifc categories.
     * @param  Illuminate\Query\Builder  $query      QueryBuilder
     * @param  array                     $users List of user ids
     * @return Illuminate\Query\Builder              QueryBuilder
     */
    public function scopeFilterUsers($query, $users)
    {
        return $query->whereHas('categories', function($q) use ($users) {
            $q->whereIn('id', $users);
        });
    }

    public function beforeCreate()
    {
        if(!$this->status)
            $this->status = 'draft';
    }

    public function beforeSave()
    {
       if(!$this->ends_at){
           if($this->status = 'approved'){
               $this->ends_at = Carbon::now()->addDays(30);
           }
       }
    }

    public function beforeValidate()
    {
        if ($this->status && $this->status =='denied') {
            $this->rules['status_note'] = 'required';
        }
    }

}
