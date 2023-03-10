<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Auth;
use App\Models\{
    User,
    UserProfile,
    Conversation,
    MeasurerAvailablityHours,
    Message,
    RequestAppointment,
    RequestPersonaliseProduct,
    TemporaryMeasurerCommission
};

class MeasurerController extends Controller
{
    //
    public function register_measurer(Request $request)
    {

        if(Auth::check()) {
			if((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer')) {
				flash(translate('Admin or Customer can not be a measurer'))->error();
				return back();
			} if(Auth::user()->user_type == 'seller'){
				flash(translate('This user already a measurer'))->error();
				return back();
			}

        }
        else {
            if($request->isMethod('post')) {
                $validated = \Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required|min:6',
                    'password_confirmation' => 'required|same:password',

                ]);
                if($validated->fails()){
                    return back()->withErrors($validated->messages())->withInput();
                }


                $data = $request->all();

                $data['password'] = bcrypt($data['password']);
                $data['user_type'] = 'measurer';


                // dd($data);
                if ($user = User::create($data)) {
                    // 4 profile id of Measurer
                    if(UserProfile::create(['user_id' => $user->id, 'profile_id' => 4]) ) {
                        flash(translate('Registration successfully.'));
                        return back();
                    }
                }

                flash(translate('Sorry! Something went wrong.'))->error();
                return back();
            }
            else {
                return view('frontend.measurer_form');
            }
        }

    }
    public function measurer_conversations_create(Request $request) {

        if (Auth::check()) {

            $measurer = User::findOrFail($request->measurer_id);

            // dd($request->all());


            $curUser = Auth::user();
            if ($measurer) {

                $conversation = Conversation::where(['sender_id' => $curUser->id, 'receiver_id' => $request->measurer_id])->first();
                if($conversation) {
                    return redirect()->route('seller.measurer.conversations', ['id' => encrypt($conversation->id), 'measurer_id' => $request->measurer_id, 'request_id' => $request->request_id]);
                }
                else {
                    $conversation = Conversation::create(['sender_id' => $curUser->id, 'receiver_id' => $request->measurer_id, 'sender_viewed' => 0, 'receiver_viewed' => 0]);
                    if ($conversation) {
                        return redirect()->route('seller.measurer.conversations', ['id' => encrypt($conversation->id), 'measurer_id' => $request->measurer_id, 'request_id' => $request->request_id]);
                    }
                }
            }
            else {
                return redirect('/');
            }
        }
        else {
            return redirect('/');
        }
    }
    public function measurer_conversations(Request $request, $id) {

        // dd($request->all());



        $measurer_avaliablity = MeasurerAvailablityHours::where('measurer_id',$request->measurer_id)->get();



        // dd($measurer_hours);


        if($request->isMethod('post')) {

            $message = new Message;
            $message->conversation_id = $request->conversation_id;
            $message->user_id = Auth::user()->id;
            $message->message = $request->message;
            $message->save();
            $conversation = $message->conversation;
            if ($conversation->sender_id == Auth::user()->id) {
                $conversation->receiver_viewed = "1";
            }
            elseif($conversation->receiver_id == Auth::user()->id) {
                $conversation->sender_viewed = "1";
            }
            $conversation->save();

            return back();
        }
        else {
            $conversation = Conversation::findOrFail(decrypt($id));
            $request_personalize_product = RequestPersonaliseProduct::where('id',$request->request_id)->first();
            // dd($request->request_id);
            if ($conversation->sender_id == Auth::user()->id) {
                $conversation->sender_viewed = 1;
            }
            elseif($conversation->receiver_id == Auth::user()->id) {
                $conversation->receiver_viewed = 1;
            }
            $conversation->save();

            $measurer = User::find($request->measurer_id);
            $commission = TemporaryMeasurerCommission::where('measurer_id', $request->measurer_id)->where('consumer_id', auth()->id())->first();

            if(!$commission){
                if (isset($measurer->defaultMeasurerCommission)) {
                    $commission = $measurer->defaultMeasurerCommission->default_commission;
                }else{
                    $commission = 0;
                }
            }else{
                $commission = $commission->commission;
            }
            return view('seller.requests.conversations', compact('conversation','request_personalize_product','measurer_avaliablity','measurer','commission'));
        }
    }

    public function refresh(Request $request)
    {
        $conversation = Conversation::findOrFail(decrypt($request->id));
        if($conversation->sender_id == Auth::user()->id){
            $conversation->sender_viewed = 1;
            $conversation->save();
        }
        else{
            $conversation->receiver_viewed = 1;
            $conversation->save();
        }
        return view('frontend.partials.measurer-messages', compact('conversation'));
    }

    public function appointment_create(Request $request) {

        $tmpcommission = TemporaryMeasurerCommission::where([
            ['measurer_id', '=', decrypt($request->measurer_id)],
            ['consumer_id', '=', auth()->id()]
        ])->delete();

        $requestPer = RequestPersonaliseProduct::findOrFail(decrypt($request->request_id));

        RequestAppointment::create(['datetime' => $request->datetime,'measurer_commission' => $request->measurer_commission , 'request_id' => decrypt($request->request_id), 'measurer_id' => decrypt($request->measurer_id), 'owner_id' => $requestPer->owner_id, 'product_id' => $requestPer->product_id, ]);
        return redirect()->route('seller.requests.index')->with('msg', 'Appointment created successfully!');
    }
    public function appointments() {

        $appointments = RequestAppointment::with(['measurer:id,name', 'owner:id,name', 'request:id,price', 'product:id,name'])->where(['owner_id' => Auth::user()->id])->paginate(10);
        return view('seller.requests.appointments', compact('appointments'));
    }

    public function set_commission(Request $request) {


        $request_personalize_product = RequestPersonaliseProduct::where('id',$request->request_id)->first();



        if(isset($request_personalize_product)){

            $request_personalize_product->price = $request->set_commission;
            $request_personalize_product->save();
            flash(translate('Measurer Commission Set Successfully.'));
            return back();

        }

        else{
            flash(translate('Measurer Commission Set Successfully.'));
            return back();

        }


    }





}
