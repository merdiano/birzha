<?php

use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\Settings as UserSettings;
use Vdomah\JWTAuth\Models\Settings;

Route::group(['prefix' => 'api'], function() {

    Route::post('login', function (Request $request) {
        if (Settings::get('is_login_disabled'))
            App::abort(404, 'Page not found');

        $login_fields = Settings::get('login_fields', ['email', 'password']);

        $credentials = Input::only($login_fields);
        $username = $credentials['username'];

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt(array_merge($credentials, ['username' => $credentials['dial_code'] . $username]))) {
                return response()->json(['error' => [
                    'ru' => trans('validation.no_user', [], 'ru'),
                    'en' => trans('validation.no_user', [], 'en'),
                    'tm' => trans('validation.no_user', [], 'tm'),
                ]], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $userModel = JWTAuth::authenticate($token);

        // if user is not activated, he will not get token
        if(!$userModel->is_activated) {
            return response()->json(['error' => 'Not activated'], 403);
        }

        if ($userModel->methodExists('getAuthApiSigninAttributes')) {
            $user = $userModel->getAuthApiSigninAttributes();
        } else {
            $user = [
                'id' => $userModel->id,
                'name' => $userModel->name,
                'surname' => $userModel->surname,
                'username' => $userModel->username,
                'email' => $userModel->email,
                'is_activated' => $userModel->is_activated,
                'user_balance' => $userModel->user_balance
            ];
        }
        // if no errors are encountered we can return a JWT
        return response()->json(compact('token', 'user'));
    });

    Route::post('refresh', function (Request $request) {
        if (Settings::get('is_refresh_disabled'))
            App::abort(404, 'Page not found');

        $token = Request::get('token');

        try {
            // attempt to refresh the JWT
            if (!$token = JWTAuth::refresh($token)) {
                return response()->json(['error' => 'could_not_refresh_token'], 401);
            }
        } catch (Exception $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_refresh_token'], 500);
        }

        // if no errors are encountered we can return a new JWT
        return response()->json(compact('token'));
    });

    Route::post('invalidate', function (Request $request) {
        if (Settings::get('is_invalidate_disabled'))
            App::abort(404, 'Page not found');

        $token = Request::get('token');

        try {
            // invalidate the token
            JWTAuth::invalidate($token);
        } catch (Exception $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_invalidate_token'], 500);
        }

        // if no errors we can return a message to indicate that the token was invalidated
        return response()->json('token_invalidated');
    });

    Route::post('signup', function (Request $request) {
        if (Settings::get('is_signup_disabled'))
            App::abort(404, 'Page not found');

        $login_fields = Settings::get('signup_fields', ['email', 'password', 'password_confirmation']);
        $credentials = Input::only($login_fields);

        $rules = [
            'email'    => 'required|between:6,191|email',
            'username' => 'required|digits_between:8,20|numeric',
            'dial_code' => 'required',
        ];

        $validation = \Validator::make($credentials, $rules,(new UserModel)->messages);
        if ($validation->fails()) {
            return Response::json(['error' => $validation->errors()], 400);
        }

        /**
         * activation is set to be automatic
         */
        $automaticActivation = UserSettings::get('activate_mode') == UserSettings::ACTIVATE_AUTO;

        try {
            // password_confirmation is required
            // but not used when signing up like on web-site
            if (!array_key_exists('password_confirmation', $credentials) && array_key_exists('password', $credentials)) {
                $credentials['password_confirmation'] = $credentials['password'];
            }
            $userModel = Auth::register(array_merge($credentials,[
                'username' => $credentials['dial_code'] . $credentials['username']
            ]), $automaticActivation);

            if ($userModel->methodExists('getAuthApiSignupAttributes')) {
                $user = $userModel->getAuthApiSignupAttributes();
            } else {
                $user = [
                    'id' => $userModel->id,
                    'name' => $userModel->name,
                    'surname' => $userModel->surname,
                    'username' => $userModel->username,
                    'dial_code' => $userModel->dial_code,
                    'email' => $userModel->email,
                    'is_activated' => $userModel->is_activated,
                ];
            }
        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()], 401);
        }

        $token = JWTAuth::fromUser($userModel);

        return Response::json(compact('token', 'user'));
    });

    Route::get('me', function() {

        $me = \JWTAuth::parseToken()->authenticate()
            ->only(['name','surname','email','username','is_activated','phone','company','street_addr','city','mobile']);

        return Response::json(compact('me'));

    })->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');

    Route::post('me', function(Request $request) {

        $me = \JWTAuth::parseToken()->authenticate();
        if(!$me) {
            return Response::json(['error' => 'Not found'], 404);
        }

        $data = Input::all();

        $rules = [
            'email'    => 'required|between:6,191|email',
            'username' => 'required|digits_between:8,20|numeric',
            'company' => 'max:191',
            'phone' => 'numeric',
            'mobile' => 'numeric',
            'street_addr' => 'max:191',
            'city' => 'max:191',
            'about' => 'digits:6|numeric',
        ];

        $validation = \Validator::make($data, $rules,(new UserModel)->messages);
        if ($validation->fails()) {
            return Response::json(['error' => $validation->errors()], 400);
        }

        /**
         * If password in input data, add rules for password
         */
        if (array_key_exists('password', $data) && strlen($data['password']))  {
            $rules = [
                'password' => 'required:create|between:8,255|confirmed',
                'password_confirmation' => 'required_with:password|between:8,255'
            ];

            $validation = \Validator::make($data, $rules,(new UserModel)->messages);
            if ($validation->fails()) {
                return Response::json(['error' => $validation->errors()], 400);
            }
        }

        $me->fill($data);
        $me->save();

        /*
         * Password has changed, reauthenticate the user - send new token
         */
        if (array_key_exists('password', $data) && strlen($data['password'])) {

            $credentials['username'] = $me->username;
            $credentials['password'] = $data['password'];

            try {
                // verify the credentials and create a token for the user
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                // something went wrong
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            $userModel = JWTAuth::authenticate($token);

            // if user is not activated, he will not get token
            if(!$userModel->is_activated) {
                return response()->json(['error' => 'Not activated'], 403);
            }

            if ($userModel->methodExists('getAuthApiSigninAttributes')) {
                $user = $userModel->getAuthApiSigninAttributes();
            } else {
                $user = [
                    'id' => $userModel->id,
                    'name' => $userModel->name,
                    'surname' => $userModel->surname,
                    'username' => $userModel->username,
                    'email' => $userModel->email,
                    'is_activated' => $userModel->is_activated,
                ];
            }
            // if no errors are encountered we can return a JWT
            return response()->json(compact('token', 'user'));

        }

        return Response::json(compact('me'));

    })->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
});
