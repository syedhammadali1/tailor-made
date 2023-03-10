<?php

namespace App\Http\Controllers;

use App\Models\TemporaryMeasurerCommission;
use App\Models\TemporaryModelCommission;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\BusinessSetting;
use App\Models\Message;
use Auth;
use App\Models\Product;
use Mail;
use App\Mail\ConversationMailManager;
use App\Models\RequestPersonaliseProduct;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('asd0');
        if (BusinessSetting::where('type', 'conversation_system')->first()->value == 1) {
            $conversations = Conversation::where('sender_id', Auth::user()->id)->orWhere('receiver_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(5);
            return view('frontend.user.conversations.index', compact('conversations'));
        }
        else {
            flash(translate('Conversation is disabled at this moment'))->warning();
            return back();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_index()
    {
        if (BusinessSetting::where('type', 'conversation_system')->first()->value == 1) {
            $conversations = Conversation::orderBy('created_at', 'desc')->get();
            return view('backend.support.conversations.index', compact('conversations'));
        }
        else {
            flash(translate('Conversation is disabled at this moment'))->warning();
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_type = Product::findOrFail($request->product_id)->user->user_type;

        $conversation = new Conversation;
        $conversation->sender_id = Auth::user()->id;
        $conversation->receiver_id = Product::findOrFail($request->product_id)->user->id;
        $conversation->title = $request->title;

        if($conversation->save()) {
            $message = new Message;
            $message->conversation_id = $conversation->id;
            $message->user_id = Auth::user()->id;
            $message->message = $request->message;

            if (auth()->user()->user_type == 'customer') {
                    $message->customer_viewed = 1;
            } elseif (auth()->user()->user_type == 'delivery_boy') {
                    $message->delivery_boy_viewed = 1;
            } elseif(auth()->user()->user_type == 'measurer') {
                    $message->measurer_viewed = 1;
            }

            if ($message->save()) {
                $this->send_message_to_seller($conversation, $message, $user_type);
            }
        }

        flash(translate('Message has been sent to seller'))->success();
        return back();
    }

    public function send_message_to_seller($conversation, $message, $user_type)
    {
        $array['view'] = 'emails.conversation';
        $array['subject'] = 'Sender:- '.Auth::user()->name;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = 'Hi! You recieved a message from '.Auth::user()->name.'.';
        $array['sender'] = Auth::user()->name;

        if($user_type == 'admin') {
            $array['link'] = route('conversations.admin_show', encrypt($conversation->id));
        } else {
            $array['link'] = route('conversations.show', encrypt($conversation->id));
        }

        $array['details'] = $message->message;

        try {
            Mail::to($conversation->receiver->email)->queue(new ConversationMailManager($array));
        } catch (\Exception $e) {
            //dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {

        $messages = Message::where('conversation_id', decrypt($id))->get();

      if (auth()->user()->user_type == 'customer') {
            foreach ($messages as $key => $message) {
                $message->customer_viewed = 1;
                $message->save();

            }
        } elseif (auth()->user()->user_type == 'seller') {
            foreach ($messages as $key => $message) {
                $message->seller_viewed = 1;
                $message->save();

            }
        } elseif(auth()->user()->user_type == 'measurer') {
            foreach ($messages as $key => $message) {
                $message->measurer_viewed = 1;
                $message->save();
            }
        }

        $request_personalize_product = RequestPersonaliseProduct::where('id',decrypt($id))->first();



        $conversation = Conversation::findOrFail(decrypt($id));
        if ($conversation->sender_id == Auth::user()->id) {
            $conversation->sender_viewed = 1;
        }
        elseif($conversation->receiver_id == Auth::user()->id) {
            $conversation->receiver_viewed = 1;
        }
        $conversation->save();


        if ($conversation->sender_id == Auth::user()->id){
            $otherUserId =  $conversation->receiver->id;
        }else{
           $otherUserId =  $conversation->sender->id;
        }

        $otherPerson = User::find($otherUserId);

        if(Auth::user()->user_type == 'measurer'){
            $commission = TemporaryMeasurerCommission::where('measurer_id', auth()->id())->where('consumer_id', $otherUserId)->first();
            if(!$commission){
                if (isset(Auth::user()->defaultMeasurerCommission)) {
                    $commission = Auth::user()->defaultMeasurerCommission->default_commission;
                }else{
                $commission = 0;
                }
            }else{
                $commission = $commission->commission;
            }

        }elseif(Auth::user()->user_type == 'model'){
            $commission = TemporaryModelCommission::where('model_id', auth()->id())->where('seller_id', $otherUserId)->first();
            if(!$commission){
                if (isset(Auth::user()->defaultModelCommission)) {
                    $commission = Auth::user()->defaultModelCommission->model_commission;
                }else{
                $commission = 0;
                }
            }else{
                $commission = $commission->commission;
            }
        }else{
            $commission = TemporaryMeasurerCommission::where('measurer_id', $otherUserId)->where('consumer_id', auth()->id())->first();
            if(!$commission){
                if (isset($otherPerson->defaultMeasurerCommission)) {
                    $commission = $otherPerson->defaultMeasurerCommission->default_commission;
                }else{
                $commission = 0;
                }
            }else{
                $commission = $commission->commission;
            }
        }

        // dd($commission);

        // $measurer = User::find($request->measurer_id);

        return view('frontend.user.conversations.show', compact('conversation','request_personalize_product','commission'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        return view('frontend.partials.messages', compact('conversation'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_show($id)
    {
        $conversation = Conversation::findOrFail(decrypt($id));
        if ($conversation->sender_id == Auth::user()->id) {
            $conversation->sender_viewed = 1;
        }
        elseif($conversation->receiver_id == Auth::user()->id) {
            $conversation->receiver_viewed = 1;
        }
        $conversation->save();
        return view('backend.support.conversations.show', compact('conversation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $conversation = Conversation::findOrFail(decrypt($id));
        foreach ($conversation->messages as $key => $message) {
            $message->delete();
        }
        if(Conversation::destroy(decrypt($id))){
            flash(translate('Conversation has been deleted successfully'))->success();
            return back();
        }
    }


    public function set_commission(Request $request) {


        $request_personalize_product = RequestPersonaliseProduct::where('id',$request->request_id)->first();



        if(isset($request_personalize_product)){

            $request_personalize_product->price = $request->set_commission;
            $request_personalize_product->save();
            flash(translate('Commission Set Successfully.'));
            return back();

        }

        else{
            flash(translate('Commission Set Successfully.'));
            return back();

        }


    }

}
