<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Request;
use Mail;
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
        'login' => [
            'email' => 'log',
            'password' => 'pwd'
        ],
        'remember' => [
            'email' => 'user_login'
        ],
        'register' => [
            'email' => 'user_login'
        ]
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

    /**
     * @return array
     */
    public function register()
    {
        $validator = Validator::make(Request::all(), [
            self::$fields['remember']['email'] => 'required|email|max:255|unique:users,email',
        ]);

        $validator->setCustomMessages([
            self::$fields['remember']['email'] . '.required' => 'Email required',
            self::$fields['remember']['email'] . '.email' => 'Email not correct',
            self::$fields['remember']['email'] . '.unique' => 'Email not unique',
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


        $password = str_random(5);
        $user = User::create([
            'email' => Request::input(self::$fields['remember']['email']),
            'credits' => 1000,
            'password' => bcrypt($password),
            'role_id' => User::GAMER
        ]);

        if($user){
            $email = Request::input(self::$fields['remember']['email']);

            Mail::send('emails.register', [
                'login' => $email,
                'password' => $password
            ], function ($message) use ($email) {
                $message->to($email, 'Успешная регистрация')->subject('Успешная регистрация')->from(env('MAIL_SENDER'));
            });


            return [
                'result' => true,
                'action' => Request::input('login-with-ajax')
            ];
        }else{
            return [
                'error' => '<strong>ERROR</strong>' . ' user not saved',
                'result' => true,
                'action' => Request::input('login-with-ajax')
            ];
        }

    }

    /**
     * @return array
     */
    public function remember()
    {
        $validator = Validator::make(Request::all(), [
            self::$fields['remember']['email'] => 'required|email|max:255',
        ]);

        $validator->setCustomMessages([
            self::$fields['remember']['email'] . '.required' => 'Email required',
            self::$fields['remember']['email'] . '.email' => 'Email not correct',
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

        $user = User::where('email', Request::input(self::$fields['remember']['email']))->get()->first();
        if ($user) {
            $password = str_random(10);
            $user->update([
                'password' => bcrypt($password)
            ]);

            $email = Request::input(self::$fields['remember']['email']);
            Mail::send('emails.forgot', ['password' => $password], function ($message) use ($email) {
                $message->to($email, 'Восстановление пароля')->subject('Восстановление пароля')->from(env('MAIL_SENDER'));
            });


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
            self::$fields['login']['email'] => 'required|email|max:255',
            self::$fields['login']['password'] => 'required',
        ]);

        $validator->setCustomMessages([
            self::$fields['login']['password'] . '.required' => 'Password required',
            self::$fields['login']['email'] . '.required' => 'Email required',
            self::$fields['login']['email'] . '.email' => 'Email not correct',
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
        $user = User::where('email', Request::input(self::$fields['login']['email']))->get()->first();

        if ($user && Hash::check(Request::input(self::$fields['login']['password']), $user->password)) {
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
    protected $redirectTo = '/';

//    /**
//     * Create a new controller instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
//        $this->middleware('guest', ['except' => 'logout']);
//    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->to('/');
    }
    
}
