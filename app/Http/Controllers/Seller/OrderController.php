<?php

namespace App\Http\Controllers\Seller;

use App\Mail\InvoiceEmailManager;
use App\Models\Address;
use App\Models\Order;
use App\Models\ProductStock;
use App\Models\SmsTemplate;
use App\Models\User;
use App\Models\Shop;
use App\Utility\NotificationUtility;
use App\Utility\SmsUtility;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $orders = DB::table('orders')
            ->orderBy('id', 'desc')
            ->where('seller_id', Auth::user()->id)
            ->select('orders.id')
            ->distinct();

        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }

        $orders = $orders->paginate(15);

        foreach ($orders as $key => $value) {
            $order = Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }

        return view('seller.orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
    }

    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::where('city', $order_shipping_address->city)
            ->where('user_type', 'delivery_boy')
            ->get();

        $order->viewed = 1;
        $order->save();
        return view('seller.orders.show', compact('order', 'delivery_boys'));
    }

    // Update Delivery Status
    public function update_delivery_status(Request $request)
    {
      
           $shop = Shop::where('user_id',Auth::id())->first();
           
           $order = Order::findOrFail($request->order_id);


           $customer_shipping_address = json_decode($order->shipping_address, true);


        
          if(isset($shop)){
            $shop_address = $shop->address;
          }

            // Build the URL for the API request
            $url_shop = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($shop_address) . '&key=' . env('MAP_API_KEY');

            $url_customer = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($customer_shipping_address['address']) . '&key=' . env('MAP_API_KEY');
            

            // Make the API request using Guzzle
            $client = new \GuzzleHttp\Client();
            $response_shop = $client->get($url_shop);
            $data_shop = json_decode($response_shop->getBody(), true);


            $response_customer = $client->get($url_customer);
            $data_customer = json_decode($response_customer->getBody(), true);

            // Extract the latitude and longitude from the API response
            $shop_latitude = $data_shop['results'][0]['geometry']['location']['lat'];
            $shop_longitude = $data_shop['results'][0]['geometry']['location']['lng'];

            $customer_latitude = $data_customer['results'][0]['geometry']['location']['lat'];
            $customer_longitude = $data_customer['results'][0]['geometry']['location']['lng'];

            // dd($shop_latitude , $shop_longitude);
            // Get the latitude and longitude of User A and User B
            $user_a = [
                'latitude' => $shop_latitude,
                'longitude' => $shop_longitude,
            ];

            $user_b = [
                'latitude' => $customer_latitude,
                'longitude' => $customer_longitude,
            ];

            // Calculate the distance between User A and User B using the Haversine formula
            $lat1 = $user_a['latitude'];
            $lat2 = $user_b['latitude'];
            $lon1 = $user_a['longitude'];
            $lon2 = $user_b['longitude'];
            $radius = 6371; // Earth's radius in kilometers

            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);

            $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $radius * $c;

            // Estimate the delivery time based on the average speed of delivery
            $average_speed = 50; // km/h
            $delivery_time = $distance / $average_speed;

            // Convert the delivery time from hours to days
            $delivery_time_days = ceil($delivery_time / 24);

            // Calculate the estimated delivery date
            $order_date = Carbon::now();
            $estimated_delivery_date = $order_date->addDays($delivery_time_days);

            // dd($estimated_delivery_date);


    //    $getCurrentDatetime =  date('Y-m-d H:i:s');
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;

        if ($request->status == "confirmed") {
            $order->delivery_time = $estimated_delivery_date;
        }

        $order->save();

       
        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }


        foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
            $orderDetail->delivery_status = $request->status;
            $orderDetail->save();

            if ($request->status == 'cancelled') {
                $variant = $orderDetail->variation;
                if ($orderDetail->variation == null) {
                    $variant = '';
                }

                $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                    ->where('variant', $variant)
                    ->first();

                if ($product_stock != null) {
                    $product_stock->qty += $orderDetail->quantity;
                    $product_stock->save();
                }
            }
        }

        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
            try {
                SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {

            }
        }

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('delivery_boy')) {
            if (Auth::user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }

        return 1;
    }

       // Update Payment Status
       public function update_delivery_time(Request $request)
       {
           $order = Order::findOrFail($request->order_id);
           $order->delivery_time = $request->status;
           $order->save();

       }

    // Update Payment Status
    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();


        if ($order->payment_status == 'paid' && $order->commission_calculated == 0) {
            calculateCommissionAffilationClubPoint($order);
        }

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->payment_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
            try {
                SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {

            }
        }
        return 1;
    }

    public function assign_delivery_boy(Request $request)
    {
        if (addon_is_activated('delivery_boy')) {

            $order = Order::findOrFail($request->order_id);
            $order->assign_delivery_boy = $request->delivery_boy;
            $order->delivery_history_date = date("Y-m-d H:i:s");
            $order->save();

            $delivery_history = \App\Models\DeliveryHistory::where('order_id', $order->id)
                ->where('delivery_status', $order->delivery_status)
                ->first();

            if (empty($delivery_history)) {
                $delivery_history = new \App\Models\DeliveryHistory;

                $delivery_history->order_id = $order->id;
                $delivery_history->delivery_status = $order->delivery_status;
                $delivery_history->payment_type = $order->payment_type;
            }
            $delivery_history->delivery_boy_id = $request->delivery_boy;

            $delivery_history->save();

            if (env('MAIL_USERNAME') != null && get_setting('delivery_boy_mail_notification') == '1') {
                $array['view'] = 'emails.invoice';
                $array['subject'] = translate('You are assigned to delivery an order. Order code') . ' - ' . $order->code;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['order'] = $order;

                try {
                    Mail::to($order->delivery_boy->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {

                }
            }

            if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'assign_delivery_boy')->first()->status == 1) {
                try {
                    SmsUtility::assign_delivery_boy($order->delivery_boy->phone, $order->code);
                } catch (\Exception $e) {

                }
            }
        }

        return 1;
    }

}
