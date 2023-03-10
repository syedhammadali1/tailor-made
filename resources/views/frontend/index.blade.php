@extends('frontend.layouts.home-layout')

@section('content')
    {{-- Categories , Sliders . Today's deal --}}
    <div class="home-banner-area mb-4 pt-3">
        <div class="container">
            <div class="row gutters-10 position-relative">
                <div class="col-lg-3 position-static d-none d-lg-block">
                    @include('frontend.partials.category_menu')
                </div>

                @php
                    $num_todays_deal = count($todays_deal_products);
                @endphp

                <div class="@if($num_todays_deal > 0) col-lg-7 @else col-lg-9 @endif">
                    @if (get_setting('home_slider_images') != null)
                        <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height" data-arrows="true" data-dots="true" data-autoplay="true">
                            @php $slider_images = json_decode(get_setting('home_slider_images'), true);  @endphp
                            @foreach ($slider_images as $key => $value)
                                <div class="carousel-box">
                                    <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
                                        <img
                                            class="d-block mw-100 img-fit rounded shadow-sm overflow-hidden"
                                            src="{{ uploaded_asset($slider_images[$key]) }}"
                                            alt="{{ env('APP_NAME')}} promo"
                                            @if(count($featured_categories) == 0)
                                            height="457"
                                            @else
                                            height="315"
                                            @endif
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';"
                                        >
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if (count($featured_categories) > 0)
                        <ul class="list-unstyled mb-0 row gutters-5">
                            @foreach ($featured_categories as $key => $category)
                                <li class="minw-0 col-4 col-md mt-3">
                                    <a href="{{ route('products.category', $category->slug) }}" class="d-block rounded bg-white p-2 text-reset shadow-sm">
                                        <img
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($category->banner) }}"
                                            alt="{{ $category->getTranslation('name') }}"
                                            class="lazyload img-fit"
                                            height="78"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';"
                                        >
                                        <div class="text-truncate fs-12 fw-600 mt-2 opacity-70">{{ GoogleTranslate::trans($category->getTranslation('name'),session()->get('localelang')) }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @if($num_todays_deal > 0)
                <div class="col-lg-2 order-3 mt-3 mt-lg-0">
                    <div class="bg-white rounded shadow-sm">
                        <div class="bg-soft-primary rounded-top p-3 d-flex align-items-center justify-content-center">
                            <span class="fw-600 fs-16 mr-2 text-truncate">
                                {{  GoogleTranslate::trans('Todays Deal',session()->get('localelang')) }}
                            </span>
                            <span class="badge badge-primary badge-inline">{{  GoogleTranslate::trans('Hot',session()->get('localelang')) }}</span>
                        </div>
                        <div class="c-scrollbar-light overflow-auto h-lg-400px p-2 bg-primary rounded-bottom">
                            <div class="gutters-5 lg-no-gutters row row-cols-2 row-cols-lg-1">
                            @foreach ($todays_deal_products as $key => $product)
                                @if ($product != null)
                                <div class="col mb-2">
                                    <a href="{{ route('product', $product->slug) }}" class="d-block p-2 text-reset bg-white h-100 rounded">
                                        <div class="row gutters-5 align-items-center">
                                            <div class="col-xxl">
                                                <div class="img">
                                                    <img
                                                        class="lazyload img-fit h-140px h-lg-80px"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                        alt="{{ $product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-xxl">
                                                <div class="fs-16">
                                                    <span class="d-block text-primary fw-600">{{ home_discounted_base_price($product) }}</span>
                                                    @if(home_base_price($product) != home_discounted_base_price($product))
                                                        <del class="d-block opacity-70">{{ home_base_price($product) }}</del>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>


    {{-- Banner section 1 --}}


    <div class="content_main_wrapper">
        <div class="container">
            <div class="content_inner_wrapper row">
                <div class="content_left_col col-lg-3 ">
                    {{-- Discount banner Start --}}
                    @php $banner_1_imags = json_decode(get_setting('home_banner1_images')); @endphp
                    @if (get_setting('home_banner1_images') != null)
                    @foreach ($banner_1_imags as $key => $value)
                    <div class="dis_banner_top_wrapper">
                        <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}" class="d-block text-reset">
                            <div class="dis_top_banner_bg" style="background-image: url({{ uploaded_asset($banner_1_imags[$key]) }})"></div>
                        </a>

                    </div>
                    @endforeach
                    @endif
                    {{-- Discount Banner End --}}

                    {{-- Product Section Start --}}
                    <div class="pro_left_wrapper">
                        <div class="d-inline-block d-md-block ">
                            <div class="d-flex mb-3 align-items-baseline border-bottom">
                                <h3 class="h5 fw-700 mb-0">
                                    <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block"> {{GoogleTranslate::trans('Measurments',session()->get('localelang'))}}</span>
                                </h3>
                            </div>
                            <form class="d-flex flex-nowrap mb-4" method="POST" action="{{ route('search.measurment') }}">
                                @csrf
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" placeholder="{{  GoogleTranslate::trans('Enter Id',session()->get('localelang')) }}" name="uuid"
                                        required>
                                </div>

                                <button class="btn btn-primary" type="submit" fdprocessedid="mgt5ko">
                                    <i class="la la-search la-flip-horizontal fs-18"></i>
                                </button>
                            </form>
                        </div>


                        <div class="d-flex mb-3 align-items-baseline border-bottom">
                            <h3 class="h5 fw-700 mb-0">
                                <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block"> {{GoogleTranslate::trans('Latest Products',session()->get('localelang'))}}</span>
                            </h3>
                        </div>
                            <div class="lef_pro_List_wrapper">
                                @forelse ($latest_products as $item)
                                <div class="left_pro_item_wrapper">
                                    <div class="left_pro_bg" style="background-image: url({{ uploaded_asset($item->thumbnail_img) }})" ></div>
                                    <div class="left_pro_content">
                                        <div class="rating rating-sm mt-1">
                                            {{ renderStarRating($item->rating) }}
                                        </div>
                                        <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0">
                                            <a href="{{route('product', $item->slug)}}" class="d-block text-reset" tabindex="0">{{$item->name}}</a>
                                        </h3>
                                        <div class="fs-15 pro_price">
                                            <span class="fw-700 text-primary cut_price">{{ home_base_price($item) }}</span>
                                            <span class="fw-700 text-primary">{{ home_discounted_base_price($item) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @empty


                                @endforelse
                            </div>

                    </div>
                    {{-- Product Section End --}}

                    {{-- Banner Two Start --}}
                    @if (get_setting('home_banner2_images') != null)
                    <div class="banner_two_main_wrapper">
                        @php $banner_2_imags = json_decode(get_setting('home_banner2_images')); @endphp
                        @foreach ($banner_2_imags as $key => $value)
                        <div class="dis_banner_top_wrapper">
                            <a href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}" class="d-block text-reset">
                                <div class="banner_two" style="background-image: url({{ uploaded_asset($banner_2_imags[$key]) }})"></div>
                            </a>

                        </div>

                        @endforeach


                    </div>
                    @endif
                    {{-- Banner Two End --}}

                    {{-- Icon Items Start --}}
                    <div class="icon_main_wrapper">
                        <div class="icon_items_wrapper">
                            <div class="icon_bg" style="background-image: url({{ static_asset('assets/img/free-shipping.png') }})"></div>
                            <div class="icon_content">
                                <h3 class="icon_title"> {{GoogleTranslate::trans('free shipping',session()->get('localelang'))}}</h3>
                                <p class="icon_desc">{{ GoogleTranslate::trans('Lorem ipsum dolor, sit amet.',session()->get('localelang'))}}</p>
                            </div>
                        </div>
                        <div class="icon_items_wrapper">
                            <div class="icon_bg" style="background-image: url({{ static_asset('assets/img/shield.png') }})"></div>
                            <div class="icon_content">
                                <h3 class="icon_title"> {{GoogleTranslate::trans('Lorem ipsum dolor',session()->get('localelang'))}}</h3>
                                <p class="icon_desc">{{ GoogleTranslate::trans('Lorem ipsum dolor, sit amet.',session()->get('localelang'))}}</p>
                            </div>
                        </div>
                        <div class="icon_items_wrapper">
                            <div class="icon_bg" style="background-image: url({{ static_asset('assets/img/gift.png') }})"></div>
                            <div class="icon_content">
                                <h3 class="icon_title"> {{GoogleTranslate::trans('promotion gifts',session()->get('localelang'))}}</h3>
                                <p class="icon_desc">{{ GoogleTranslate::trans('Lorem ipsum dolor, sit amet.',session()->get('localelang'))}}</p>
                            </div>
                        </div>
                        <div class="icon_items_wrapper">
                            <div class="icon_bg" style="background-image: url({{ static_asset('assets/img/money.png') }})"></div>
                            <div class="icon_content">
                                <h3 class="icon_title"> {{GoogleTranslate::trans('money back',session()->get('localelang'))}}</h3>
                                <p class="icon_desc">{{ GoogleTranslate::trans('Lorem ipsum dolor, sit amet.',session()->get('localelang'))}}</p>
                            </div>
                        </div>
                    </div>
                    {{-- Icon Items End --}}


                </div>
                <div class="content_right_col col-lg-9 p-0">

                    {{-- Crazy Sunday --}}
                    {{--    @if($crazy_sunday)--}}
                    <section class="mb-4">
                        <div class="container">
                            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">

                                <div class="d-flex flex-wrap mb-3 align-items-baseline border-bottom">
                                    <h3 class="h5 fw-700 mb-0">
                                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{  GoogleTranslate::trans('Crazy Sunday',session()->get('localelang')) }}</span>
                                    </h3>
                                </div>

                                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                                    @foreach ($crazy_sunday_products as $key => $product)
                                        <div class="carousel-box">
                                            @include('frontend.partials.product_box_1',['product' => $product])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>
                    {{--    @endif--}}


                    {{-- Personalise Product--}}
                    {{--    @if($is_personalise)--}}
                    <section class="mb-4">
                        <div class="container">
                            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">

                                <div class="d-flex flex-wrap mb-3 align-items-baseline border-bottom">
                                    <h3 class="h5 fw-700 mb-0">
                                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{  GoogleTranslate::trans('Personalise Products',session()->get('localelang')) }}</span>
                                    </h3>
                                </div>

                                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                                    @foreach ($personalise_products as $key => $product)
                                        <div class="carousel-box">
                                            @include('frontend.partials.product_box_1',['product' => $product])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>
                    {{--    @endif--}}


                    {{-- Flash Deal --}}
                    @php
                        $flash_deal = \App\Models\FlashDeal::where('status', 1)->where('featured', 1)->first();
                    @endphp
                    @if($flash_deal != null && strtotime(date('Y-m-d H:i:s')) >= $flash_deal->start_date && strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date)
                        <section class="mb-4">
                            <div class="container">
                                <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">

                                    <div class="d-flex flex-wrap mb-3 align-items-baseline border-bottom">
                                        <h3 class="h5 fw-700 mb-0">
                                            <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{  GoogleTranslate::trans('Flash Sale',session()->get('localelang')) }}</span>
                                        </h3>
                                        <div class="aiz-count-down ml-auto ml-lg-3 align-items-center" data-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                                        <a href="{{ route('flash-deal-details', $flash_deal->slug) }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md w-100 w-md-auto">{{  GoogleTranslate::trans('View More',session()->get('localelang')) }}</a>
                                    </div>

                                    <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                                        @foreach ($flash_deal->flash_deal_products->take(20) as $key => $flash_deal_product)
                                            @php
                                                $product = \App\Models\Product::find($flash_deal_product->product_id);
                                            @endphp
                                            @if ($product != null && $product->published != 0)
                                                <div class="carousel-box">
                                                    @include('frontend.partials.product_box_1',['product' => $product])
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif

                    <div id="section_newest">
                        @if (count($newest_products) > 0)
                            <section class="mb-4">
                                <div class="container">
                                    <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                                        <div class="d-flex mb-3 align-items-baseline border-bottom">
                                            <h3 class="h5 fw-700 mb-0">
                                                <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">
                                                    {{  GoogleTranslate::trans('New Products',session()->get('localelang')) }}
                                                </span>
                                            </h3>
                                        </div>
                                        <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                                            @foreach ($newest_products as $key => $new_product)
                                            <div class="carousel-box">
                                                @include('frontend.partials.product_box_1',['product' => $new_product])
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>

                    {{-- Featured Section --}}
                    <div id="section_featured">

                    </div>

                    {{-- Best Selling  --}}
                    <div id="section_best_selling">

                    </div>

                    <!-- Auction Product -->
                    @if(addon_is_activated('auction'))
                        <div id="auction_products">

                        </div>
                    @endif



                    {{-- Banner Section 2 --}}


                    {{-- Category wise Products --}}
                    <div id="section_home_categories">

                    </div>

                    {{-- Classified Product --}}
                    @if(get_setting('classified_product') == 1)
                        @php
                            $classified_products = \App\Models\CustomerProduct::where('status', '1')->where('published', '1')->take(10)->get();
                        @endphp
                        @if (count($classified_products) > 0)
                            <section class="mb-4">
                                <div class="container">
                                    <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                                            <div class="d-flex mb-3 align-items-baseline border-bottom">
                                                <h3 class="h5 fw-700 mb-0">
                                                    <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{  GoogleTranslate::trans('Classified Ads',session()->get('localelang')) }}</span>
                                                </h3>
                                                <a href="{{ route('customer.products') }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{  GoogleTranslate::trans('View More',session()->get('localelang')) }}</a>
                                            </div>
                                        <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                                            @foreach ($classified_products as $key => $classified_product)
                                                <div class="carousel-box">
                                                        <div class="aiz-card-box border border-light rounded hov-shadow-md my-2 has-transition">
                                                            <div class="position-relative">
                                                                <a href="{{ route('customer.product', $classified_product->slug) }}" class="d-block">
                                                                    <img
                                                                        class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                        data-src="{{ uploaded_asset($classified_product->thumbnail_img) }}"
                                                                        alt="{{ $classified_product->getTranslation('name') }}"
                                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                                    >
                                                                </a>
                                                                <div class="absolute-top-left pt-2 pl-2">
                                                                    @if($classified_product->conditon == 'new')
                                                                    <span class="badge badge-inline badge-success">{{ GoogleTranslate::trans('new',session()->get('localelang'))}}</span>
                                                                    @elseif($classified_product->conditon == 'used')
                                                                    <span class="badge badge-inline badge-danger">{{ GoogleTranslate::trans('Used',session()->get('localelang'))}}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="p-md-3 p-2 text-left">
                                                                <div class="fs-15 mb-1">
                                                                    <span class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                                                </div>
                                                                <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                                                    <a href="{{ route('customer.product', $classified_product->slug) }}" class="d-block text-reset">{{ $classified_product->getTranslation('name') }}</a>
                                                                </h3>
                                                            </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                    @endif

                    {{-- Banner Section 2 --}}
                    @if (get_setting('home_banner3_images') != null)
                    <div class="mb-4">
                        <div class="container">
                            <div class="row gutters-10">
                                @php $banner_3_imags = json_decode(get_setting('home_banner3_images')); @endphp
                                @foreach ($banner_3_imags as $key => $value)
                                    <div class="col-xl col-md-6">
                                        <div class="mb-3 mb-lg-0">
                                            <a href="{{ json_decode(get_setting('home_banner3_links'), true)[$key] }}" class="d-block text-reset">
                                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($banner_3_imags[$key]) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100">
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Best Seller --}}
                    <div id="section_best_sellers">

                    </div>

                    {{-- Top 10 categories and Brands --}}
                    @if (get_setting('top10_categories') != null && get_setting('top10_brands') != null)
                    <section class="mb-4">
                        <div class="container">
                            <div class="row gutters-10">
                                @if (get_setting('top10_categories') != null)
                                    <div class="col-lg-6">
                                        <div class="d-flex mb-3 align-items-baseline border-bottom">
                                            <h3 class="h5 fw-700 mb-0">
                                                <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{  GoogleTranslate::trans('Top 10 Categories',session()->get('localelang')) }}</span>
                                            </h3>
                                            <a href="{{ route('categories.all') }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{  GoogleTranslate::trans('View All Categories',session()->get('localelang')) }}</a>
                                        </div>
                                        <div class="row gutters-5">
                                            @php $top10_categories = json_decode(get_setting('top10_categories')); @endphp
                                            @foreach ($top10_categories as $key => $value)
                                                @php $category = \App\Models\Category::find($value); @endphp
                                                @if ($category != null)
                                                    <div class="col-sm-6">
                                                        <a href="{{ route('products.category', $category->slug) }}" class="bg-white border d-block text-reset rounded p-2 hov-shadow-md mb-2">
                                                            <div class="row align-items-center no-gutters">
                                                                <div class="col-3 text-center">
                                                                    <img
                                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                        data-src="{{ uploaded_asset($category->banner) }}"
                                                                        alt="{{ $category->getTranslation('name') }}"
                                                                        class="img-fluid img lazyload h-60px"
                                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                                    >
                                                                </div>
                                                                <div class="col-7">
                                                                    <div class="text-truncat-2 pl-3 fs-14 fw-600 text-left">{{ $category->getTranslation('name') }}</div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <i class="la la-angle-right text-primary"></i>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if (get_setting('top10_brands') != null)
                                    <div class="col-lg-6">
                                        <div class="d-flex mb-3 align-items-baseline border-bottom">
                                            <h3 class="h5 fw-700 mb-0">
                                                <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{  GoogleTranslate::trans('Top 10 Brands',session()->get('localelang')) }}</span>
                                            </h3>
                                            <a href="{{ route('brands.all') }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{  GoogleTranslate::trans('View All Brands',session()->get('localelang')) }}</a>
                                        </div>
                                        <div class="row gutters-5">
                                            @php $top10_brands = json_decode(get_setting('top10_brands')); @endphp
                                            @foreach ($top10_brands as $key => $value)
                                                @php $brand = \App\Models\Brand::find($value); @endphp
                                                @if ($brand != null)
                                                    <div class="col-sm-6">
                                                        <a href="{{ route('products.brand', $brand->slug) }}" class="bg-white border d-block text-reset rounded p-2 hov-shadow-md mb-2">
                                                            <div class="row align-items-center no-gutters">
                                                                <div class="col-4 text-center">
                                                                    <img
                                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                        data-src="{{ uploaded_asset($brand->logo) }}"
                                                                        alt="{{ $brand->getTranslation('name') }}"
                                                                        class="img-fluid img lazyload h-60px"
                                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                                    >
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="text-truncate-2 pl-3 fs-14 fw-600 text-left">{{ $brand->getTranslation('name') }}</div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <i class="la la-angle-right text-primary"></i>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $.post('{{ route('home.section.featured') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_featured').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.best_selling') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_best_selling').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.auction_products') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#auction_products').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.home_categories') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.best_sellers') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_best_sellers').html(data);
                AIZ.plugins.slickCarousel();
            });
        });
    </script>
@endsection
