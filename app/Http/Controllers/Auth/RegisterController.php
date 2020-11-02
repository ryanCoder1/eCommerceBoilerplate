<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\User;
use Mail;
use App\Mail\SendMailable;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'welcome';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [

            'email' => 'sometimes|string|email|max:255|unique:users',
            'firstName' => 'required|string|min:1',
            'lastName' => 'required|string|min:1',
            'password' => 'required|string|min:6',
            'outfitName' => 'string|min:1',
            'address' => 'string|min:1',
            'city' => 'string|min:1',
            'state' => [

                        Rule::notIn(['choose']),
                       ],
            'zipcode' => 'string|min:5',

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'email' => $data['email'],
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'password' => Hash::make($data['password']),
            'verifyToken' => Str::random(40),

        ]);

        $thisUser = User::findOrFail($user->id);
        $this->sendEmail($thisUser);
        $saved = $user->save();
          if($saved)
          {
            return $saved;
           }

    }
   // send Registeration Email Verification to verify account
   // @param $thisUser = current user
    public function sendEmail($user)
    {
        // Mail::to($thisUser['email'])->send(new SendMailable($thisUser));
        Mail::send('emails.verifyUser', ['user' => $user], function ($m) use ($user) {
           $m->from('fieldjobtracker@gmail.com', 'Field Job Tracker');

           $m->to($user->email, $user->name)->subject('Verify email');
       });
    }

    public function getEmailReturn()
    {
      return view('welcome');
    }
    public function sendEmailDone(Request $request)
    {

        $user = User::where(['email' => $request->input('email'), 'verifyToken' => $request->input('verifyToken')])->first();

   if($user)
   {
      User::where(['email' => $request->input('email'), 'verifyToken' => $request->input('verifyToken')])->update(['verifyEmail' => '1', 'verifyToken' => NULL]);
       return response()->json(['verifyEmailSuccess' => $request->input('email') .' account is verified, you can log in!']);
   }
    else{
       return response()->json(['verifyEmailNonActive' => 'This link isn\'t active anymore.']);
    }

    }

}
