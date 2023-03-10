<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RequestPersonaliseProduct;
use App\Models\User;
use App\Models\RequestAppointment;
use App\Models\RequestMembership;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;

class SellerRequestController extends Controller
{
    //
    // public function index() {
    //     $requests = RequestPersonaliseProduct::with('customer', 'product')->where('owner_id', \Auth::user()->id)->paginate();
    //     $measurers = User::join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->select(['users.id', 'users.name'])->get();

    //     return view('seller.requests.index', compact('requests', 'measurers'));
    // }
    public function index(Request $request) {


        // dd($requests->customer->customer_addresses);


        $requests = RequestPersonaliseProduct::with('addresses','customer', 'product', 'appointment:id,request_id,appointment_status')->where(function($query) use($request){
            $cQuery = $query->where('owner_id', \Auth::user()->id);
            if($request->get('request_id')){

                $cQuery = $query->where('id', $request->get('request_id'));
            }
            return $cQuery;
        })->paginate();


        // dd($requests->customer);


        // foreach ($requests as $key => $value) {
        //    dd($value->customer->customer_addresses);
        // }





        $measurers = User::join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->select(['users.id', 'users.name'])->get();

        appointments_expire();

        return view('seller.requests.index', compact('requests', 'measurers'));
    }


    public function nearby_measurers(Request $request) {



    $address = Address::find($request->address_id);
     $user = User::find($request->user_id);

     $latitude  =       $address->latitude;
     $longitude =       $address->longitude;

     $nearUser  =       DB::table("users")->join('addresses', 'addresses.user_id', '=', 'users.id');

     $nearUser  =       $nearUser->select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                        * cos(radians(addresses.latitude)) * cos(radians(addresses.longitude) - radians(" . $longitude . "))
                        + sin(radians(" .$latitude. ")) * sin(radians(addresses.latitude))) AS distance"))->where("users.id","!=",$user->id);
     $nearUser  =       $nearUser->having('distance', '<', 20);
     $nearUser  =       $nearUser->where('users.user_type', 'measurer');
     $nearUser  =       $nearUser->orderBy('distance', 'asc');

     $data['measurers'] =       $nearUser->get();


     return response()->json($data);

    }

    public function appointment_show($id) {
        $appointment = RequestAppointment::findOrFail($id);
        return view('seller.requests.request-appointment', compact('appointment'));
    }


    public function membership_request_store(Request $request)
    {

     $request_membership = new RequestMembership;
     $request_membership->seller_id = Auth::id();
     $request_membership->profile_type_id = $request->profile_type_id;

     if($request_membership->save()){

        flash(translate('Request for Premium User has successfully sent'))->success();
        return redirect()->back();
    }

    }

    public function all_membership_requests()
    {
        $request_membership =  RequestMembership::with('user','profile_type')->get();

        return view('backend.sellers.seller_membership_request.index', compact('request_membership'));


    }

    public function update_membership_status(Request $request)
    {

        if($request->status == "1"){

        $request_membership =  RequestMembership::where('seller_id',$request->id)->first();

        $request_membership->is_premium = 1;
        $request_membership->save();


        DB::update('update user_profiles set profile_id = 2 where user_id = ?', [$request->id]);

        }

        elseif ($request->status == "0") {

            $request_membership =  RequestMembership::where('seller_id',$request->id)->first();
            $request_membership->is_premium = 0;
            $request_membership->save();
        }

    }
}
