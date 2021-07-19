<?php namespace TPS\Card\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use TPS\Card\Models\Application;
use \TPS\Card\Classes\Payment;
/**
 * Applications Back-end Controller
 */
class Applications extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    /**
     * @var string Configuration file for the `FormController` behavior.
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string Configuration file for the `ListController` behavior.
     */
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('TPS.Card', 'card', 'applications');
    }

    public function checkPayment(){

        $app_id = get('app_id');
        if(get(is_payment_successful) && $app_id){
            $responce = json_decode(Payment::getStatus($app_id),true);

            if( $responce['ErrorCode'] == 0
                && $responce['OrderStatus'] == 2){
                Application::where('id',$app_id)->update(['payed' => true]);
                //todo show successful page, send email
            }
            else{
                //
            }

        }
        else{
            //todo show payment cancelled view
        }

        //todo check payment status, modify applctn, show status page
    }

    private function sendNotification(Application $app){
        //todo send user email
    }
}
