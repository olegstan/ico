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
            default:
                return $this->prefix([
                    'error' => 'Not found action',
                    'result' => false,
                    'action' => Request::input('login-with-ajax')
                ]);
        }
    }

    public function prefix($json)
    {
        return Request::input('callback') . '(' . json_encode($json) . ')';
    }

    public function login()
    {
        $validator = Validator::make(Request::all(), [
            self::$fields['email'] => 'required|max:255',
            self::$fields['password'] => 'required',
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
            Auth::loginUsingId($user->id);
            return [
                'result' => true,
                'action' => Request::input('login-with-ajax')
            ];

        }

        return $this->prefix([
            'error' => 'Неверный логин/пароль',
            'result' => false,
            'action' => Request::input('login-with-ajax')
        ]);
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
