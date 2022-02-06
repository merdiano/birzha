<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;

use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use TPS\Birzha\Models\Contactmessage;
use TPS\Birzha\Models\Settings;

class ContactFormApiController extends Controller
{
    protected $helpers;

    public function __construct(Helpers $helpers)
    {
        parent::__construct();
        $this->helpers = $helpers;
    }

    public function sendContactForm(Request $request) {
        $rules = [
            'name' => 'required|max:100',
            'surname' => 'required|max:100',
            'mobile' => 'required|min:6',
            'email' => 'required|email|max:100',
            'content' => 'required'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validator->errors() );
        }

        $contactMessage = new Contactmessage();
        $contactMessage->fill($data);
        $contactMessage->save();

        $vars = [
            'firstname' => $data['name'],
            'lastname' => $data['surname'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'content' => $data['content']
        ];

        $admin_email = Settings::getValue('admin_email');
        
        if($admin_email) {
            \Mail::send('tps.birzha::mail.message', $vars, function($message) {
                $message->to(Settings::getValue('admin_email'), 'Birzha Admin');
                $message->subject('Контактная форма');
            });
        }

        return response()->json('Contact message sent', 201);
    }
}