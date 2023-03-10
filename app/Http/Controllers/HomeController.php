<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\DefaultModelCommission;
use App\Models\Message;
use App\Models\ModelImage;
use App\Models\RequestToModel;
use App\Models\TemporaryMeasurerCommission;
use App\Models\TemporaryModelCommission;
use App\Models\Upload;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use Stevebauman\Location\Facades\Location;
use Victorybiz\GeoIPLocation\GeoIPLocation;
use Session;
use Config;
use Auth;
use Hash;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\Brand;
use App\Models\Product;
use App\Models\CustomerProduct;
use App\Models\PickupPoint;
use App\Models\CustomerPackage;
use App\Models\User;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\Order;
use App\Models\BusinessSetting;
use App\Models\Coupon;
use Cookie;
use Illuminate\Support\Str;
use App\Mail\SecondEmailVerifyMailManager;
use App\Models\AffiliateConfig;
use App\Models\Page;
use Mail;
use Illuminate\Auth\Events\PasswordReset;
use Cache;
use App\Models\RequestAppointment;
use App\Models\RequestMeasurement;
use App\Models\Address;
use App\Models\CombinedOrder;
use App\Models\MeasurerAvailablityHours;
use App\Models\OrderDetail;
use App\Models\ProductForum;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function appointments()
    {
        if(!Auth::check())
            return redirect('/');

        // $appointments = RequestAppointment::join('request_personalise_products', 'request_personalise_products.id', '=', 'request_appointments.request_id')
        // ->select('request_appointments.*')
        // ->where(['request_personalise_products.user_id' => Auth::user()->id])
        // ->paginate(10);


        $appointments = RequestAppointment::join('request_personalise_products', 'request_personalise_products.id', '=', 'request_appointments.request_id')
        ->leftJoin('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
        ->select('request_appointments.*','request_measurements.uuid as measurement_uuid')
        ->where(['request_personalise_products.user_id' => Auth::user()->id])
        ->whereNotIn('appointment_status', [6])
        ->paginate(10);


        //  dd($appointments);

        // $request_measurement = RequestMeasurement::where



        //  dd($appointments);

        return view('frontend.user.customer.request-appointments', compact('appointments'));
    }

    // this function return  appointments of customer which he created directly from measurer without seller.
    public function customer_direct_appointments_of_measurments()
    {
        if(!Auth::check())
            return redirect('/');

        $appointments = RequestAppointment::leftJoin('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
        ->select('request_appointments.*','request_measurements.uuid as measurement_uuid')
        ->where('user_id',Auth::user()->id)
        ->whereNotIn('appointment_status', [6])
        ->get();

        appointments_expire();


        return view('frontend.user.customer.direct-request-appointments', compact('appointments'));
    }


    public function completedMeasures()
    {
        if (!Auth::check())
            return redirect('/');
        if(auth()->user()->user_type == 'measurer' || auth()->user()->user_type == 'seller'){
            $appointments = RequestAppointment::join('request_personalise_products', 'request_personalise_products.id', '=', 'request_appointments.request_id')
            ->join('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
            ->select('request_appointments.*','request_measurements.uuid as measurement_uuid')
            ->where([
                'measurer_id' => Auth::user()->id,
                'appointment_status' =>  [6]
                ])
            ->paginate(10);
        }else{
            $appointments = RequestAppointment::join('request_personalise_products', 'request_personalise_products.id', '=', 'request_appointments.request_id')
            ->join('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
            ->select('request_appointments.*','request_measurements.id as measurement_id')
            ->where([
                'request_personalise_products.user_id' => Auth::user()->id,
                'appointment_status' => 6,
                ])
            ->paginate(10);
        }

        $completedMeasures = true;

        if(auth()->user()->user_type == 'seller'){
            return view('seller.requests.appointments', compact('appointments','completedMeasures'));
        }

        return view('frontend.user.customer.completed-measures', compact('appointments'));
    }


    public function completedDirectMeasures()
    {
        if (!Auth::check())
            return redirect('/');
        if(auth()->user()->user_type == 'measurer' || auth()->user()->user_type == 'seller'){
            $appointments = RequestAppointment::
            join('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
            ->select('request_appointments.*','request_measurements.uuid as measurement_uuid')
            ->where([
                'measurer_id' => Auth::user()->id,
                'appointment_status' =>  [6]
                ])
            ->whereNotNull('user_id')
            ->paginate(10);
        }else{
            $appointments = RequestAppointment::
            join('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
            ->select('request_appointments.*','request_measurements.uuid as measurement_uuid')
            ->where([
                'user_id' => Auth::user()->id,
                'appointment_status' => 6,
                ])
            ->paginate(10);
        }

        $completedMeasures = true;

        // if(auth()->user()->user_type == 'seller'){
        //     return view('seller.requests.appointments', compact('appointments','completedMeasures'));
        // }
        // dd($appointments);
        return view('frontend.user.customer.direct-completed-measures', compact('appointments'));
    }
    public function measurer_appointments()
    {
        if(!Auth::check())
            return redirect('/');

        $appointments = RequestAppointment::join('request_personalise_products', 'request_personalise_products.id', '=', 'request_appointments.request_id')
        ->leftJoin('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
        ->select('request_appointments.*','request_measurements.uuid as measurement_uuid')
        ->where('measurer_id',Auth::user()->id)
        ->whereNotIn('appointment_status', [6])
        ->paginate(10);

        // dd($appointments);


        // $appointments = RequestAppointment::where(['measurer_id' => Auth::user()->id])->paginate(10);

        //  dd($appointments);

        appointments_expire();

        if(auth()->user()->user_type == 'seller'){
            return view('seller.requests.appointments', compact('appointments'));
        }
        return view('frontend.user.measurer.request-appointments', compact('appointments'));
    }
    public function direct_measurer_appointments()
    {
        if(!Auth::check())
            return redirect('/');

        $appointments = RequestAppointment::leftJoin('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
        ->select('request_appointments.*','request_measurements.uuid as measurement_uuid')
        ->where('measurer_id',Auth::user()->id)
        ->whereNotNull('user_id')
        ->whereNotIn('appointment_status', [6])
        ->get();

        appointments_expire();


        return view('frontend.user.measurer.direct-request-appointments', compact('appointments'));
    }






    public function appointments_video($id) {

        $appointment_id = $id;

        $request_measurement =  RequestMeasurement::where('appointment_id',$appointment_id)->first();

        return view('frontend.user.measurer.measurer-video-rec',compact('appointment_id','request_measurement'));
    }


    public function appointments_video_customer($id) {

        $appointment_id = $id;

        $request_measurement =  RequestMeasurement::where('appointment_id',$appointment_id)->first();

        return view('frontend.user.customer.customer-video-rec',compact('appointment_id','request_measurement'));
    }



    public function appointments_video_delete($id) {

        $appointment_id = $id;

        $request_measurement =  RequestMeasurement::where('appointment_id',$appointment_id)->first();
        $request_measurement->measurement_video = '';
        $request_measurement->save();

        if ($request_measurement->measurement_video == '') {
            flash(translate('Video Has Been Deleted Successfully!'))->success();
            return redirect()->back();
            // return view('frontend.user.measurer.measurer-video-rec',compact('appointment_id','request_measurement'));

        }


    }




    public function appointments_video_post(Request $request) {


            if($request->file('measurement_video')){

                $path = $request->measurement_video->store('/videos');

                $request_measurement =  RequestMeasurement::where('appointment_id', $request->appointment_id)->first();

                if($request_measurement){
                     $request_measurement->measurement_video = $path;
                     $request_measurement->save();
                }else{
                   $request_measurement =  RequestMeasurement::create([
                        'appointment_id' => $request->appointment_id,
                        'measurement_video' => $path,
                         'uuid' => Str::uuid()->toString()
                        ]);
                }

                // $request_measurement = RequestMeasurement::updateOrCreate(['appointment_id' => $request->appointment_id],[
                //     'appointment_id' => $request->appointment_id,
                //     'measurement_video' => $path,
                //     'uuid' => Str::uuid()->toString()
                // ]);

                // $request_measurement->appointment_id = $request->appointment_id;
                // $request_measurement->measurement_video = $path;
                // $request_measurement->save();


                return \Response::json('Successfully Uploaded', 200);
            }





        // return view('frontend.user.measurer.measurer-video-rec');
    }


    public function measurer_appointment_accept_reject($id, $status)
    {
        $status = decrypt($status);
        $appointmentStatus = 0;
        $msg = '';
        if($status == 'accept') {
            $appointmentStatus = 1;
            $msg = 'You have accepted appointment successfully';
        }
        elseif($status == 'reject') {
            $appointmentStatus = 5;
            $msg = 'You have rejected appointment successfully';
        }
        $appointment = RequestAppointment::where(['measurer_id' => Auth::user()->id, 'id' => decrypt($id)]);
        if($appointment->count() > 0) {
            $appointment->update([
                'appointment_status' => $appointmentStatus,
            ]);


            // return redirect()->route('measurer-appointments')->with('success_message', $msg);
            return redirect()->back()->with('success_message', $msg);
        }
        else {
            return redirect('/');
        }
    }


    public function mark_as_complete($id, Request $request)
    {
    //    dd($id);
        $appointment = RequestAppointment::where('measurer_id', Auth::user()->id)->where('id',$id)->first();
        // dd($appointment);
        if($appointment->count() > 0) {
            $appointment->update([
                'appointment_status' => 6,
            ]);


            // return redirect()->route('measurer-appointments')->with('success_message', 'Mark Completed Successfully');
            return redirect()->back()->with('success_message', 'Mark Completed Successfully');
        }
        else {
            return redirect('/');
        }
    }
    public function measurer_measurement($id, Request $request)
    {

        if($request->isMethod('post')){
            $data = $request->all();

            $data['appointment_id'] = decrypt($id);
//            $data['measurements_image'] = $data['measurements_image_h'];
//            if ($request->measurements_image) {
//                $data['measurements_image'] = upload_image($request->measurements_image);
//                remove_image($data['measurements_image_h']);
//            }
//            if($data['measurements_image'] == '' && $data['measurements_text'] == '') {
//                return [
//                    'message' => 'You must enter a single field!',
//                    'status' => false,
//                ];
//            }
//            else {


    \DB::transaction(function () use ($data) {
            $measurement = RequestMeasurement::updateOrCreate(['appointment_id' => $data['appointment_id']],$data);
            $measurement->uuid = Str::uuid()->toString();
            $measurement->save();
                    if($measurement) {
                        $requestData = RequestAppointment::
                        join('request_personalise_products', 'request_personalise_products.id', '=', 'request_appointments.request_id')
                        ->join('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
                        ->join('addresses', 'request_personalise_products.address_id', '=', 'addresses.id')
                        ->select([
                            'request_personalise_products.id AS request_id',
                            'request_personalise_products.owner_id AS seller_id',
                            'request_personalise_products.user_id AS customer_id',
                            'request_personalise_products.product_id',
                            'request_personalise_products.quantity',
                            'request_personalise_products.variation',
                            'request_personalise_products.price',
                            'request_personalise_products.address_id',

                            'request_appointments.id AS appointment_id',
                            'request_appointments.datetime AS appointment_datetime',
                            'request_appointments.measurer_id',
                            'request_appointments.appointment_status',

                            'request_measurements.id AS measurement_id',
//                            'request_measurements.measurements_text',
//                            'request_measurements.measurements_image',


                        ])
                        ->where(['request_appointments.id' => $data['appointment_id']])->first();
                        $shippingAddress = [];
                        if ($requestData->count()) {
                            $address = Address::findOrfail($requestData->address_id);
                            $customer = User::findOrfail($requestData->customer_id);
                            $product = Product::findOrfail($requestData->product_id);
                            $shippingAddress['name']        = $customer->name;
                            $shippingAddress['email']       = $customer->email;
                            $shippingAddress['address']     = $address->address;
                            $shippingAddress['country']     = $address->country->name;
                            $shippingAddress['state']       = $address->state->name;
                            $shippingAddress['city']        = $address->city->name;
                            $shippingAddress['postal_code'] = $address->postal_code;
                            $shippingAddress['phone']       = $address->phone;
                            if ($address->latitude || $address->longitude) {
                                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
                            }
                            $order = Order::where([
                                'measurement_id' => $requestData->measurement_id,
                                'appointment_id' => $requestData->appointment_id,
                                'request_id' => $requestData->request_id,
                                'user_id' => $requestData->customer_id,
                                'seller_id' => $requestData->seller_id,
                            ]);
                            if($order->count() > 0){
                                return;
                            }
                            $combined_order = new CombinedOrder;
                            $combined_order->user_id = $requestData->customer_id;
                            $combined_order->shipping_address = json_encode($shippingAddress);
                            $combined_order->save();

                            $order = new Order;
                            $order->measurement_id = $requestData->measurement_id;
                            $order->appointment_id = $requestData->appointment_id;
                            $order->request_id = $requestData->request_id;

                            $order->combined_order_id = $combined_order->id;
                            $order->user_id = $requestData->customer_id;
                            $order->seller_id = $requestData->seller_id;
                            $order->shipping_address = $combined_order->shipping_address;

                            $order->additional_info = '';
                            $order->shipping_type = 'home_delivery';
                            $order->delivery_status = 'confirmed';
                            $order->payment_type = 'pending';
                            $order->payment_status = 'unpaid';
                            $order->grand_total = $requestData->price;
                            $order->delivery_viewed = '0';
                            $order->payment_status_viewed = '0';
                            $order->code = date('Ymd-His') . rand(10, 99);
                            $order->date = strtotime('now');
                            $order->save();

                            $order_detail = new OrderDetail;
                            $order_detail->order_id = $order->id;
                            $order_detail->seller_id = $product->user_id;
                            $order_detail->product_id = $product->id;
                            $order_detail->variation = $requestData->variation;
                            $order_detail->price = $requestData->price;
                            $order_detail->tax = 0;
                            $order_detail->shipping_type = 'home_delivery';
                            $order_detail->shipping_cost = 0;


                            $order_detail->quantity = $requestData->quantity;
                            $order_detail->save();

                            // $product->num_of_sale += $requestData->quantity;
                            // $product->save();


                        }
                    }

                });
                return [
                    'message' => 'Measurements updated successfully!',
                    'status' => true,
                ];
//            }
        }
        else {
            $appointment = RequestAppointment::find(decrypt($id));
            if($appointment){
                $requestMeasurement = RequestMeasurement::where(['appointment_id' => $appointment->id])->first();
                // dd($requestMeasurement);
                return ['data' => $requestMeasurement];
            }
        }
    }

    public function direct_measurer_measurement($id, Request $request)
    {
            //    dd(decrypt($id));

        if($request->isMethod('post')){
            $data = $request->all();

            $data['uuid'] = Str::uuid()->toString();

            $data['appointment_id'] = decrypt($id);
                \DB::transaction(function () use ($data) {
                    $measurement = RequestMeasurement::updateOrCreate(['appointment_id' => $data['appointment_id']],$data);
                });
                return [
                    'message' => 'Measurements updated successfully!',
                    'status' => true,
                ];
//            }
        }
        else {
            $appointment = RequestAppointment::find(decrypt($id));
            if($appointment){
                $requestMeasurement = RequestMeasurement::where(['appointment_id' => $appointment->id])->first();
                // dd($requestMeasurement);
                return ['data' => $requestMeasurement];
            }
        }
    }

    public function order_create(Request $request)
    {

            $data = $request->all();

            // dd($data['product_id']);

             $requestData = RequestAppointment::
             join('request_personalise_products', 'request_personalise_products.id', '=', 'request_appointments.request_id')
            //  ->join('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
             ->join('addresses', 'request_personalise_products.address_id', '=', 'addresses.id')
             ->select([
                 'request_personalise_products.id AS request_id',
                 'request_personalise_products.owner_id AS seller_id',
                 'request_personalise_products.user_id AS customer_id',
                 'request_personalise_products.product_id',
                 'request_personalise_products.quantity',
                 'request_personalise_products.variation',
                 'request_personalise_products.price',
                 'request_personalise_products.address_id',

                 'request_appointments.id AS appointment_id',
                 'request_appointments.datetime AS appointment_datetime',
                 'request_appointments.measurer_id',
                 'request_appointments.appointment_status',

                //  'request_measurements.id AS measurement_id',
                //  'request_measurements.measurements_text',
                //  'request_measurements.measurements_image',


             ])
//             ->where(['request_appointments.product_id' => $data['product_id']])
                 ->first();

                $shippingAddress = [];
//                if ($requestData->count()) {
                if (isset($requestData)) {


                    $address = Address::findOrfail($requestData->address_id);
                    $customer = User::findOrfail($requestData->customer_id);
                    $product = Product::findOrfail($requestData->product_id);
                    $shippingAddress['name']        = $customer->name;
                    $shippingAddress['email']       = $customer->email;
                    $shippingAddress['address']     = $address->address;
                    $shippingAddress['country']     = $address->country->name;
                    $shippingAddress['state']       = $address->state->name;
                    $shippingAddress['city']        = $address->city->name;
                    $shippingAddress['postal_code'] = $address->postal_code;
                    $shippingAddress['phone']       = $address->phone;
                    if ($address->latitude || $address->longitude) {
                        $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
                    }
                    $order = Order::where([
                        'measurement_id' => 0,
                        'appointment_id' => $requestData->appointment_id,
                        'request_id' => $requestData->request_id,
                        'user_id' => $requestData->customer_id,
                        'seller_id' => $requestData->seller_id,
                    ]);
                    // dd($order->count());
                    // if($order->count() > 0){
                    //     return;
                    // }

                    $combined_order = new CombinedOrder;
                    $combined_order->user_id = $requestData->customer_id;
                    $combined_order->shipping_address = json_encode($shippingAddress);
                    $combined_order->save();

                    $order = new Order;
                    $order->measurement_id = 0;
                    $order->appointment_id = $requestData->appointment_id;
                    $order->request_id = $requestData->request_id;

                    $order->combined_order_id = $combined_order->id;
                    $order->user_id = $requestData->customer_id;
                    $order->seller_id = $requestData->seller_id;
                    $order->shipping_address = $combined_order->shipping_address;

                    $order->additional_info = '';
                    $order->shipping_type = 'home_delivery';
                    $order->delivery_status = 'confirmed';
                    $order->payment_type = 'pending';
                    $order->payment_status = 'unpaid';
                    $order->grand_total = $requestData->price;
                    $order->delivery_viewed = '0';
                    $order->payment_status_viewed = '0';
                    $order->code = date('Ymd-His') . rand(10, 99);
                    $order->date = strtotime('now');
                    $order->save();

                    $order_detail = new OrderDetail;
                    $order_detail->order_id = $order->id;
                    $order_detail->seller_id = $product->user_id;
                    $order_detail->product_id = $product->id;
                    $order_detail->variation = $requestData->variation;
                    $order_detail->price = $requestData->price;
                    $order_detail->tax = 0;
                    $order_detail->shipping_type = 'home_delivery';
                    $order_detail->shipping_cost = 0;


                    $order_detail->quantity = $requestData->quantity;
                    $order_detail->save();

                    flash(translate('Order Was Placed Successfully!'))->success();
                    return redirect()->back();
                }else{
                    flash(translate('Order Fail Measurement Not Found!'))->error();
                    return redirect()->back();
                }


    }
//    public function measurer_measurement_bkp($id, Request $request)
//    {
//        if($request->isMethod('post')){
//            $data = $request->all();
//
//            $data['appointment_id'] = decrypt($id);
//            $data['measurements_image'] = $data['measurements_image_h'];
//            if ($request->measurements_image) {
//                $data['measurements_image'] = upload_image($request->measurements_image);
//                remove_image($data['measurements_image_h']);
//            }
//            if($data['measurements_image'] == '' && $data['measurements_text'] == '') {
//                return [
//                    'message' => 'You must enter a single field!',
//                    'status' => false,
//                ];
//            }
//            else {
//                \DB::transaction(function () use ($data) {
//                    $measurement = RequestMeasurement::updateOrCreate(['appointment_id' => $data['appointment_id']],$data);
//                    if($measurement) {
//
//                        $requestData = RequestAppointment::
//                        join('request_personalise_products', 'request_personalise_products.id', '=', 'request_appointments.request_id')
//                            ->join('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
//                            ->join('addresses', 'request_personalise_products.address_id', '=', 'addresses.id')
//                            ->select([
//                                'request_personalise_products.id AS request_id',
//                                'request_personalise_products.owner_id AS seller_id',
//                                'request_personalise_products.user_id AS customer_id',
//                                'request_personalise_products.product_id',
//                                'request_personalise_products.quantity',
//                                'request_personalise_products.variation',
//                                'request_personalise_products.price',
//                                'request_personalise_products.address_id',
//
//                                'request_appointments.id AS appointment_id',
//                                'request_appointments.datetime AS appointment_datetime',
//                                'request_appointments.measurer_id',
//                                'request_appointments.appointment_status',
//
//                                'request_measurements.id AS measurement_id',
//                                'request_measurements.measurements_text',
//                                'request_measurements.measurements_image',
//
//
//                            ])
//                            ->where(['request_appointments.id' => $data['appointment_id']])->first();
//                        $shippingAddress = [];
//                        if ($requestData->count()) {
//                            $address = Address::findOrfail($requestData->address_id);
//                            $customer = User::findOrfail($requestData->customer_id);
//                            $product = Product::findOrfail($requestData->product_id);
//                            $shippingAddress['name']        = $customer->name;
//                            $shippingAddress['email']       = $customer->email;
//                            $shippingAddress['address']     = $address->address;
//                            $shippingAddress['country']     = $address->country->name;
//                            $shippingAddress['state']       = $address->state->name;
//                            $shippingAddress['city']        = $address->city->name;
//                            $shippingAddress['postal_code'] = $address->postal_code;
//                            $shippingAddress['phone']       = $address->phone;
//                            if ($address->latitude || $address->longitude) {
//                                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
//                            }
//                            $order = Order::where([
//                                'measurement_id' => $requestData->measurement_id,
//                                'appointment_id' => $requestData->appointment_id,
//                                'request_id' => $requestData->request_id,
//                                'user_id' => $requestData->customer_id,
//                                'seller_id' => $requestData->seller_id,
//                            ]);
//                            if($order->count() > 0){
//                                return;
//                            }
//                            $combined_order = new CombinedOrder;
//                            $combined_order->user_id = $requestData->customer_id;
//                            $combined_order->shipping_address = json_encode($shippingAddress);
//                            $combined_order->save();
//
//                            $order = new Order;
//                            $order->measurement_id = $requestData->measurement_id;
//                            $order->appointment_id = $requestData->appointment_id;
//                            $order->request_id = $requestData->request_id;
//
//                            $order->combined_order_id = $combined_order->id;
//                            $order->user_id = $requestData->customer_id;
//                            $order->seller_id = $requestData->seller_id;
//                            $order->shipping_address = $combined_order->shipping_address;
//
//                            $order->additional_info = '';
//                            $order->shipping_type = 'home_delivery';
//                            $order->delivery_status = 'confirmed';
//                            $order->payment_type = 'pending';
//                            $order->payment_status = 'unpaid';
//                            $order->grand_total = $requestData->price;
//                            $order->delivery_viewed = '0';
//                            $order->payment_status_viewed = '0';
//                            $order->code = date('Ymd-His') . rand(10, 99);
//                            $order->date = strtotime('now');
//                            $order->save();
//
//                            $order_detail = new OrderDetail;
//                            $order_detail->order_id = $order->id;
//                            $order_detail->seller_id = $product->user_id;
//                            $order_detail->product_id = $product->id;
//                            $order_detail->variation = $requestData->variation;
//                            $order_detail->price = $requestData->price;
//                            $order_detail->tax = 0;
//                            $order_detail->shipping_type = 'home_delivery';
//                            $order_detail->shipping_cost = 0;
//
//
//                            $order_detail->quantity = $requestData->quantity;
//                            $order_detail->save();
//
//                            // $product->num_of_sale += $requestData->quantity;
//                            // $product->save();
//
//
//                        }
//                    }
//
//                });
//                return [
//                    'message' => 'Measurements updated successfully!',
//                    'status' => true,
//                ];
//            }
//        }
//        else {
//            $appointment = RequestAppointment::find(decrypt($id));
//            if($appointment){
//                $requestMeasurement = RequestMeasurement::where(['appointment_id' => $appointment->id])->first();
//                // dd($requestMeasurement);
//                return ['data' => $requestMeasurement];
//            }
//        }
//    }

    public function index()
    {

        // $ip = '103.239.147.187'; //For static IP address get
        // $ip = request()->ip(); //Dynamic IP address get
        // $data = Location::get($ip);
        // dd($data->countryName);
        //  $ip =  GeoIPLocation::getIP();




        $featured_categories = Cache::rememberForever('featured_categories', function () {
            return Category::where('featured', 1)->get();
        });


        $todays_deal_products = Cache::rememberForever('todays_deal_products', function () {
            return filter_products(Product::where('published', 1)->where('todays_deal', '1'))->get();
        });

        $crazy_sunday_products = Cache::remember('crazy_sunday_products',3600, function () {
            return filter_products(Product::where('published', 1)->where('is_crazy_sunday', 1))->get();
        });

        $personalise_products = Cache::remember('personalise_products',3600, function () {
            return filter_products(Product::where('published', 1)->where('is_personalise', 1))->get();
        });

        $newest_products = Cache::remember('newest_products', 3600, function () {
            return filter_products(Product::latest())->limit(12)->get();
        });

        $latest_products = Cache::remember('latest_products', 3600, function () {
            return filter_products(Product::orderBy('created_at','desc'))->take(5)->get();
        });


        return view('frontend.index', compact('featured_categories', 'todays_deal_products','crazy_sunday_products','personalise_products', 'newest_products','latest_products'));
    }

    public function login()
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        return view('frontend.user_login');
    }

    public function registration(Request $request)
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        if($request->has('referral_code') && addon_is_activated('affiliate_system')) {
            try {
                $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }

                Cookie::queue('referral_code', $request->referral_code, $cookie_minute);
                $referred_by_user = User::where('referral_code', $request->referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            } catch (\Exception $e) {

            }
        }
        return view('frontend.user_registration');
    }

    public function cart_login(Request $request)
    {
        $user = null;
        if($request->get('phone') != null){
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('phone', "+{$request['country_code']}{$request['phone']}")->first();
        }
        elseif($request->get('email') != null){
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
        }

        if($user != null){
            if(Hash::check($request->password, $user->password)){
                if($request->has('remember')){
                    auth()->login($user, true);
                }
                else{
                    auth()->login($user, false);
                }
            }
            else {
                flash(translate('Invalid email or password!'))->warning();
            }
        }
        else{
            flash(translate('Invalid email or password!'))->warning();
        }
        return back();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if(Auth::user()->user_type == 'seller'){
            return redirect()->route('seller.dashboard');
        }
        elseif(Auth::user()->user_type == 'customer'){
            return view('frontend.user.customer.dashboard');
        }
        elseif(Auth::user()->user_type == 'delivery_boy'){
            return view('delivery_boys.frontend.dashboard');
        }
        elseif(Auth::user()->user_type == 'measurer'){
            return view('frontend.user.customer.dashboard');
        }
        elseif(Auth::user()->user_type == 'model'){
            return view('frontend.user.customer.dashboard');
        }
        else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        if(Auth::user()->user_type == 'seller'){
            return redirect()->route('seller.profile.index');
        }
        elseif(Auth::user()->user_type == 'delivery_boy'){
            return view('delivery_boys.frontend.profile');
        }
        else{
            return view('frontend.user.profile');
        }
    }

    public function userProfileUpdate(Request $request)
    {
        if(env('DEMO_MODE') == 'On'){
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }

        $user->avatar_original = $request->photo;
        $user->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }

    public function flash_deal_details($slug)
    {
        $flash_deal = FlashDeal::where('slug', $slug)->first();
        if($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }

    public function load_featured_section(){
        return view('frontend.partials.featured_products_section');
    }

    public function load_best_selling_section(){
        return view('frontend.partials.best_selling_section');
    }

    public function load_auction_products_section(){
        if(!addon_is_activated('auction')){
            return;
        }
        return view('auction.frontend.auction_products_section');
    }

    public function load_home_categories_section(){
        return view('frontend.partials.home_categories_section');
    }

    public function load_best_sellers_section(){
        return view('frontend.partials.best_sellers_section');
    }

    public function trackOrder(Request $request)
    {
        if($request->has('order_code')){
            $order = Order::where('code', $request->order_code)->first();
            if($order != null){
                return view('frontend.track_order', compact('order'));
            }
        }
        return view('frontend.track_order');
    }

    public function product(Request $request, $slug)
    {

        if (Auth::user()) {
            $detailedProduct  = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop')->where('auction_product', 0)->where('slug', $slug)->where('approved', 1)->first();


                $productForum  = ProductForum::with('user','seller')->where('product_id',$detailedProduct->id)->orderBy('id','desc')->get();

                // dd($productForum);
                $appointments = RequestAppointment::join('request_personalise_products', 'request_personalise_products.id', '=', 'request_appointments.request_id')
                ->select('request_appointments.*')
                ->where(['request_personalise_products.user_id' => Auth::user()->id])
                ->paginate(10);

                $appointment = RequestAppointment::with('product')->where('owner_id',Auth::user()->id)->first();



            if($detailedProduct != null && $detailedProduct->published){
                if($request->has('product_referral_code') && addon_is_activated('affiliate_system')) {

                    $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                    $cookie_minute = 30 * 24;
                    if($affiliate_validation_time) {
                        $cookie_minute = $affiliate_validation_time->value * 60;
                    }
                    Cookie::queue('product_referral_code', $request->product_referral_code, $cookie_minute);
                    Cookie::queue('referred_product_id', $detailedProduct->id, $cookie_minute);

                    $referred_by_user = User::where('referral_code', $request->product_referral_code)->first();

                    $affiliateController = new AffiliateController;
                    $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
                }
                if($detailedProduct->digital == 1){
                    return view('frontend.digital_product_details', compact('detailedProduct','productForum'));
                }
                else {
                    return view('frontend.product_details', compact('detailedProduct','appointments','appointment','productForum'));
                }
            }
            abort(404);

        } else {
            $detailedProduct  = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop')->where('auction_product', 0)->where('slug', $slug)->where('approved', 1)->first();
            $productForum  = ProductForum::with('user','seller')->where('product_id',$detailedProduct->id)->orderBy('id','desc')->get();

            return view('frontend.product_details', compact('detailedProduct','productForum'));

        }







    }

    public function shop($slug)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if($shop!=null){
            if ($shop->verification_status != 0){
                return view('frontend.seller_shop', compact('shop'));
            }
            else{
                return view('frontend.seller_shop_without_verification', compact('shop'));
            }
        }
        abort(404);
    }

    public function filter_shop($slug, $type)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if($shop!=null && $type != null){
            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function all_categories(Request $request)
    {
        $categories = Category::where('level', 0)->orderBy('order_level', 'desc')->get();
        return view('frontend.all_category', compact('categories'));
    }

    public function all_brands(Request $request)
    {
        $categories = Category::all();
        return view('frontend.all_brand', compact('categories'));
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if(is_array($request->top_categories) && in_array($category->id, $request->top_categories)){
                $category->top = 1;
                $category->save();
            }
            else{
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if(is_array($request->top_brands) && in_array($brand->id, $request->top_brands)){
                $brand->top = 1;
                $brand->save();
            }
            else{
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(translate('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;
        $tax = 0;
        $max_limit = 0;

        if($request->has('color')){
            $str = $request['color'];
        }

        if(json_decode($product->choice_options) != null){
            foreach (json_decode($product->choice_options) as $key => $choice) {
                if($str != null){
                    $str .= '-'.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
                else{
                    $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
            }
        }

        $product_stock = $product->stocks->where('variant', $str)->first();

        $price = $product_stock->price;


        if($product->wholesale_product){
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
            if($wholesalePrice){
                $price = $wholesalePrice->price;
            }
        }

        $quantity = $product_stock->qty;
        $max_limit = $product_stock->qty;

        if($quantity >= 1 && $product->min_qty <= $quantity){
            $in_stock = 1;
        }else{
            $in_stock = 0;
        }

        //Product Stock Visibility
        if($product->stock_visibility_state == 'text') {
            if($quantity >= 1 && $product->min_qty < $quantity){
                $quantity = translate('In Stock');
            }else{
                $quantity = translate('Out Of Stock');
            }
        }

        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        }
        elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }
        }

        // taxes
        foreach ($product->taxes as $product_tax) {
            if($product_tax->tax_type == 'percent'){
                $tax += ($price * $product_tax->tax) / 100;
            }
            elseif($product_tax->tax_type == 'amount'){
                $tax += $product_tax->tax;
            }
        }

        $price += $tax;

        return array(
            'price' => single_price($price*$request->quantity),
            'quantity' => $quantity,
            'digital' => $product->digital,
            'variation' => $str,
            'max_limit' => $max_limit,
            'in_stock' => $in_stock
        );
    }

    public function sellerpolicy(){
        $page =  Page::where('type', 'seller_policy_page')->first();
        return view("frontend.policies.sellerpolicy", compact('page'));
    }

    public function returnpolicy(){
        $page =  Page::where('type', 'return_policy_page')->first();
        return view("frontend.policies.returnpolicy", compact('page'));
    }

    public function supportpolicy(){
        $page =  Page::where('type', 'support_policy_page')->first();
        return view("frontend.policies.supportpolicy", compact('page'));
    }

    public function terms(){
        $page =  Page::where('type', 'terms_conditions_page')->first();
        return view("frontend.policies.terms", compact('page'));
    }

    public function privacypolicy(){
        $page =  Page::where('type', 'privacy_policy_page')->first();
        return view("frontend.policies.privacypolicy", compact('page'));
    }

    public function get_pick_up_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request){
        $category = Category::findOrFail($request->id);
        return view('frontend.partials.category_elements', compact('category'));
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.user.customer_packages_lists', compact('customer_packages'));
    }


    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if(isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = 'Email already exists!';
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }


    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if(isUnique($email)) {
            $this->send_email_change_verification_mail($request, $email);
            flash(translate('A verification mail has been sent to the mail you provided us with.'))->success();
            return back();
        }

        flash(translate('Email already exists!'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = 'Email Verification';
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = 'Verify your account';
        $array['link'] = route('email_change.callback').'?new_email_verificiation_code='.$verification_code.'&email='.$email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = "Email Second";

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = translate("Your verification mail has been Sent to your email.");

        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request){
        if($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param =  $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if($user != null) {

                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('Email Changed successfully'))->success();
                if($user->user_type == 'seller') {
                    return redirect()->route('seller.dashboard');
                }
                return redirect()->route('dashboard');
            }
        }

        flash(translate('Email was not verified. Please resend your mail!'))->error();
        return redirect()->route('dashboard');

    }

    public function reset_password_with_code(Request $request){
        if (($user = User::where('email', $request->email)->where('verification_code', $request->code)->first()) != null) {
            if($request->password == $request->password_confirmation){
                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

                flash(translate('Password updated successfully'))->success();

                if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')
                {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            }
            else {
                flash("Password and confirm password didn't match")->warning();
                return redirect()->route('password.request');
            }
        }
        else {
            flash("Verification code mismatch")->error();
            return redirect()->route('password.request');
        }
    }


    public function all_flash_deals() {
        $today = strtotime(date('Y-m-d H:i:s'));

        $data['all_flash_deals'] = FlashDeal::where('status', 1)
                ->where('start_date', "<=", $today)
                ->where('end_date', ">", $today)
                ->orderBy('created_at', 'desc')
                ->get();

        return view("frontend.flash_deal.all_flash_deal_list", $data);
    }

    public function all_seller(Request $request) {
        $shops = Shop::whereIn('user_id', verified_sellers_id())
                ->paginate(15);

        return view('frontend.shop_listing', compact('shops'));
    }

    public function all_coupons(Request $request) {
        $coupons = Coupon::where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->paginate(15);
        return view('frontend.coupons', compact('coupons'));
    }

    public function inhouse_products(Request $request) {
        $products = filter_products(Product::where('added_by', 'admin'))->with('taxes')->paginate(12)->appends(request()->query());
        return view('frontend.inhouse_products', compact('products'));
    }

    public function show_user_measurement($uuid) {
        $request_measurement = RequestMeasurement::with('appointment')->where('uuid',$uuid)->first();


    //    dd($request_measurement);

    // $request_measurement->appointment->product->personalizeProductTypeName->slug

       if(isset($request_measurement)){
        return view('frontend.user.measurer.user-measurements', compact('request_measurement'));
       }

       else{
        abort(404);
       }





    }



    public function measurer_availablity() {

        // dd('asd');

        $measurer_avaliablity = MeasurerAvailablityHours::where('measurer_id',Auth::user()->id)->get();

        //  dd($measurer_avaliablity->isEmpty());

        if(auth()->user()->user_type == 'seller'){
            return view('backend.sellers.seller_as_measurer_commission.measurer-availablity',compact('measurer_avaliablity'));
        }else{
            return view('frontend.user.measurer.measurer-availablity',compact('measurer_avaliablity'));
        }

    }

    public function measurer_availablity_save(Request $request) {

    //  dd($request->all());


    if (isset($request->days)) {

        $check_measurer_exists = MeasurerAvailablityHours::where('measurer_id',Auth::user()->id)->delete();

        foreach($request->days as $key =>$value){
            $data = array(
                            'days'=>$value,
                            'from_time'=>$request->from [$key],
                            'to_time'=>$request->to [$key],
                            'measurer_id'=>Auth::user()->id,

                );
                MeasurerAvailablityHours::insert($data);
        }


        flash("Hours set successfully")->success();
        return redirect()->back();

    }


    //   return view('frontend.user.measurer.measurer-availablity');

    }


    public function searchMeasurment(Request $request)
    {

        $validated = $request->validate(
        ['uuid' => 'required'],
        ['uuid.required' => 'Id is required']);

        $request_measurement = RequestMeasurement::where('uuid', $request->uuid)->first();

        if (isset($request_measurement)) {
            return redirect()->route('show_user_measurement', [$request->uuid]);
        } else {
            abort(404);
        }



        // dd($request->all());
    }


    public function showNewLabelOnSidebar()
    {

        // Query For direct measurments
        $directAppointments = RequestAppointment::where('measurer_id',Auth::user()->id)
        ->whereNotNull('user_id')
        ->whereNotIn('appointment_status', [5,6])
        ->get();

        $direct_new_measurments = [];
        $today = date('Y-m-d H:i:s');

        foreach ($directAppointments as $key => $appointment) {

            if(strtotime($today) < strtotime($appointment->created_at->modify('+1 day'))){
                array_push($direct_new_measurments,date_format(date_create($appointment->created_at),"Y-m-d"));
            }
        }
        //

        // query for indirect appointment

        $indirectAppointments = RequestAppointment::join('request_personalise_products', 'request_personalise_products.id', '=', 'request_appointments.request_id')
        ->leftJoin('request_measurements', 'request_measurements.appointment_id', '=', 'request_appointments.id')
        ->select('request_appointments.*','request_measurements.uuid as measurement_uuid')
        ->where('measurer_id',Auth::user()->id)
        ->whereNotIn('appointment_status', [5,6])
        ->get();

        $indirect_new_measurments = [];

        foreach ($indirectAppointments as $key => $appointment) {
            if(strtotime($today) < strtotime($appointment->created_at->modify('+1 day'))){
                array_push($indirect_new_measurments,date_format(date_create($appointment->created_at),"Y-m-d"));
            }
        }


        return response()->json([
            'direct_measurments_count' => count($direct_new_measurments),
            'indirect_measurments_count' => count($indirect_new_measurments)
        ]);





    }


    public function makeLastLoginAtColumnNull()
    {
        $user = User::find(auth()->id());
        if(!is_null($user->last_login_at)){ // cheching "is last_login_time column not null"
            $minutes = (Carbon::now()->diffInSeconds($user->last_login_at) / 60); // get spended minutes count
            $user->spended_minutes_on_site += $minutes; // add minutes count into the existing spended minutes count
        }
        $user->last_login_at = null;
        $user->save();
        return;
    }


    public function model_gallery()
    {
        $imagesId = ModelImage::where('model_id',auth()->id())->pluck('uploaded_image_id'); // get images id
        $imagesPath = Upload::whereIn('id', $imagesId)->pluck('file_name'); // get images path
        return view('frontend.user.model.model-gallery',compact('imagesPath'));
    }

    public function model_upload_image(Request $request)
    {
        $validator =    $request->validate([
            'photo' => ['required']
        ]);

        $model_image = new ModelImage;
        $model_image->model_id = auth()->id();
        $model_image->uploaded_image_id = $request->photo;
        $model_image->save();
        return redirect()->back();
    }

    public function model_list()
    {
        $models = User::where('user_type', 'model')->paginate();
        // dd($models->AvatarImage);
        return view('seller.model.model-list',compact('models'));
    }

    public function single_model_gallery($id)
    {
        $imagesId = ModelImage::where('model_id',$id)->pluck('uploaded_image_id'); // get images id
        $imagesPath = Upload::whereIn('id', $imagesId)->pluck('file_name'); // get images path

        return view('seller.model.single-model-gallery',compact('imagesPath'));
    }

    public function model_conversations_create($model_id)
    {
     
        $model_id = decrypt($model_id);
     
        if (Auth::check()) {
            $model = User::findOrFail($model_id);
            // dd($model);
            $curUser = Auth::user();
            if ($model) {
                $conversation = Conversation::where(['sender_id' => $curUser->id, 'receiver_id' => $model_id])->first();
                if($conversation) {
                    return redirect()->route('seller.model_conversations',['conversation_id' => encrypt($conversation->id),'model_id' => $model_id]);
                }
                else {
                    $conversation = Conversation::create(['sender_id' => $curUser->id, 'receiver_id' => $model_id, 'sender_viewed' => 0, 'receiver_viewed' => 0]);
                    if ($conversation) {
                        return redirect()->route('seller.model_conversations',['conversation_id' => encrypt($conversation->id),'model_id' => $model_id]);
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

    public function model_conversations(Request $request ,$conversation_id,$model_id)
    {
        // dd($model_id);
        // $conversation = Conversation::findOrFail(decrypt($conversation_id));

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
            $conversation = Conversation::findOrFail(decrypt($conversation_id));

            if ($conversation->sender_id == Auth::user()->id) {
                $conversation->sender_viewed = 1;
            }
            elseif($conversation->receiver_id == Auth::user()->id) {
                $conversation->receiver_viewed = 1;
            }
            $conversation->save();

            $model = User::find($model_id);
            $commission = TemporaryModelCommission::where('model_id', $model_id)->where('seller_id', auth()->id())->first();
            if(!$commission){
                if (isset($model->defaultModelCommission)) {
                    $commission = $model->defaultModelCommission->model_commission;
                }else{
                    $commission = 0;
                }
            }else{
                $commission = $commission->commission;
            }
            // return view('seller.requests.conversations', compact('conversation','request_personalize_product','measurer_avaliablity','measurer','commission'));
            return view('seller.model.conversation.conversation',compact('conversation','commission','model'));
        }
        return view('seller.model.conversation.conversation',compact('conversation'));
    }

    public function set_model_commission(Request $request)
    {
        $defaultModelCommission  = DefaultModelCommission::updateOrCreate([
            'model_id'  => auth()->id(),
        ],[
            'model_commission' => $request->model_commission
        ]);

        return redirect()->back();
    }


    public function model_appointment_create(Request $request)
    {


       $validated = $request->validate([
            'model_commission' => ['required', 'integer'],
            'seller_id' => ['required', 'integer'],
            'model_id' => ['required', 'integer'],
        ]);

        $tmpcommission = TemporaryModelCommission::where([
            ['seller_id', '=', $request->seller_id],
            ['model_id', '=', $request->model_id]
        ])->delete();

        $requestToModel = RequestToModel::create($validated);

        return back();
        # code...
    }

    public function requests_to_be_model()
    {
        $requests = RequestToModel::where('seller_id', auth()->id())->paginate(10);
        // dd($requests);
        //$getUserCurrentAddress = Address::where('user_id',Auth::id())->first();

        // dd($getUserCurrentAddress);

        return view('seller.model.requests-to-be-model.requests-to-be-model', compact('requests'));
    }


    public function appointment_for_modeling()
    {
        $appointments = RequestToModel::where('model_id', auth()->id())->paginate(10);
        return view('frontend.user.model.requests-to-be-model', compact('appointments'));
    }

    public function update_request_model_status($request,$status)
    {

        $request =  RequestToModel::findOrFail($request);
        $request->request_status = $status;
        $request->save();
        return back();
    }

    public function temporary_model_commission_store(Request $request)
    {
        $TemporaryModelCommission  = TemporaryModelCommission::updateOrCreate([
            'seller_id' => $request->seller_id,
            'model_id' => $request->model_id,
        ],[
            'commission' => $request->commission,

        ]);

        return redirect()->back();
    }


    public function reject_measurment($measurment_id, int $status)
    {
        $measurment = RequestMeasurement::findOrFail(decrypt($measurment_id));
        $measurer_id = $measurment->appointment->measurer_id;
        if($status == 0){
            
            $wallet = new Wallet;
            $wallet->user_id = $measurer_id;
            $wallet->amount = $measurment->appointment->measurer_commission;
            $wallet->payment_method = 'Measurment Commission';
            $wallet->payment_details = 'Measurment Commission';
            $wallet->save();

            // add measurer commission in his wallet
            $measurer = User::find($measurer_id);
            $measurer->balance = $measurer->balance + $measurment->appointment->measurer_commission;
            $measurer->save();
        }

        $measurment = RequestMeasurement::findOrFail(decrypt($measurment_id));
        $measurment->is_rejected = $status;
        $measurment->rejected_by = auth()->id();
        $measurment->save();
        return back();
    }


    public function nearby_models(Request $request) {

        $user1 = Address::find($request->address_id);
     
        $user2 = Address::where('user_id',Auth::id())->first();
        // dd($user2);
        $earthRadiusKm = 6371; // Approximate radius of the earth in km

        $lat1 = $user1->latitude;
        $lon1 = $user1->longitude;
        $lat2 = $user2->latitude;
        $lon2 = $user2->longitude;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        $distance = $earthRadiusKm * $c;
    
      
        $data['distance'] =   $distance;
        $data['user_name'] =   $user1->user->name;


        // dd($data);
         return response()->json($data);
    
        }
}
