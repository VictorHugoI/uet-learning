<?php

namespace App\Http\Controllers\Auth;

use App\Lecturer;
use App\Student;
use App\User;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/home';

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'level' => ['required','string']
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
        $time = DateTime::createFromFormat('d/m/Y',$data['birthday']);
        $data_user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'level' => $data['level'],
            'avatar' => ""
        ]);
        if ($data['level'] == 'student'){
            Student::create([
                'user_id' => $data_user['id'],
                'name' => $data['name'],
                'student_code' => $data['student_code'],
                'gender' => $data['gender'],
                'birthday' => $time,
                'class_name' => $data['class_name']
            ]);
        } else if ($data['level'] == 'lecturer'){
            Lecturer::create([
                'user_id' => $data_user['id'],
                'name' => $data['name'],
                'lecturer_code' => $data['lecturer_code'],
                'gender' => $data['gender'],
                'birthday' => $time,
                'phone_number' => $data['phone_number']
            ]);
        }
        return $data_user;
    }
}
