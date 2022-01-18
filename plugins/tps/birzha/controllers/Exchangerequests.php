<?php namespace TPS\Birzha\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Exchangerequests extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController'    ];
    
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('TPS.Birzha', 'birzha-menu', 'exchange-requests');
    }
}
