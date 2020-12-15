<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\Route;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
            // 'first_name' => ['required', 'string', 'max:255'],
            // 'last_name' => ['required', 'string', 'max:255'],
            // 'klas' => ['required', 'int'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],

            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'klas' => ['int'],
            'email' => ['string', 'email', 'max:255', 'unique:users'],
            'password' => ['string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {   
        if(isset($data['admin_checkbox'])){
            $admin = true;      
        }else{
            $admin = false;
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'admin' => $admin,
        ]);
        $createdUser = User::where('name', '=', $data['name'])->first();

        if($admin){
            \App\Models\Teacher::create([
                'first_name'  => $data['name'],
                'last_name'   => $data['last_name'],
                'created_at'  => now(),
                'user_id'     => $createdUser->id,
            ]);
        }
        
        
        
        
        //else{
        //     return \App\Models\Student::create([
        //         'first_name'  => $data['name'],
        //         'last_name'   => $data['last_name'],     //TODO:: KLAS_ID DINGEN && INSERT IN STUDENT TABLE
        //         'created_at'  => now(),
        //         'klas_id'     => $data['klas_id'],
        //         'user_id'     => $id->id,
        //     ]);
        // }

    }
}
