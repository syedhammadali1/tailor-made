<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Models\Message;
use Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // dd($request->all());
        // $message = new Message;
        // $message->conversation_id = $request->conversation_id;
        // $message->user_id = Auth::user()->id;
        // $message->message = $request->message;
        // $message->save();
        // $conversation = $message->conversation;
        // if ($conversation->sender_id == Auth::user()->id) {
        //     $conversation->receiver_viewed ="1";
        // }
        // elseif($conversation->receiver_id == Auth::user()->id) {
        //     $conversation->sender_viewed ="1";
        // }
        // $conversation->save();

        // return back();

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }
}
