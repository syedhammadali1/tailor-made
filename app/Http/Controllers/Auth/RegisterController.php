<?php

namespace App\Http\Controllers\Auth;

use App\ClubPoint;
use App\Models\ClubPointType;
use App\Models\User;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\BusinessSetting;
use App\Notifications\ProfileApprovedByAdminNotification;
use App\OtpConfiguration;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OTPVerificationController;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Cookie;
use Session;
use Nexmo;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use App\Models\Shop;
use Illuminate\Support\Facades\Notification;
use Ipdata\ApiClient\Ipdata;
use Symfony\Component\HttpClient\Psr18Client;
use Nyholm\Psr7\Factory\Psr17Factory;


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
    protected $redirectTo = '/';

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
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
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


        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'user_type' => $data['user_type'],
                'password' => Hash::make($data['password']),
            ]);

            if ($data['user_type'] == 'seller') {
                $shop = new Shop;
                $shop->user_id = $user->id;
                $shop->name = $data['shop_name'];
                $shop->address = $data['shop_address'];
                $shop->save();
            }

        }
        else {
            if (addon_is_activated('otp_system')){
                $user = User::create([
                    'name' => $data['name'],
                    'phone' => '+'.$data['country_code'].$data['phone'],
                    'password' => Hash::make($data['password']),
                    'verification_code' => rand(100000, 999999)
                ]);

                $otpController = new OTPVerificationController;
                $otpController->send_code($user);
            }
        }

        if(session('temp_user_id') != null){
            Cart::where('temp_user_id', session('temp_user_id'))
                    ->update([
                        'user_id' => $user->id,
                        'temp_user_id' => null
            ]);

            Session::forget('temp_user_id');
        }

        if(Cookie::has('referral_code')){
            $referral_code = Cookie::get('referral_code');
            $referred_by_user = User::where('referral_code', $referral_code)->first();
            if($referred_by_user != null){
                $user->referred_by = $referred_by_user->id;
                $user->save();
            }
        }

        $club_type = ClubPointType::where('name','registration')->first();

        $get_req_points = BusinessSetting::where('type', 'club_point_registration_rate')->first();

        $club_point = new ClubPoint;
        $club_point->user_id = $user->id;
        $club_point->points = $get_req_points->value ?? 100;
        $club_point->order_id = null;
        $club_point->club_point_type_id = $club_type->id ?? 1; // for registration
        $club_point->convert_status = 1;
        $club_point->save();


        wallet_payment_done($user->id, floatval($club_point->points / $get_req_points->value), 'Club Point Convert', 'Club Point Convert');

        return $user;
    }

    public function register(Request $request)
    {
        // dd('asd')
     
        $getUserIpAddress = $request->ip();
        // dd($getUserIpAddress);
        $httpClient = new Psr18Client();
        // dd($httpClient);

        $psr17Factory = new Psr17Factory();
        $ipdata = new Ipdata('7e26940b851ff17e2f8933799f1294dee3d66c1ca2365998ca85ad21', $httpClient, $psr17Factory);
        $data = $ipdata->lookup($getUserIpAddress,['flag']);
        // dd($data['flag']);
       
        // dd($admins = User::where('user_type', 'admin')->get());
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                if(User::where('email', $request->email)->first() != null){
                    flash(translate('Email or Phone already exists.'));
                    return back();
                }
            }
            elseif (User::where('phone', '+'.$request->country_code.$request->phone)->first() != null) {
                flash(translate('Phone already exists.'));
                return back();
            }

            $this->validator($request->all())->validate();

            $user = $this->create($request->all());

            $this->guard()->login($user);

            if($user->email != null){
                if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
                    $user->email_verified_at = date('Y-m-d H:m:s');

                    if (isset($data['flag'])) {
                        $user->country_flag = $data['flag'];
                    }
                    $user->save();
                    flash(translate('Registration successful.'))->success();
                }
                else {
                    try {
                        $user->sendEmailVerificationNotification();
                        flash(translate('Registration successful. Please verify your email.'))->success();
                    } catch (\Throwable $th) {
                        $user->delete();
                        flash(translate('Registration failed. Please try again later.'))->error();
                    }
                }
            }
            // send nitification to admin for  approve profile
            $admins = User::where('user_type', 'admin')->get();

            Notification::send($admins, new ProfileApprovedByAdminNotification($user));
            return $this->registered($request, $user)
                ?: redirect($this->redirectPath());


}

    protected function registered(Request $request, $user)
    {
        if ($user->email == null) {
            return redirect()->route('verification');
        }elseif(session('link') != null){
            return redirect(session('link'));
        }else {
            return redirect()->route('home');
        }
    }
}
