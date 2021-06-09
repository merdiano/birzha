<?php namespace TPS\Birzha\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Support\Facades\DB;
use TPS\Birzha\Models\Payment;

class Payments extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $stats, $payment_stats, $amount_stats;
    public $requiredPermissions = [
        'payment'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('TPS.Birzha', 'birzha-menu', 'payments');

        $this->stats = Payment::select('status',DB::raw('COUNT(id) as count, SUM(amount) as total_amount'))
            ->groupBy('status')
            ->get();

        $this->payment_stats = Payment::select('payment_type',DB::raw('COUNT(id) as count, SUM(amount) as total_amount'))
            ->groupBy('payment_type')
            ->get();

    }

    public function getRecordsStats($status){
        return $this->stats->where('status',$status)->first()->count ?? 0;
    }

    public function getPaymentStats($type)
    {
        return $this->stats->where('payment_type',$type)->first()->count ?? 0;
    }

    //todo amount funksia yazmaly
}
