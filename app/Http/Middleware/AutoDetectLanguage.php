<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Victorybiz\GeoIPLocation\GeoIPLocation;
use App\Models\Currency;

use Ipdata\ApiClient\Ipdata;
use Symfony\Component\HttpClient\Psr18Client;
use Nyholm\Psr7\Factory\Psr17Factory;

class AutoDetectLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // dd($request->ip());
        // $geoip = new GeoIPLocation([
        //     // 'ip' => $request->ip(), // Set IP.  Default is NULL, will be auto set by the package
        //     'ip' => '102.128.79.255',
        // ]);

        $geoip = new GeoIPLocation();

        $geoip->setIP($request->ip());

        $country_code =  $geoip->getCountryCode();
        $currency_code =  $geoip->getCurrencyCode();






        // $array = array(
        //  'IN' => 'ar',
        //  'BD' => 'bn',
        //  'US' => 'en',
        //  'SA' => 'ar',
        //  'MY' => 'ms',
        //  'PK' => 'ur',
        //  'MX' => 'es',
        //  'SG' => 'zh',
        //  'FR' => 'fr',
        //  'TR' => 'tr',
        //  'UA' => 'ukr',
        //  'UG' => 'sw',
        //  'DE' => 'de',
        //  'HK' => 'zh',
        //  'IS' => 'is',
        //  'ID' => 'in',
        //  'IE' => 'en-ie',
        //  'IT' => 'it',
        //  'KW' => 'ar',
        //  'ZW' => 'sn');


        // foreach ($array as $key => $item) {
        //     if($key == $country_code){
        //         app()->setLocale($item);
        //         session()->put('localelang', $item);
        //     }
        // }


        $httpClient = new Psr18Client();
        $psr17Factory = new Psr17Factory();
        $ipdata = new Ipdata('febac5553cf3dcc174eba634496fafd45b9dbb99d0bbbd15b4dad7ea', $httpClient, $psr17Factory);
        $data = $ipdata->lookup('50.174.42.230');
        // $data = $ipdata->lookup(request()->ip());
        if(session()->get('localelang') == null){
            session()->put('localelang', $data['languages'][0]['code']);
        }
        // dd(session()->get('localelang'));



        $currency = Currency::where('code', $currency_code)->first();




        if(isset($currency)){

            $request->session()->put('currency_code', $currency_code);
            $request->session()->put('currency_symbol', $currency->symbol);
            $request->session()->put('currency_exchange_rate', $currency->exchange_rate);

        }

        else{
            $request->session()->put('currency_code', 'USD');
            $request->session()->put('currency_symbol', '$');
            $request->session()->put('currency_exchange_rate', '1.0');
        }



        return $next($request);
    }
}
