<?php

namespace App\Http\Controllers;

use App\ClubPoint;
use App\Models\Attachment;
use App\Models\BusinessSetting;
use App\Models\ClubPointType;
use App\Models\Upload;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Product;
use App\Models\RequestAppointment;
use App\Models\RequestAttachment;
use App\Models\RequestPersonaliseProduct;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    public function add_request_to_personaliser(Request $request) {
        if(!\Auth::check())
            return abort(403);


        $currentDateTime = date("Y-m-d h:i:sa");

        $product = Product::findOrFail($request->id);

        $address = Address::where(['user_id' => \Auth::user()->id])->count();
        // dd(\Auth::user()->id);
        if($address == 0) {
            Address::create([
                'user_id' => \Auth::user()->id,
                'address' => $request->address,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'set_default' => 1,
            ]);
        }


        $variation = '';
        if($request->has('color')) {
            $variation = $request['color'];
        }




        if ($product->digital != 1) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode($product->choice_options) as $key => $choice) {
                if($variation != null){
                    $variation .= '-'.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
                else{
                    $variation .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
            }
        }



        $product_stock = $product->stocks->where('variant', $variation)->first();
        $price = $product_stock->price;

        // dd($addresses->first());
        // dd();



        if (isset($product->personalizeProductTypeName)) {
            # code...


        if($product->personalizeProductTypeName->slug == "clothing" || $product->personalizeProductTypeName->slug == "shoes"){

            $checkRequestPersonaliseProduct = RequestPersonaliseProduct::create([
                'owner_id' => $product->user_id,
                'user_id' => \Auth::user()->id,
                'address_id' => \Auth::user()->addresses()->where('set_default', 1)->first(['id'])->id,
                'product_id' => $product->id,
                'variation' => $variation,
                'price' => $price,
                'quantity' => $request->quantity,
                'product_description' => $request->product_description,
            ]);


        }

        else{


            $checkRequestPersonaliseProduct = RequestPersonaliseProduct::create([
                'owner_id' => $product->user_id,
                'user_id' => \Auth::user()->id,
                'address_id' => \Auth::user()->addresses()->where('set_default', 1)->first(['id'])->id,
                'product_id' => $product->id,
                'variation' => $variation,
                'price' => $price,
                'quantity' => $request->quantity,
                'product_description' => $request->product_description,
            ]);


            $createAppointment = RequestAppointment::create([
                'product_id' => $product->id,
                'request_id' => $checkRequestPersonaliseProduct->id,
                'measurer_id' => null,
                'owner_id' => $product->user_id,
                'datetime' => $currentDateTime


            ]);



        }
    }

        $response = ['status' => false, 'msg' => 'Something went wrong, try again later !'];

        $user = \Auth::user();

        $club_type = ClubPointType::where('name','booking_appointments')->first();

        $get_req_points = BusinessSetting::where('type', 'club_point_booking_appointments_rate')->first();

        $club_point = new ClubPoint;
        $club_point->user_id = $user->id;
        $club_point->points = $get_req_points->value ?? 10;
        $club_point->order_id = null;
        $club_point->club_point_type_id = $club_type->id ?? 3; // for review
        $club_point->convert_status = 1;
        $club_point->save();

        wallet_payment_done($user->id, floatval($club_point->points / $get_req_points->value ?? 10), 'Club Point Convert', 'Club Point Convert');


        if(isset($request->file) && count($request->file) > 0){
            foreach($request->file as $file){
            $mimeType = $file->getClientmimeType();    // image/png
            $fileType = substr($mimeType, 0, strpos($mimeType, "/"));  // image
            $full_path = 'uploads/request-img/'.$checkRequestPersonaliseProduct->id.'/'. basename($file);
            $returnedPath = Storage::disk('local')->put($full_path, $file);

            Attachment::create([
                'model_type' => 'RequestPersonaliseProduct',
                'model_id' => $checkRequestPersonaliseProduct->id,
                'attachment_type' => $fileType,
                'attachment_path' => $returnedPath,
            ]);
            }

        }
        //
        if($product->personalizeProductTypeName->slug == "clothing" || $product->personalizeProductTypeName->slug == "shoes"){
            $response = ['status' => true, 'msg' => 'Request sent successfully'];
            return $response;
        }

        else{
            $response = ['status' => true, 'msg' => 'Appointment Created Successfully'];
            return $response;
        }


    }
}
