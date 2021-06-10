<?php namespace TPS\Birzha\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Support\Facades\DB;
use TPS\Birzha\Models\Product;

class Products extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController'];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $stats;
    public $requiredPermissions = [
        'product'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('TPS.Birzha', 'birzha-menu', 'products');

        $this->stats = Product::select('status',DB::raw('COUNT(id) as count'))
            ->groupBy('status')
            ->pluck('count','status');
    }

    public function getRecordsStats($status){
        if($this->stats)
            return $this->stats[$status] ?? 0;
        return 0;
    }
}
