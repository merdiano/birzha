<?php namespace TPS\Birzha\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use TPS\Birzha\Models\Payment;

class Payments extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $stats;
    public $requiredPermissions = [
        'payment'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('TPS.Birzha', 'birzha-menu', 'payments');

        $this->stats = Payment::groupBy('status','payment_type')
            ->selectRaw('status, payment_type, COUNT(id) as count, SUM(amount) as total_amount')
            ->get();
    }

    public function getRecordsStats($status){
        return $this->stats->where('status',$status)
                ->sum('count')
            ?? 0;
    }

    public function getPaymentStats($type)
    {
        return $this->stats->where('payment_type',$type)
                ->sum('count')
            ?? 0;
    }

    public function getAmountStats($type){
        return $this->stats->where('payment_type',$type)
                ->where('status','approved')
                ->sum('total_amount')
            ?? 0;
    }

    public function onApprove(){
        Log::info('test approve');
    }

    public function onDecline(){
        Log::info('test approve');
    }
    //todo amount funksia yazmaly
}
