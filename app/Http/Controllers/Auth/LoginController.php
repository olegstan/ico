<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Request;
use Socialite;
use Validator;
use Auth;
use Hash;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public static $fields = [
        'email' => 'log',
        'password' => 'pwd'
    ];

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyForm()
    {
        switch (Request::input('login-with-ajax')){
            case 'login':
                return $this->prefix($this->login());
            case 'register':
                return $this->prefix($this->register());
            case 'remember':
                return $this->prefix($this->remember());
            default:
                return $this->prefix([
                    'error' => 'Not found action',
                    'result' => false,
                    'action' => Request::input('login-with-ajax')
                ]);
        }
    }

    /**
     * @param $json
     * @return string
     */
    public function prefix($json)
    {
        return Request::input('callback') . '(' . json_encode($json) . ')';
    }

    
    public function register()
    {

    }

    /**
     * @return array
     */
    public function remember()
    {
        $validator = Validator::make(Request::all(), [
            self::$fields['email'] => 'required|email|max:255',
        ]);

        $validator->setCustomMessages([
            self::$fields['email'] . '.required' => 'Email required',
            self::$fields['email'] . '.email' => 'Email not correct',
        ]);

        if ($validator->fails()) {
            if ($validator->fails()) {
                $errorMessage = '<strong>ERROR</strong>';

                foreach($validator->errors()->all() as $error){
                    if(is_string($error) && isset($error)){
                        $errorMessage .= ' ' . $error;
                    }
                }

                return [
                    'error' => $errorMessage,
                    'result' => false,
                    'action' => Request::input('login-with-ajax')
                ];
            }
        }

        $user = User::where('email', Request::input(self::$fields['email']))->get()->first();
        if ($user) {
            //TODO send email




            return [
                'result' => true,
                'action' => Request::input('login-with-ajax')
            ];
        }

        return [
            'error' => '<strong>ERROR</strong>User not found',
            'result' => false,
            'action' => Request::input('login-with-ajax')
        ];
    }

    /**
     * @return array
     */
    public function login()
    {
        $validator = Validator::make(Request::all(), [
            self::$fields['email'] => 'required|email|max:255',
            self::$fields['password'] => 'required',
        ]);

        $validator->setCustomMessages([
            self::$fields['password'] . '.required' => 'Password required',
            self::$fields['email'] . '.required' => 'Email required',
            self::$fields['email'] . '.email' => 'Email not correct',
        ]);

        if ($validator->fails()) {
            $errorMessage = '<strong>ERROR</strong>';

            foreach($validator->errors()->all() as $error){
                if(is_string($error) && isset($error)){
                    $errorMessage .= ' ' . $error;
                }
            }

            return [
                'error' => $errorMessage,
                'result' => false,
                'action' => Request::input('login-with-ajax')
            ];
        }


        /**
         * @var User $user
         */
        $user = User::where('email', Request::input(self::$fields['email']))->get()->first();

        if ($user && Hash::check(Request::input(self::$fields['password']), $user->password)) {
            //TODO send email



            Auth::loginUsingId($user->id);
            return [
                'result' => true,
                'action' => Request::input('login-with-ajax')
            ];

        }

        return [
            'error' => 'Not correct login or password',
            'result' => false,
            'action' => Request::input('login-with-ajax')
        ];
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    
}
