@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:price:currency" content="{{ \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@section('content')
    <section class="mb-4 pt-3">
        <div class="container">
            <div class="bg-white shadow-sm rounded p-3">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 mb-4">
                        <div class="sticky-top z-3 row gutters-10">
                            @php
                                $photos = explode(',', $detailedProduct->photos);
                            @endphp
                            <div class="col order-1 order-md-2">
                                <div class="aiz-carousel product-gallery" data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true'>
                                    @foreach ($photos as $key => $photo)
                                        <div class="carousel-box img-zoom rounded">
                                            <img
                                                class="img-fluid lazyload"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ uploaded_asset($photo) }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                            >
                                        </div>
                                    @endforeach
                                    @foreach ($detailedProduct->stocks as $key => $stock)
                                        @if ($stock->image != null)
                                            <div class="carousel-box img-zoom rounded">
                                                <img
                                                    class="img-fluid lazyload"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($stock->image) }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12 col-md-auto w-md-80px order-2 order-md-1 mt-3 mt-md-0">
                                <div class="aiz-carousel product-gallery-thumb" data-items='5' data-nav-for='.product-gallery' data-vertical='true' data-vertical-sm='false' data-focus-select='true' data-arrows='true'>
                                    @foreach ($photos as $key => $photo)
                                    <div class="carousel-box c-pointer border p-1 rounded">
                                        <img
                                            class="lazyload mw-100 size-50px mx-auto"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($photo) }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                        >
                                    </div>
                                    @endforeach
                                    @foreach ($detailedProduct->stocks as $key => $stock)
                                        @if ($stock->image != null)
                                            <div class="carousel-box c-pointer border p-1 rounded" data-variation="{{ $stock->variant }}">
                                                <img
                                                    class="lazyload mw-100 size-50px mx-auto"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($stock->image) }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7 col-lg-6">
                        <div class="text-left">
                            <h1 class="mb-2 fs-20 fw-600">
                                {{ $detailedProduct->getTranslation('name') }}
                            </h1>

                            <div class="row align-items-center">
                                <div class="col-12">
                                    @php
                                        $total = 0;
                                        $total += $detailedProduct->reviews->count();
                                    @endphp
                                    <span class="rating">
                                        {{ renderStarRating($detailedProduct->rating) }}
                                    </span>
                                    <span class="ml-1 opacity-50">({{ $total }} {{  GoogleTranslate::trans('reviews',session()->get('localelang'))}})</span>
                                </div>
                                @if ($detailedProduct->est_shipping_days)
                                <div class="col-auto ml">
                                    <small class="mr-2 opacity-50">{{  GoogleTranslate::trans('Estimate Shipping Time',session()->get('localelang'))}}: </small>{{ $detailedProduct->est_shipping_days }} {{   GoogleTranslate::trans('Days',session()->get('localelang')) }}
                                </div>
                                @endif
                            </div>

                            <hr>

                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <small class="mr-2 opacity-50">{{  GoogleTranslate::trans('Sold by',session()->get('localelang'))}}: </small><br>
                                    @if ($detailedProduct->added_by == 'seller' && get_setting('vendor_system_activation') == 1)
                                        <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="text-reset">{{ $detailedProduct->user->shop->name }}</a>
                                    @else
                                        {{   GoogleTranslate::trans('Inhouse product',session()->get('localelang')) }}
                                    @endif
                                </div>
                                @if (get_setting('conversation_system') == 1)
                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-soft-primary" onclick="show_notification_modal()">{{  GoogleTranslate::trans('Message Seller',session()->get('localelang'))}}</button>
                                    </div>
                                @endif

                                @if ($detailedProduct->brand != null)
                                    <div class="col-auto">
                                        <a href="{{ route('products.brand',$detailedProduct->brand->slug) }}">
                                            <img src="{{ uploaded_asset($detailedProduct->brand->logo) }}" alt="{{ $detailedProduct->brand->getTranslation('name') }}" height="30">
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            @if ($detailedProduct->wholesale_product)
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{  GoogleTranslate::trans('Min Qty',session()->get('localelang')) }}</th>
                                            <th>{{  GoogleTranslate::trans('Max Qty',session()->get('localelang')) }}</th>
                                            <th>{{  GoogleTranslate::trans('Unit Price',session()->get('localelang')) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detailedProduct->stocks->first()->wholesalePrices as $wholesalePrice)
                                            <tr>
                                                <td>{{ $wholesalePrice->min_qty }}</td>
                                                <td>{{ $wholesalePrice->max_qty }}</td>
                                                <td>{{ single_price($wholesalePrice->price) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                @if(home_price($detailedProduct) != home_discounted_price($detailedProduct))
                                    <div class="row no-gutters mt-3">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ translate('Price')}}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="fs-20 opacity-60">
                                                <del>
                                                    {{ home_price($detailedProduct) }}
                                                    @if($detailedProduct->unit != null)
                                                        <span>/{{ $detailedProduct->getTranslation('unit') }}</span>
                                                    @endif
                                                </del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row no-gutters my-2">
                                        <div class="col-sm-2">
                                            <div class="opacity-50">{{ translate('Discount Price')}}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="">
                                                <strong class="h2 fw-600 text-primary">
                                                    {{ home_discounted_price($detailedProduct) }}
                                                </strong>
                                                @if($detailedProduct->unit != null)
                                                    <span class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row no-gutters mt-3">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ translate('Price')}}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="">
                                                <strong class="h2 fw-600 text-primary">
                                                    {{ home_discounted_price($detailedProduct) }}
                                                </strong>
                                                @if($detailedProduct->unit != null)
                                                    <span class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                <div class="row no-gutters mt-4">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{  translate('Club Point') }}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="d-inline-block rounded px-2 bg-soft-primary border-soft-primary border">
                                            <span class="strong-700">{{ $detailedProduct->earn_point }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <hr>

                            <form id="option-choice-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

                                @if ($detailedProduct->choice_options != null)
                                    @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)

                                    <div class="row no-gutters">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ \App\Models\Attribute::find($choice->attribute_id)->getTranslation('name') }}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="aiz-radio-inline">
                                                @foreach ($choice->values as $key => $value)
                                                <label class="aiz-megabox pl-0 mr-2">
                                                    <input
                                                        type="radio"
                                                        name="attribute_id_{{ $choice->attribute_id }}"
                                                        value="{{ $value }}"
                                                        @if($key == 0) checked @endif
                                                    >
                                                    <span class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center py-2 px-3 mb-2">
                                                        {{ $value }}
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                @endif

                                @if (count(json_decode($detailedProduct->colors)) > 0)
                                    <div class="row no-gutters">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ translate('Color')}}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="aiz-radio-inline">
                                                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                                <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip" data-title="{{ \App\Models\Color::where('code', $color)->first()->name }}">
                                                    <input
                                                        type="radio"
                                                        name="color"
                                                        value="{{ \App\Models\Color::where('code', $color)->first()->name }}"
                                                        @if($key == 0) checked @endif
                                                    >
                                                    <span class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 mb-2">
                                                        <span class="size-30px d-inline-block rounded" style="background: {{ $color }};"></span>
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                @endif

                                <!-- Quantity + Add to cart -->
                                <div class="row no-gutters">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{ translate('Quantity')}}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="product-quantity d-flex align-items-center">
                                            <div class="row no-gutters align-items-center aiz-plus-minus mr-3" style="width: 130px;">
                                                <button class="btn col-auto btn-icon btn-sm btn-circle btn-light" type="button" data-type="minus" data-field="quantity" disabled="">
                                                    <i class="las la-minus"></i>
                                                </button>
                                                <input type="number" name="quantity" class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}" max="10" lang="en">
                                                <button class="btn  col-auto btn-icon btn-sm btn-circle btn-light" type="button" data-type="plus" data-field="quantity">
                                                    <i class="las la-plus"></i>
                                                </button>
                                            </div>
                                            @php
                                                $qty = 0;
                                                foreach ($detailedProduct->stocks as $key => $stock) {
                                                    $qty += $stock->qty;
                                                }
                                            @endphp
                                            <div class="avialable-amount opacity-60">
                                                @if($detailedProduct->stock_visibility_state == 'quantity')
                                                (<span id="available-quantity">{{ $qty }}</span> {{ translate('available')}})
                                                @elseif($detailedProduct->stock_visibility_state == 'text' && $qty >= 1)
                                                    (<span id="available-quantity">{{ translate('In Stock') }}</span>)
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row no-gutters pb-3 d-none" id="chosen_price_div">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{ translate('Total Price')}}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="product-price">
                                            <strong id="chosen_price" class="h4 fw-600 text-primary">

                                            </strong>
                                        </div>
                                    </div>
                                </div>

                            </form>

                            <div class="mt-3">
                                @if ($detailedProduct->external_link != null)
                                    <a type="button" class="btn btn-primary buy-now fw-600" href="{{ $detailedProduct->external_link }}">
                                        <i class="la la-share"></i> {{ translate($detailedProduct->external_link_btn)}}
                                    </a>
                                @elseif($detailedProduct->external_link == null && $detailedProduct->is_personalise === 0)
                                    <button type="button" class="btn btn-soft-primary mr-2 add-to-cart fw-600" onclick="addToCart()">
                                        <i class="las la-shopping-bag"></i>
                                        <span class="d-none d-md-inline-block"> {{ translate('Add to cart')}}</span>
                                    </button>
                                    <button type="button" class="btn btn-primary buy-now fw-600" onclick="buyNow()">
                                        <i class="la la-shopping-cart"></i> {{ translate('Buy Now')}}
                                    </button>

                                @elseif($detailedProduct->is_personalise === 1)
                                    <button type="button" class="btn btn-soft-primary mr-2 request-to-personalise fw-600" onclick="requestToPersonliser()">
                                        <i class="las la-arrow-right"></i>
                                        <span class="d-none d-md-inline-block"> {{ translate('Request')}}</span>
                                    </button>

                                @endif
                                <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled>
                                    <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock')}}
                                </button>
                            </div>



                            <div class="d-table width-100 mt-3">
                                <div class="d-table-cell">
                                    <!-- Add to wishlist button -->
                                    <button type="button" class="btn pl-0 btn-link fw-600" onclick="addToWishList({{ $detailedProduct->id }})">
                                        {{ translate('Add to wishlist')}}
                                    </button>
                                    <!-- Add to compare button -->
                                    <button type="button" class="btn btn-link btn-icon-left fw-600" onclick="addToCompare({{ $detailedProduct->id }})">
                                        {{ translate('Add to compare')}}
                                    </button>
                                    @if(Auth::check() && addon_is_activated('affiliate_system') && (\App\Models\AffiliateOption::where('type', 'product_sharing')->first()->status || \App\Models\AffiliateOption::where('type', 'category_wise_affiliate')->first()->status) && Auth::user()->affiliate_user != null && Auth::user()->affiliate_user->status)
                                        @php
                                            if(Auth::check()){
                                                if(Auth::user()->referral_code == null){
                                                    Auth::user()->referral_code = substr(Auth::user()->id.Str::random(10), 0, 10);
                                                    Auth::user()->save();
                                                }
                                                $referral_code = Auth::user()->referral_code;
                                                $referral_code_url = URL::to('/product').'/'.$detailedProduct->slug."?product_referral_code=$referral_code";
                                            }
                                        @endphp
                                        <div>
                                            <button type=button id="ref-cpurl-btn" class="btn btn-sm btn-secondary" data-attrcpy="{{ translate('Copied')}}" onclick="CopyToClipboard(this)" data-url="{{$referral_code_url}}">{{ translate('Copy the Promote Link')}}</button>
                                        </div>
                                    @endif
                                </div>
                            </div>


                            @php
                                $refund_sticker = get_setting('refund_sticker');
                            @endphp
                            @if (addon_is_activated('refund_request'))
                                <div class="row no-gutters mt-3">
                                    <div class="col-2">
                                        <div class="opacity-50 mt-2">{{ translate('Refund')}}:</div>
                                    </div>
                                    <div class="col-10">
                                        <a href="{{ route('returnpolicy') }}" target="_blank">
                                            @if ($refund_sticker != null)
                                                <img src="{{ uploaded_asset($refund_sticker) }}" height="36">
                                            @else
                                                <img src="{{ static_asset('assets/img/refund-sticker.jpg') }}" height="36">
                                            @endif</a>
                                        <a href="{{ route('returnpolicy') }}" class="ml-2" target="_blank">{{ translate('View Policy') }}</a>
                                    </div>
                                </div>
                            @endif
                            <div class="row no-gutters mt-4">
                                <div class="col-sm-2">
                                    <div class="opacity-50 my-2">{{ translate('Share')}}:</div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="aiz-share"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                <div class="col-xl-3 order-1 order-xl-0">
                    @if ($detailedProduct->added_by == 'seller' && $detailedProduct->user->shop != null)
                        <div class="bg-white shadow-sm mb-3">
                            <div class="position-relative p-3 text-left">
                                @if ($detailedProduct->user->shop->verification_status)
                                    <div class="absolute-top-right p-2 bg-white z-1">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" viewBox="0 0 287.5 442.2" width="22" height="34">
                                            <polygon style="fill:#F8B517;" points="223.4,442.2 143.8,376.7 64.1,442.2 64.1,215.3 223.4,215.3 "/>
                                            <circle style="fill:#FBD303;" cx="143.8" cy="143.8" r="143.8"/>
                                            <circle style="fill:#F8B517;" cx="143.8" cy="143.8" r="93.6"/>
                                            <polygon style="fill:#FCFCFD;" points="143.8,55.9 163.4,116.6 227.5,116.6 175.6,154.3 195.6,215.3 143.8,177.7 91.9,215.3 111.9,154.3
                                            60,116.6 124.1,116.6 "/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="opacity-50 fs-12 border-bottom">{{ translate('Sold by')}}</div>
                                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="text-reset d-block fw-600">
                                    {{ $detailedProduct->user->shop->name }}
                                    @if ($detailedProduct->user->shop->verification_status == 1)
                                        <span class="ml-2"><i class="fa fa-check-circle" style="color:green"></i></span>
                                    @else
                                        <span class="ml-2"><i class="fa fa-times-circle" style="color:red"></i></span>
                                    @endif
                                </a>
                                <div class="location opacity-70">{{ $detailedProduct->user->shop->address }}</div>
                                <div class="text-center border rounded p-2 mt-3">
                                    <div class="rating">
                                        @if ($total > 0)
                                            {{ renderStarRating($detailedProduct->user->shop->rating) }}
                                        @else
                                            {{ renderStarRating(0) }}
                                        @endif
                                    </div>
                                    <div class="opacity-60 fs-12">({{ $total }} {{ translate('customer reviews')}})</div>
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center border-top">
                                <div class="col">
                                    <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="d-block btn btn-soft-primary rounded-0">{{ translate('Visit Store')}}</a>
                                </div>
                                <div class="col">
                                    <ul class="social list-inline mb-0">
                                        <li class="list-inline-item mr-0">
                                            <a href="{{ $detailedProduct->user->shop->facebook }}" class="facebook" target="_blank">
                                                <i class="lab la-facebook-f opacity-60"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item mr-0">
                                            <a href="{{ $detailedProduct->user->shop->google }}" class="google" target="_blank">
                                                <i class="lab la-google opacity-60"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item mr-0">
                                            <a href="{{ $detailedProduct->user->shop->twitter }}" class="twitter" target="_blank">
                                                <i class="lab la-twitter opacity-60"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="{{ $detailedProduct->user->shop->youtube }}" class="youtube" target="_blank">
                                                <i class="lab la-youtube opacity-60"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="bg-white rounded shadow-sm mb-3">
                        <div class="p-3 border-bottom fs-16 fw-600">
                            {{ translate('Top Selling Products')}}
                        </div>
                        <div class="p-3">
                            <ul class="list-group list-group-flush">
                                @foreach (filter_products(\App\Models\Product::where('user_id', $detailedProduct->user_id)->orderBy('num_of_sale', 'desc'))->limit(6)->get() as $key => $top_product)
                                <li class="py-3 px-0 list-group-item border-light">
                                    <div class="row gutters-10 align-items-center">
                                        <div class="col-5">
                                            <a href="{{ route('product', $top_product->slug) }}" class="d-block text-reset">
                                                <img
                                                    class="img-fit lazyload h-xxl-110px h-xl-80px h-120px"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($top_product->thumbnail_img) }}"
                                                    alt="{{ $top_product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                            </a>
                                        </div>
                                        <div class="col-7 text-left">
                                            <h4 class="fs-13 text-truncate-2">
                                                <a href="{{ route('product', $top_product->slug) }}" class="d-block text-reset">{{ $top_product->getTranslation('name') }}</a>
                                            </h4>
                                            <div class="rating rating-sm mt-1">
                                                {{ renderStarRating($top_product->rating) }}
                                            </div>
                                            <div class="mt-2">
                                                <span class="fs-17 fw-600 text-primary">{{ home_discounted_base_price($top_product) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 order-0 order-xl-1">
                    <div class="bg-white mb-3 shadow-sm rounded">
                        <div class="nav border-bottom aiz-nav-tabs">
                            <a href="#tab_default_1" data-toggle="tab" class="p-3 fs-16 fw-600 text-reset active show">{{ translate('Description')}}</a>

                            @if($detailedProduct->video_link != null)
                                <a href="#tab_default_2" data-toggle="tab" class="p-3 fs-16 fw-600 text-reset">{{ translate('Video')}}</a>
                            @endif
                            @if($detailedProduct->pdf != null)
                                <a href="#tab_default_3" data-toggle="tab" class="p-3 fs-16 fw-600 text-reset">{{ translate('Downloads')}}</a>
                            @endif
                                <a href="#tab_default_4" data-toggle="tab" class="p-3 fs-16 fw-600 text-reset">{{ translate('Reviews')}}</a>
                                <a href="#tab_default_5" data-toggle="tab" class="p-3 fs-16 fw-600 text-reset">{{ translate('Product Forum')}}</a>
                        </div>

                        <div class="tab-content pt-0">
                            <div class="tab-pane fade active show" id="tab_default_1">
                                <div class="p-4">
                                    <div class="mw-100 overflow-hidden text-left aiz-editor-data">
                                        <?php echo $detailedProduct->getTranslation('description'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_default_2">
                                <div class="p-4">
                                    <div class="embed-responsive embed-responsive-16by9">

                                        <img height="100" width="100" src="{{asset('taylor-made-watermark.png')}}" alt="">
                                        @if ($detailedProduct->video_provider == 'youtube')
                                            @if (strpos($detailedProduct->video_link, 'www.youtube.com'))
                                            <iframe class="embed-responsive-item" data-video-player="youtube" id="video-frame" src="https://www.youtube.com/embed/{{ get_url_params($detailedProduct->video_link, 'v') }}?rel=0"></iframe>
                                            @else
                                            <iframe class="embed-responsive-item" data-video-player="youtube" id="video-frame" src="https://www.youtube.com/embed/{{ explode('/', $detailedProduct->video_link)[3] ?? '' }}?rel=0"></iframe>
                                            @endif
                                        @elseif ($detailedProduct->video_provider == 'dailymotion')
                                            @if (strpos($detailedProduct->video_link, 'www.dailymotion.com'))
                                                <iframe class="embed-responsive-item" data-video-player="dailymotion" id="video-frame" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}"></iframe>
                                                @else
                                                <iframe class="embed-responsive-item" data-video-player="dailymotion" id="video-frame" src="https://www.dailymotion.com/embed/video/{{ explode('/', $detailedProduct->video_link)[3] ?? '' }}"></iframe>
                                            @endif
                                            {{-- <iframe class="embed-responsive-item" data-video-player="dailymotion" id="video-frame" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}"></iframe> --}}
                                        @elseif ($detailedProduct->video_provider == 'vimeo' && isset(explode('vimeo.com/', $detailedProduct->video_link)[1]))
                                            <iframe id="video-frame" src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] }}" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_default_3">
                                <div class="p-4 text-center ">
                                    <a href="{{ uploaded_asset($detailedProduct->pdf) }}" class="btn btn-primary">{{  translate('Download') }}</a>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_default_4">
                                <div class="p-4">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($detailedProduct->reviews as $key => $review)
                                            @if($review->user != null)
                                            <li class="media list-group-item d-flex">
                                                <span class="avatar avatar-md mr-3">
                                                    <img
                                                        class="lazyload"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                        @if($review->user->avatar_original !=null)
                                                            data-src="{{ uploaded_asset($review->user->avatar_original) }}"
                                                        @else
                                                            data-src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        @endif
                                                    >
                                                </span>
                                                <div class="media-body text-left">
                                                    <div class="d-flex justify-content-between">
                                                        <h3 class="fs-15 fw-600 mb-0">{{ $review->user->name }}</h3>
                                                        <span class="rating rating-sm">
                                                            @for ($i=0; $i < $review->rating; $i++)
                                                                <i class="las la-star active"></i>
                                                            @endfor
                                                            @for ($i=0; $i < 5-$review->rating; $i++)
                                                                <i class="las la-star"></i>
                                                            @endfor
                                                        </span>
                                                    </div>
                                                    <div class="opacity-60 mb-2">{{ date('d-m-Y', strtotime($review->created_at)) }}</div>
                                                    <p class="comment-text">
                                                        {{ $review->comment }}
                                                    </p>
                                                </div>
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>

                                    @if(count($detailedProduct->reviews) <= 0)
                                        <div class="text-center fs-18 opacity-70">
                                            {{  translate('There have been no reviews for this product yet.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_default_5">
                                <div class="p-4">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($productForum as $key => $value)
                                            @if($value != null)
                                            <li class="media list-group-item d-flex">

                                                <div class="media-body text-left">
                                                    {{-- <div class="d-flex justify-content-between">
                                                        <h3 class="fs-15 fw-600 mb-0">{{ $value->user->name }}</h3>

                                                    </div> --}}
                                                    <div class="opacity-60 mb-2">Date : {{ date('d-m-Y', strtotime($value->created_at)) }} - Time : {{ $value->created_at->format('H:i:s') }}</div>
                                                    <p class="comment-text">
                                                        <h3 class="fs-15 fw-600 mb-0"> <span class="bold">Q</span> : {{ $value->question ?? '-' }}</h3>
                                                        <p class="comment-text">Ask By {{$value->user->name ?? '-'}}  </p>
                                                      {{-- Q:  {{ $value->question }} --}}
                                                    </p>
                                                    <p class="comment-text">
                                                        <h3 class="fs-15 fw-600 mb-0"><span class="bold">A</span> : {{ $value->answer ?? '-' }}</h3>
                                                        @if ($value->answer == null)
                                                        <p class="comment-text"> </p>
                                                        @else
                                                        <p class="comment-text">Answer By {{$value->seller->name ?? '-'}}  </p>
                                                        @endif

                                                    </p>
                                                </div>
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>

                                    @if(count($productForum) <= 0)
                                        <div class="text-center fs-18 opacity-70">
                                            {{  translate('There have been no Forum for this product yet.') }}
                                        </div>
                                    @endif

                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-soft-primary" onclick="show_product_forum_modal()">{{ translate('Ask a Question')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded shadow-sm">
                        <div class="border-bottom p-3">
                            <h3 class="fs-16 fw-600 mb-0">
                                <span class="mr-4">{{ translate('Related products')}}</span>
                            </h3>
                        </div>
                        <div class="p-3">
                            <div class="aiz-carousel gutters-5 half-outside-arrow" data-items="5" data-xl-items="3" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
                                @foreach (filter_products(\App\Models\Product::where('category_id', $detailedProduct->category_id)->where('id', '!=', $detailedProduct->id))->limit(10)->get() as $key => $related_product)
                                <div class="carousel-box">
                                    <div class="aiz-card-box border border-light rounded hov-shadow-md my-2 has-transition">
                                        <div class="">
                                            <a href="{{ route('product', $related_product->slug) }}" class="d-block">
                                                <img
                                                    class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($related_product->thumbnail_img) }}"
                                                    alt="{{ $related_product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                            </a>
                                        </div>
                                        <div class="p-md-3 p-2 text-left">
                                            <div class="fs-15">
                                                @if(home_base_price($related_product) != home_discounted_base_price($related_product))
                                                    <del class="fw-600 opacity-50 mr-1">{{ home_base_price($related_product) }}</del>
                                                @endif
                                                <span class="fw-700 text-primary">{{ home_discounted_base_price($related_product) }}</span>
                                            </div>
                                            <div class="rating rating-sm mt-1">
                                                {{ renderStarRating($related_product->rating) }}
                                            </div>
                                            <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                                <a href="{{ route('product', $related_product->slug) }}" class="d-block text-reset">{{ $related_product->getTranslation('name') }}</a>
                                            </h3>
                                            @if (addon_is_activated('club_point'))
                                                <div class="rounded px-2 mt-2 bg-soft-primary border-soft-primary border">
                                                    {{ translate('Club Point') }}:
                                                    <span class="fw-700 float-right">{{ $related_product->earn_point }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('modal')
    @if(\Auth::check())
    <div class="modal fade" id="request-to-personaliser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Request')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="p-3">

                        <form class="form-default request-to-personliser-form" role="form" method="POST" enctype="multipart/form-data">



                            @csrf
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Name')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="name" class="form-control  mb-3" id="name" value="{{ \Auth::user()->name }}" placeholder="Name" required>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Email')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="email" name="email" class="form-control  mb-3" class="form-control" id="email" value="{{ \Auth::user()->email }}" placeholder="Email" required>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Address')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control mb-3" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Country')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="mb-3">
                                        <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                                            <option value="">{{ translate('Select your country') }}</option>
                                            @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('State')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required>

                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('City')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city_id" required>

                                    </select>
                                </div>
                            </div>

                            @if (get_setting('google_map') == 0)
                            <div class="row">


                                <ul id="geoData">
                                    <li style="display: none;">Full Address: <span id="location"></span></li>
                                    <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                                    <li style="display: none;">Country: <span id="country"></span></li>
                                    <li style="display: none;">Latitude: <span id="lat"></span></li>
                                    <li style="display: none;">Longitude: <span id="lon"></span></li>
                                </ul>
                            </div>

                            <div class="row">

                                <div class="col-md-10" id="">
                                    <input type="hidden" class="form-control mb-3" id="longitude" name="longitude" readonly="">
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-10" id="">
                                    <input type="hidden" class="form-control mb-3" id="latitude" name="latitude" readonly="">
                                </div>

                            </div>
                        @endif

                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Postal code')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" required>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Phone')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('+880')}}" name="phone" value="{{ \Auth::user()->phone }}" required>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Media')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input class="form-control form-control-sm" id="formFileSm" type="file" name="file[]" multiple>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-2 mt-3">
                                    <label>{{ translate('Specify Something')}}</label>
                                </div>
                                <div class="col-md-10 mt-3">
                                    <textarea name="product_description" rows="5" class="form-control"></textarea>
                                </div>


                            </div>
                            {{-- <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Attachment')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="file"  id="attachment" name="attachment[]" multiple>
                                </div>

                            </div> --}}


                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-primary mt-4 GetLocation" >Pin To Current Location</button>


                             </div>

                            </div>

                            <div id="map"></div>
                            <div class="mb-5 mt-4">
                                <button type="submit" class="btn btn-primary btn-block fw-600">{{  translate('Request') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    @section('modal')
    @if(\Auth::check())
    <div class="modal fade" id="request-for-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                     <h6 class="modal-title fw-600">{{ translate('Request For Measurement')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-5">
                        <button type="button" class="btn btn-primary btn-block fw-600" onclick="requestForSelectMeasurment()">{{  translate('Select Measurement') }}</button>
                    </div>

                    <div class="mb-5">
                        <button type="button" class="btn btn-primary btn-block fw-600" onclick="requestForMeasurment()">{{  translate('Request Measurement') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif



    @section('modal')
    @if(\Auth::check())
    <div class="modal fade" id="select-to-measurment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Request')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
             <form method="POST" action="{{route('user.order.create')}}">
             @csrf
             <div class="modal-body">
                 <input type="hidden" name="product_id" value="{{$detailedProduct->id}}">
                    @if (count($appointments) > 0)
                    <div class="card-body p-3">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ translate('Product')}}</th>
                                    {{-- <th data-breakpoints="lg">{{ translate('Owner')}}</th> --}}
                                    {{-- <th data-breakpoints="lg">{{ translate('Measurer')}}</th> --}}
                                    <th data-breakpoints="lg">{{ translate('Price')}}</th>
                                    {{-- <th data-breakpoints="lg">{{ translate('Status')}}</th> --}}
                                    <th data-breakpoints="lg">{{ translate('action')}}</th>
                                    <th data-breakpoints="lg">{{ translate('Measurments')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($appointments as $key => $appointment)

                                    @if($appointment != null)
                                        <tr>
                                            <td>
                                                {{ $key+1 }}
                                            </td>
                                            <td>
                                                {{ $appointment->product->name }}
                                            </td>
                                            {{-- <td>
                                                {{ $appointment->owner->name }}
                                            </td> --}}
                                            {{-- <td>
                                                {{ $appointment->measurer->name }}
                                            </td> --}}

                                            <td>
                                                {{ get_system_default_currency()->symbol.' '.$appointment->request->price }}
                                            </td>

                                            {{-- <td>
                                                {{ appointment_stutus($appointment->appointment_status) }}
                                            </td> --}}
                                            <td>
                                                <label class="radio">
                                                    <input value="{{$appointment->id}}" type="radio" name="appointment_id" required>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            {{-- <td>
                                             <input type="hidden" name="appointment_id" value="{{$appointment->id}}">
                                            </td> --}}

                                            <td>
                                                <a href="javascript:;" class="m-add-measurements" a-m-url="{{ route("measurer-measurement", encrypt($appointment->id)) }}">View Measurements</a>
                                            </td>

                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="aiz-pagination">
                            {{ $appointments->links() }}
                          </div>

                          <div class="mb-5">
                            <button type="submit" class="btn btn-primary fw-600">{{  translate('Submit') }}</button>
                          </div>
                    </div>

                @else
                    <div class="card-body p-3">
                        <h5>There is no appointment</h5>
                    </div>
                @endif
                </div>
            </div>
        </form>
        </div>
    </div>
    @endif




    @if(\Auth::check())
    <div class="modal fade" id="measurer-measurement-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Measurements')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="p-3">
                        <form class="form-default" role="form" method="POST" enctype="multipart/form-data">
                            @csrf



                            {{-- <div class="form-group">
                                <label for="measurements_text">Measurements</label>
                                <textarea name="measurements_text" id="measurements_text" class="form-control"  placeholder="Measurements" rows="5" readonly></textarea>
                            </div> --}}
                            {{-- <div class="form-group">
                                <label for="measurements_image">Measurements Image</label>
                                <br>

                                <a href="" data-lightbox="photo">
                                    <img src="" width="100" class="m-img-preview ">
                                </a>
                            </div> --}}


                            @isset($appointment->product->personalizeProductTypeName)
                            @if($appointment->product->personalizeProductTypeName->slug == 'clothing')
                            <div class="row">
                                <img src="{{asset('public/assets/img/clothing.png')}}" alt="" style="margin-left: 130px;">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="neck_circumference">{{ translate('Neck Circumference')}} (1)</label>
                                    <input type="number" readonly step=".01" required name="neck_circumference1" id="neck_circumference" class="form-control" placeholder="Neck Circumference">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="shoulder_to_shoulder">{{ translate('Shoulder to shoulder')}} (2)</label>
                                    <input type="number" readonly step=".01" required name="shoulder_to_shoulder2" id="shoulder_to_shoulder" class="form-control" placeholder="Shoulder to shoulder">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="chest_circumference">{{ translate('Chest circumference')}} (3)</label>
                                    <input type="number"  readonly step=".01" required name="chest_circumference3" id="chest_circumference" class="form-control" placeholder="Chest circumference">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="circumference_under_the_breast">{{ translate('Circumference under the breast (women only)')}} (3a)</label>
                                    <input type="number" readonly step=".01" name="circumference_under_the_breast3a" id="circumference_under_the_breast" class="form-control" placeholder="Circumference under the breast">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="waist_circumference">{{ translate('Waist circumference')}} (4)</label>
                                    <input type="number" readonly step=".01" required name="waist_circumference4" id="waist_circumference" class="form-control" placeholder="Waist circumference">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="back_length">{{ translate('Back length')}} (5)</label>
                                    <input type="number" readonly step=".01" required name="back_length5" id="back_length" class="form-control" placeholder="Back length">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="shoulder_to_wrist">{{ translate('Shoulder to wrist')}} (6)</label>
                                    <input type="number" readonly step=".01" required name="shoulder_to_wrist6" id="shoulder_to_wrist" class="form-control" placeholder="Shoulder to wrist">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="shoulder_to_elbow">{{ translate('Shoulder to elbow')}} (7)</label>
                                    <input type="number" readonly step=".01" required name="shoulder_to_elbow7" id="shoulder_to_elbow" class="form-control" placeholder="Shoulder to elbow">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="wrist_to_elbow">{{ translate('Wrist to elbow')}} (8)</label>
                                    <input type="number" readonly step=".01" required name="wrist_to_elbow8" id="wrist_to_elbow" class="form-control" placeholder="Wrist to elbow">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="biceps">{{ translate('Biceps (circumference)')}} (9)</label>
                                    <input type="number" readonly step=".01" required name="biceps9" id="biceps" class="form-control" placeholder="Biceps (circumference)">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="forearm">{{ translate('Forearm (circumference)')}} (10)</label>
                                    <input type="number" readonly step=".01" required name="forearm10" id="forearm" class="form-control" placeholder="Forearm (circumference)">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="wrist_circumference">{{ translate('Wrist circumference')}} (11)</label>
                                    <input type="number" readonly step=".01" required name="wrist_circumference11" id="wrist_circumference" class="form-control" placeholder="Wrist circumference">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="waist_to_ankle_length">{{ translate('Waist to ankle length')}} (12)</label>
                                    <input type="number" readonly step=".01" required name="waist_to_ankle_length12" id="waist_to_ankle_length" class="form-control" placeholder="Waist to ankle length">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="hip_circumference">{{ translate('Hip circumference')}} (13)</label>
                                    <input type="number" readonly step=".01" required name="hip_circumference13" id="hip_circumference" class="form-control" placeholder="Hip circumference">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="thigh_circumference">{{ translate('Thigh circumference')}} (14)</label>
                                    <input type="number" readonly step=".01" required name="thigh_circumference14" id="thigh_circumference" class="form-control" placeholder="Thigh circumference">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="circumference_of_knees">{{ translate('Circumference of knees')}} (15)</label>
                                    <input type="number" readonly step=".01" required name="circumference_of_knees15" id="circumference_of_knees" class="form-control" placeholder="Circumference of knees">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="calf_circumference">{{ translate('Calf circumference')}} (16)</label>
                                    <input type="number" readonly step=".01" required name="calf_circumference16" id="calf_circumference" class="form-control" placeholder="Calf circumference">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="crotch_to_ankle">{{ translate('Crotch to ankle')}} (17)</label>
                                    <input type="number" readonly step=".01" required name="crotch_to_ankle17" id="crotch_to_ankle" class="form-control" placeholder="Crotch to ankle">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="knees_to_ankle">{{ translate('Knees to ankle')}} (18)</label>
                                    <input type="number" readonly step=".01" required name="knees_to_ankle18" id="knees_to_ankle" class="form-control" placeholder="Knees to ankle">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="ankle_circumference">{{ translate('Ankle circumference')}} (19)</label>
                                    <input type="number" readonly step=".01" required name="ankle_circumference19" id="ankle_circumference" class="form-control" placeholder="Ankle circumference">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="neck_to_ankle">{{ translate('Neck to ankle')}} (20)</label>
                                    <input type="number" readonly step=".01" required name="neck_to_ankle20" id="neck_to_ankle" class="form-control" placeholder="Neck to ankle">
                                </div>
                            </div>

                            </div>

                            @elseif($appointment->product->personalizeProductTypeName->slug == "shoes")
                            <div class="row">
                                <img src="{{asset('public/assets/img/foot-size.jpg')}}" alt="" style="margin-left: 240px; height: 280px;">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_a">{{ translate('Foot A')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_a" id="foot_a" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_b">{{ translate('Foot B')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_a" id="foot_b" class="form-control" >
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_c">{{ translate('Foot C')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_a" id="foot_c" class="form-control">
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_d">{{ translate('Foot D')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_a" id="foot_d" class="form-control" >
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_e">{{ translate('Foot E')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_e" id="foot_e" class="form-control">
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_f">{{ translate('Foot F')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_f" id="foot_f" class="form-control" >
                                </div>
                            </div>
                            @if ($appointment->product->category->slug == "clothing" || $appointment->product->category->slug == "menclothingandfashion")
                            <div class="col-lg-4">
                            <div class="form-group">
                                <label for="measurements_video">Measurements Video</label>
                                 <video width="320" height="240" controls autoplay>
                                    @if (isset($appointment->measurement->measurement_video))
                                    <source src="{{asset('public/'.$appointment->measurement->measurement_video)}}"  type="video/webm">
                                        Your browser does not support the video tag.
                                    @endif

                                </video>
                            </div>
                            </div>
                            @endif
                        </div>
                            @endif

                        @endisset


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif



    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3" name="title" value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="8" name="message" required placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600" data-dismiss="modal">{{ translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary fw-600">{{ translate('Send')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" id="notification_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Notification')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="">

                    <div class="modal-body gry-bg px-3 pt-3">

                        <div class="form-group">
                          <h4>Do Not Send Money to Seller Directly </h4>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600" data-dismiss="modal">{{ translate('Cancel')}}</button>
                        <button type="button" class="btn btn-primary fw-600" onclick="show_chat_modal()">{{ translate('I understand')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="product_forum_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom forum-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any Questions About This Product')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{route('user.forum.add')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <input type="hidden" name="seller_id" value="{{ $detailedProduct->user_id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        {{-- <div class="form-group">
                            <input type="text" class="form-control mb-3" name="title" value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}" required>
                        </div> --}}
                        <div class="form-group">
                            <textarea required class="form-control" rows="8" name="question" required placeholder="{{ translate('Your Question') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600" data-dismiss="modal">{{ translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary fw-600">{{ translate('Send')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                @if (addon_is_activated('otp_system'))
                                    <input type="text" class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone')}}" name="email" id="email">
                                @else
                                    <input type="email" class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email">
                                @endif
                                @if (addon_is_activated('otp_system'))
                                    <span class="opacity-60">{{  translate('Use country code before number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg" placeholder="{{ translate('Password')}}">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class=opacity-60>{{  translate('Remember Me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}" class="text-reset opacity-60 fs-14">{{ translate('Forgot password?')}}</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit" class="btn btn-primary btn-block fw-600">{{  translate('Login') }}</button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">{{ translate('Dont have an account?')}}</p>
                            <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a>
                        </div>
                        @if(get_setting('google_login') == 1 ||
                            get_setting('facebook_login') == 1 ||
                            get_setting('twitter_login') == 1)
                            <div class="separator mb-3">
                                <span class="bg-white px-3 opacity-60">{{ translate('Or Login With')}}</span>
                            </div>
                            <ul class="list-inline social colored text-center mb-5">
                                @if (get_setting('facebook_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                @endif
                                @if(get_setting('google_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                            <i class="lab la-google"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (get_setting('twitter_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="twitter">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
    	});

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }
        function show_chat_modal(){
            @if (Auth::check())
               $('#notification_modal').modal('hide');
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }


        function show_notification_modal(){
            @if (Auth::check())
                $('#notification_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function show_product_forum_modal(){
            @if (Auth::check())
                $('#product_forum_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }


        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });



        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });

        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }



        $(function(){
            $(document).on('click', '.m-add-measurements', function(e) {

                // console.log('asd');

                $('#measurer-measurement-modal').modal();
                $('#measurer-measurement-modal form')[0].reset();
                $('[name="measurements_image_h"]').val('');
                var url = $(this).attr('a-m-url');
                var imgUrl = '{{ url("public/assets/img") }}';
                // console.log(imgUrl);
                $.ajax({
                    url: url,
                    type: 'get',
                }).then(function(response) {
                    console.log(response.data);
                    if(!response.data) {
                        $('.m-img-preview ').attr('src', '');
                    }
                    $.each(response.data, function(key, val){

                        $('#foot_a').val(response.data.foot_a);
                        $('#foot_b').val(response.data.foot_b);
                        $('#foot_c').val(response.data.foot_c);
                        $('#foot_d').val(response.data.foot_d);
                        $('#foot_e').val(response.data.foot_e);
                        $('#foot_f').val(response.data.foot_f);

                        if(key === 'measurements_image') {
                            $('[name="measurements_image_h"]').val(val);
                            if(val) {
                                $('.m-img-preview').attr('src', imgUrl+"/"+val).show();
                                $('.m-img-preview').parent().attr('href', imgUrl+"/"+val).show();

                            }
                        }
                        else {
                            $('[name="'+key+'"]').val(val);
                        }
                    })
                }).catch(function(error){
                    console.log(error);
                    alert('Something went wrong!');
                });
            });
        });

        var videoIframe = document.getElementById('video-frame');
        // Create a new div for the watermark
        var watermark = document.createElement('div');
        watermark.style.position = 'absolute';
        watermark.style.top = '10px';
        watermark.style.width = '80%';
        watermark.style.height = '80%';
        watermark.style.backgroundImage = "url('{{ asset('public/taylor-made-watermark.png') }}')";
        watermark.style.backgroundSize = 'cover';
        watermark.style.backgroundRepeat = 'no-repeat';
        watermark.style.zIndex = '999';
        watermark.setAttribute('id','play-video');

        watermark.addEventListener("click",function(){
            // document.getElementById("video-frame").src += "&autoplay=1";
         let iframe = document.getElementById("video-frame");
         let video_provider = iframe.getAttribute('data-video-player');
         if (video_provider == 'youtube') {
            document.getElementById("video-frame").src += "&autoplay=1";
         } else if(video_provider == 'dailymotion') {
            document.getElementById("video-frame").src += "?autoplay=1";
         }
        })

        console.log(videoIframe);
        // Add the watermark to the iframe container
        videoIframe.parentNode.insertBefore(watermark, videoIframe);

    </script>

@if (get_setting('google_map') == 0)
@include('frontend.partials.google_map')
@endif
@endsection
