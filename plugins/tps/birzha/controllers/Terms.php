<?php namespace TPS\Birzha\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Terms extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'term'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('TPS.Birzha', 'dictionary', 'terms');
    }
}
