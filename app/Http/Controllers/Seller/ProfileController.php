<?php

namespace App\Http\Controllers\Seller;

use App\Http\Requests\SellerProfileRequest;
use App\Models\Profile;
use App\Models\User;
use App\Models\ProfileType;
use App\Models\UserProfile;
use Auth;
use Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses;

        $profile_type = ProfileType::get();

        $user_profiles = UserProfile::with('profile')->where('user_id',Auth::id())->get();

        // dd($profile);


        //  dd($profile);

        return view('seller.profile.index', compact('user','addresses','profile_type','user_profiles'));
    }



    public function getProfile($id)
    {
        $data['profiles'] = Profile::where("profile_type_id",$id)
                    ->get(["profile_name","id"]);
        return response()->json($data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SellerProfileRequest $request , $id)
    {

        // dd(auth()->id());
        // dd($request->all());

        if(env('DEMO_MODE') == 'On'){
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }

        $user->avatar_original = $request->photo;

        $shop = $user->shop;

        if($shop){
            $shop->cash_on_delivery_status = $request->cash_on_delivery_status;
            $shop->bank_payment_status = $request->bank_payment_status;
            $shop->bank_name = $request->bank_name;
            $shop->bank_acc_name = $request->bank_acc_name;
            $shop->bank_acc_no = $request->bank_acc_no;
            $shop->bank_routing_no = $request->bank_routing_no;

            $shop->save();
        }

        $user->save();

        // $delete_old_profile  = UserProfile::where('user_id',Auth::id())->delete();
        // foreach ($request->profile_id as $key => $value) {

        //    $user_profile = new UserProfile;
        //    $user_profile->user_id = Auth::id();
        //    $user_profile->profile_id = $value;
        //    $user_profile->save();
        // }

        if($request->profile_type){
            $delete_old_profile  = UserProfile::where('user_id',Auth::id())->delete();

            $user_profile = new UserProfile;
            $user_profile->user_id = Auth::id();
            $user_profile->profile_id = $request->profile_type;
            $user_profile->save();

        }

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }
}
