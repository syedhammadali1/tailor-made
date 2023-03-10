<?php

namespace App\Http\Controllers\Seller;

use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\BusinessSetting;
use App\Models\Message;
use Auth;
use Illuminate\Support\Facades\Storage;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (BusinessSetting::where('type', 'conversation_system')->first()->value == 1) {
            $conversations = Conversation::where('sender_id', Auth::user()->id)->orWhere('receiver_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(5);
            return view('seller.conversations.index', compact('conversations'));
        }
        else {
            flash(translate('Conversation is disabled at this moment'))->warning();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $messages = Message::where('conversation_id', decrypt($id))->get();

        if(isset($messages)){
            foreach ($messages as $key => $message) {
                $message->seller_viewed = 1;
                $message->save();

            }
        }




        $conversation = Conversation::findOrFail(decrypt($id));

        if ($conversation->sender_id == Auth::user()->id) {
            $conversation->sender_viewed = 1;
        }
        elseif($conversation->receiver_id == Auth::user()->id) {
            $conversation->receiver_viewed = 1;
        }
        $conversation->save();
        return view('seller.conversations.show', compact('conversation'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function message_store(Request $request)
    {
        // dd($request->all());
        if($request->has('file')){

            $message = new Message;
            $message->conversation_id = $request->conversation_id;
            $message->user_id = Auth::user()->id;
            $message->save();

            //
            $file = $request->file;
            $mimeType = $file->getClientmimeType();    // image/png
            $fileType = substr($mimeType, 0, strpos($mimeType, "/"));  // image
            $full_path = 'uploads/messages/'.$message->id.'/'. basename($file);
            $returnedPath = Storage::disk('local')->put($full_path, $file);

           $attachment =  Attachment::create([
                'model_type' => 'Message',
                'model_id' => $message->id,
                'attachment_type' => $fileType,
                'attachment_path' => $returnedPath,
            ]);
            //

            $message->is_attachment = 1;
            $message->attachment_id = $attachment->id;
            $message->save();

            $conversation = $message->conversation;
            if ($conversation->sender_id == Auth::user()->id) {
                $conversation->receiver_viewed ="1";
            }
            elseif($conversation->receiver_id == Auth::user()->id) {
                $conversation->sender_viewed ="1";
            }
            $conversation->save();

        }

        if ($request->has('message')) {
            $message = new Message;
            $message->conversation_id = $request->conversation_id;
            $message->user_id = Auth::user()->id;
            $message->message = $request->message;
            $message->seller_viewed = 1;
            $message->save();
            $conversation = $message->conversation;
            if ($conversation->sender_id == Auth::user()->id) {
                $conversation->receiver_viewed ="1";
            }
            elseif($conversation->receiver_id == Auth::user()->id) {
                $conversation->sender_viewed ="1";
            }
            $conversation->save();

        }


        return back();
    }

}

