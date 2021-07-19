<?php namespace TPS\Card\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Exception\AjaxException;
use October\Rain\Exception\ValidationException;
use \TPS\Card\Models\Application as CardApp;
use \TPS\Card\Classes\Payment;
class Application extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Card application',
            'description' => 'Plastic card application component'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onSend()
    {
        // Get request data
        $data = \Input::only([
            'firstname',
            'lastname',
            'email',
            'address'
        ]);

        // Validate request
        $this->validate($data);

        //todo save file

        $application = CardApp::create($data);

        $response = Payment::registerOrder($application->id);

        $result = json_decode($response->body,true);

        if($result['errorCode'] == 0){
            $application->orderId = $result['orderId'];

            $application->save();

            $this->sendNotification($application);

            return Redirect::to($result['formUrl']);
        }
        else{
            throw new AjaxException(
                $result
            );
        }

    }



    private function sendNotification(CardApp $app){
        //todo send email to admin
//        $receiver = 'admin@gmail.com';
//
//        \Mail::send('progmatiq.contact::contact', $data, function ($message) use ($receiver) {
//            $message->to($receiver);
//        });
    }

    protected function validate(array $data)
    {
        // Validate request
        $rules = [
            'firstname' => 'required|min:3|max:255',
            'lastname' => 'required|min:3|max:255',
            'email' => 'required|email',
            'address' => 'required',
        ];

        $validator = \Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
