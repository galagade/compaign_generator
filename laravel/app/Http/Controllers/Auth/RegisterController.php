<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\Employee;
use App\Role;
use Validator;
use Eloquent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;


class RegisterController extends Controller
{
	public function __construct()
    {
        //$this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }
    
    public function index()
    {
        $roleCount = Role::count();
		if($roleCount != 0) {
			
			
			return view('auth.register_client');
			
		} else {
			return view('errors.error', [
				'title' => 'Migration not completed',
				'message' => 'Please run command <code>php artisan db:seed</code> to generate required table data.',
			]);
		}
    }

   public function Post_Signup(Request $request){

   		$rules = [
                'name' => 'required|max:255',
            	'email' => 'required|email|max:255|unique:users',
            	'password' => 'required|min:6|confirmed',
            	'gender' => 'required|min:2',
            	'mobile' => 'required|integer',
            	'city'=>'required',
				'address' =>'required',
				'about' =>'required',
				'date_birth' =>'required',
        ];
		$validator = Validator::make($request->all(), $rules);
                    
        if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
        }
        else
        {
        	$data = $request->all();
	        $employee = new Employee();
	        $employee->fill([
	            'name' => $data['name'],
	            'designation' => "Client",
	            'mobile' => $data['mobile'],
	            'mobile2' => "",
	            'email' => $data['email'],
	            'gender' => $data['gender'],
	            'dept' => "2",
	            'city' => $data['city'],
	            'address' =>  $data['address'],
	            'about' => $data['about'],
	            'date_birth' => date("Y-m-d", strtotime($data['date_birth'])),
	            'date_hire' => date("Y-m-d"),
	            'date_left' => date("Y-m-d"),
	            'salary_cur' => 0,
	        ])->save();
	        
	        $user = User::create([
	            'name' => $data['name'],
	            'email' => $data['email'],
	            'password' => bcrypt($data['password']),
	            'context_id' => $employee->id,
	            'type' => "Employee",
	        ]);
	        $role = Role::where('name', 'CLIENT_USER')->first();
	        $user->attachRole($role);
	        $request->session()->flash('alert-success', "You have successfully registered");
			return redirect('/signup');
		}
   }
    
}