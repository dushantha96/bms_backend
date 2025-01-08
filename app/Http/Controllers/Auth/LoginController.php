<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your Dashboard screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $admin = RouteServiceProvider::AdminDashboard;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $merchant = RouteServiceProvider::MerchantDashboard;

     /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $seeker = RouteServiceProvider::SeekerDashboard;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        session()->flash('success', 'You are logged in!\n\n\nPlease select Faculty to proceed further');
        switch (Auth::user()->user_type) {
            case 0:
				return $this->admin;
                break; 
			case 1:  
				return $this->admin;
                break;  
            case 2:
                return $this->admin;
                break;          
            default:
                return $this->seeker;
                break;
        }        
    }
}