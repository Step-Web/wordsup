<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'username' => ['required', 'string', 'max:50','unique:users','regex:/^(?=.*[a-zA-Z]).+$/'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return string
     */
    protected function makeUserPic($user_id,$username){
        $bg =  array('#fc5c65','#fd9644','#fed330','#26de81','#2bcbba','#eb3b5a','#fa8231','#f7b731','#20bf6b','#0fb9b1','#45aaf2','#4b7bec','#a55eea','#778ca3','#2d98da','#3867d6','#8854d0','#4b6584','#16a085','#2980b9','#34495e','#e74c3c','#c0392b','#0799d1','#3742fa','#70a1ff');
        $key = array_rand($bg);
        $username = translit($username);
        $img = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90"><rect width="90" height="90" style="fill:'.$bg[$key].'"/><text x="50%" y="66%" font-weight="bold" font-size="42" font-family="Arial" fill="#fff" style="text-anchor:middle">'.mb_strtoupper($username[0],'UTF-8').'</text></svg>';
        $fn='images/user/'.$user_id.'.svg';
        Storage::disk('public')->put($fn, $img);
        return Storage::url($fn);
    }

    protected function create(array $data)
    {

        //include base_path() .'storage\func\translit.php';
       $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->userpic = $this->makeUserPic($user->id,$data['username']);
        $user->save();
        return $user;

    }
}
