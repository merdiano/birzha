<?php namespace TPS\Birzha\Models;

use Model;
use RainLab\User\Models\User;
use Carbon\Carbon;
use TPS\Birzha\Models\Settings;

/**
 * Model
 */
class Product extends Model
{

    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at','created_at','ends_at'];

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_products';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'categories' => 'required|max:1', // not to allow admin to check more than 1 category for a product
        'status' => 'required',
        'name' => 'required',
        'slug' => 'required',
        'images' => 'required',
        'quantity' => 'required',
        'measure' => 'required',
        'price' => 'required|numeric',
        'currency' => 'required',
    ];

    public $customMessages = [
        'status_note.required' => 'Status Note is required when status is set to Denied',
    ];

    public $belongsToMany = [
        'categories' => ['TPS\Birzha\Models\Category','table' => 'tps_birzha_product_categories']
    ];

    public $belongsTo = [
        'country' => ['TPS\Birzha\Models\Country'],
        'measure'       => ['TPS\Birzha\Models\Measure','key' => 'measure_id'],
        'currency'      => ['TPS\Birzha\Models\Currency'],
        'payment_term'  => ['TPS\Birzha\Models\Term','key' => 'payment_term_id'],
        'delivery_term' => ['TPS\Birzha\Models\Term','key' => 'delivery_term_id'],
        'vendor'        => User::class,
        'payment'       => ['TPS\Birzha\Models\Payment'],
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $translatable = [
        ['name', 'index' => true],
        ['slug', 'index' => true],
        'description',
    ];

    public $fillable = ['name','slug','description'];

    public function beforeCreate()
    {
        if(!$this->status)
            $this->status = 'draft';
    }

    public function scopeApprovedAndFreshEndDate($query) {
        return $query->where('status', 'approved')->where('ends_at','>=',\DB::raw('curdate()'));
    }

    public function beforeValidate()
    {
        if(\App::runningInBackend()) {
            if ($this->status && $this->status =='denied') {
                $this->rules['status_note'] = 'required';
            }
        } else {
            $this->rules = [];
        }

    }

    public function beforeUpdate()
    {
        if($this->status == 'approved' && !$this->ends_at) {
//            $createdAt = Carbon::parse($this->created_at);
            $this->ends_at = $this->created_at->addDays(Settings::getValue('duration'));
        }
        if($this->status == 'denied') {
            // give fee back to the user, because his post has been denied
            $user = $this->vendor;
            $user->balance = $user->balance + $this->payed_fee_for_publ;
            $user->save();
        }
    }

    public static function getMenuTypeInfo($type){

    }

    public static function resolveMenuItem($item, $url, $theme){

    }
}
