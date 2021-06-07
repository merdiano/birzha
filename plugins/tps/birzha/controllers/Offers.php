<?php namespace TPS\Birzha\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Offers extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'offer' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('TPS.Birzha', 'birzha-menu', 'offers');
    }
}
