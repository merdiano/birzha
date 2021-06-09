<?php namespace TPS\Birzha\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Support\Facades\DB;
use TPS\Birzha\Models\Category;
use TPS\Birzha\Models\Offer;

class Offers extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'offer'
    ];

    public $stats;
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('TPS.Birzha', 'birzha-menu', 'offers');

        $this->stats = Offer::select('status',DB::raw('COUNT(id) as count'))
            ->groupBy('status')
            ->pluck('count','status');
//        dd($this->stats);
//        dd(Category::select('status',DB::raw('COUNT(id) as count'))->groupBy('status')->pluck('count','status')[1]);
    }

    public function getRecordsStats($status){
        if($this->stats)
            return $this->stats[$status] ?? 0;
        return 0;
    }
}
