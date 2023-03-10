@extends('seller.layouts.app')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Manage Profile') }}</h1>
        </div>
        <div class="col-md-4">
          <img src="{{Auth::user()->country_flag}}" alt="">
        </div>
    </div>
</div>
<form action="{{ route('seller.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    <input name="_method" type="hidden" value="POST">
    @csrf
    <!-- Basic Info-->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Basic Info')}}</h5>

            <a href="#" class="btn btn-primary" data-toggle="modal"
                data-target="#premium_modal">{{translate('Become a Premium')}}</a>

            @section('modal')
            <div class="modal fade" id="premium_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title strong-600 heading-5">{{ translate('Request to be a Premium Seller')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body px-3 pt-3">
                            <form class="" action="{{route('seller.membership_request_store')}}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                <div class="col-md-4">
                                <select name="profile_type_id" id="profile_type_id" class="form-control aiz-selectpicker" required>
                                    @foreach ($profile_type as $item)

                                    <option value="{{$item->id}}">{{$item->profile_type}}</option>

                                    @endforeach
                                </select>
                                </div>


                                <div class="col-md-4">
                                <div class="form-group row">
                                    <button type="submit" class="btn btn-primary">{{ translate('Request')}}</button>
                                </div>
                                </div>

                            </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endsection
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="name">{{ translate('Your Name') }}</label>
                <div class="col-md-10">
                    <input type="text" name="name" value="{{ $user->name }}" id="name" class="form-control"
                        placeholder="{{ translate('Your Name') }}" required>
                    @error('name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="phone">{{ translate('Your Phone') }}</label>
                <div class="col-md-10">
                    <input type="text" name="phone" value="{{ $user->phone }}" id="phone" class="form-control"
                        placeholder="{{ translate('Your Phone')}}">
                    @error('phone')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label">{{ translate('Photo') }}</label>
                <div class="col-md-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}
                            </div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="photo" value="{{ $user->avatar_original }}" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="password">{{ translate('Your Password') }}</label>
                <div class="col-md-10">
                    <input type="password" name="new_password" id="password" class="form-control"
                        placeholder="{{ translate('New Password') }}">
                    @error('new_password')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label"
                    for="confirm_password">{{ translate('Confirm Password') }}</label>
                <div class="col-md-10">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                        placeholder="{{ translate('Confirm Password') }}">
                    @error('confirm_password')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="profile_type">{{ translate('Select Profile Type') }}</label>
                <div class="col-md-10">
                    <select name="profile_type" id="profile_type" class="form-control aiz-selectpicker" required>
                        @foreach ($profile_type as $item)

                        <option value="{{$item->id}}" @if (@$user->userProfileById->profile_id == $item->id) selected @endif>{{$item->profile_type}}</option>

                        @endforeach
                    </select>
                    @error('profile_type')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="card-body" id="profile_div">

                @foreach ($user_profiles as $item)



                <div class="form-group row">
                    <h3> {{$item->profile->profile_name}} <h3><input type="hidden" id="profile_id" name="profile_id[]"
                                value="{{$item->profile->id}} ">
                </div>

                @endforeach
            </div>


        </div>
    </div>

    <!-- Payment System -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Bank Account Verification')}}</h5>
        </div>
        <div class="card-body">
            {{-- <div class="row">
                    <label class="col-md-3 col-form-label">{{ translate('Cash Payment') }}</label>
            <div class="col-md-9">
                <label class="aiz-switch aiz-switch-success mb-3">
                    <input value="1" name="cash_on_delivery_status" type="checkbox" @if
                        ($user->shop->cash_on_delivery_status == 1) checked @endif>
                    <span class="slider round"></span>
                </label>
            </div>
        </div> --}}
        {{-- <div class="row">
                    <label class="col-md-3 col-form-label">{{ translate('Bank Payment') }}</label>
        <div class="col-md-9">
            <label class="aiz-switch aiz-switch-success mb-3">
                <input value="1" name="bank_payment_status" type="checkbox" @if ($user->shop->bank_payment_status == 1)
                checked @endif>
                <span class="slider round"></span>
            </label>
        </div>
    </div> --}}
    <div class="row">
        <label class="col-md-3 col-form-label" for="bank_name">{{ translate('Bank Name') }}</label>
        <div class="col-md-9">
            <input type="text" name="bank_name" value="{{ $user->shop->bank_name }}" id="bank_name"
                class="form-control mb-3" placeholder="{{ translate('Bank Name')}}">
            @error('phone')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <div class="row">
        <label class="col-md-3 col-form-label" for="bank_acc_name">{{ translate('Bank Account Name') }}</label>
        <div class="col-md-9">
            <input type="text" name="bank_acc_name" value="{{ $user->shop->bank_acc_name }}" id="bank_acc_name"
                class="form-control mb-3" placeholder="{{ translate('Bank Account Name')}}">
            @error('bank_acc_name')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <div class="row">
        <label class="col-md-3 col-form-label" for="bank_acc_no">{{ translate('Bank Account Number') }}</label>
        <div class="col-md-9">
            <input type="text" name="bank_acc_no" value="{{ $user->shop->bank_acc_no }}" id="bank_acc_no"
                class="form-control mb-3" placeholder="{{ translate('Bank Account Number')}}">
            @error('bank_acc_no')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    {{-- <div class="row">
                    <label class="col-md-3 col-form-label" for="bank_routing_no">{{ translate('Bank Routing Number') }}</label>
    <div class="col-md-9">
        <input type="number" name="bank_routing_no" value="{{ $user->shop->bank_routing_no }}" id="bank_routing_no"
            lang="en" class="form-control mb-3" placeholder="{{ translate('Bank Routing Number')}}">
        @error('bank_routing_no')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    </div> --}}
    </div>
    </div>

    <div class="form-group mb-0 text-right">
        <button type="submit" class="btn btn-primary">{{translate('Update Profile')}}</button>
    </div>
</form>

<br>

<!-- Address -->
{{-- <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Address')}}</h5>
</div>
<div class="card-body">
    <div class="row gutters-10">
        @foreach ($addresses as $key => $address)
        <div class="col-lg-4">
            <div class="border p-3 pr-5 rounded mb-3 position-relative">
                <div>
                    <span class="w-50 fw-600">{{ translate('Address') }}:</span>
                    <span class="ml-2">{{ $address->address }}</span>
                </div>
                <div>
                    <span class="w-50 fw-600">{{ translate('Postal Code') }}:</span>
                    <span class="ml-2">{{ $address->postal_code }}</span>
                </div>
                <div>
                    <span class="w-50 fw-600">{{ translate('City') }}:</span>
                    <span class="ml-2">{{ optional($address->city)->name }}</span>
                </div>
                <div>
                    <span class="w-50 fw-600">{{ translate('State') }}:</span>
                    <span class="ml-2">{{ optional($address->state)->name }}</span>
                </div>
                <div>
                    <span class="w-50 fw-600">{{ translate('Country') }}:</span>
                    <span class="ml-2">{{ optional($address->country)->name }}</span>
                </div>
                <div>
                    <span class="w-50 fw-600">{{ translate('Phone') }}:</span>
                    <span class="ml-2">{{ $address->phone }}</span>
                </div>
                @if ($address->set_default)
                <div class="position-absolute right-0 bottom-0 pr-2 pb-3">
                    <span class="badge badge-inline badge-primary">{{ translate('Default') }}</span>
                </div>
                @endif
                <div class="dropdown position-absolute right-0 top-0">
                    <button class="btn bg-gray px-2" type="button" data-toggle="dropdown">
                        <i class="la la-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" onclick="edit_address('{{$address->id}}')">
                            {{ translate('Edit') }}
                        </a>
                        @if (!$address->set_default)
                        <a class="dropdown-item"
                            href="{{ route('seller.addresses.set_default', $address->id) }}">{{ translate('Make This Default') }}</a>
                        @endif
                        <a class="dropdown-item"
                            href="{{ route('seller.addresses.destroy', $address->id) }}">{{ translate('Delete') }}</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-lg-4 mx-auto" onclick="add_new_address()">
            <div class="border p-3 rounded mb-3 c-pointer text-center bg-light">
                <i class="la la-plus la-2x"></i>
                <div class="alpha-7">{{ translate('Add New Address') }}</div>
            </div>
        </div>
    </div>
</div>
</div> --}}

<!-- Change Email -->
<form action="{{ route('user.change.email') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Change your email')}}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <label>{{ translate('Your Email') }}</label>
                </div>
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="{{ translate('Your Email')}}" name="email"
                            value="{{ $user->email }}" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary new-email-verification">
                                <span class="d-none loading">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>{{ translate('Sending Email...') }}
                                </span>
                                <span class="default">{{ translate('Verify') }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Update Email')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('modal')
{{-- New Address Modal --}}
<div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-default" role="form" action="{{ route('seller.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Address')}}</label>
                            </div>
                            <div class="col-md-10">
                                <textarea class="form-control mb-3" placeholder="{{ translate('Your Address')}}"
                                    rows="2" name="address" required></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Country')}}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        data-placeholder="{{ translate('Select your country') }}" name="country_id"
                                        required>
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
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true"
                                    name="state_id" required>

                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('City')}}</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true"
                                    name="city_id" required>

                                </select>
                            </div>
                        </div>

                        @if (get_setting('google_map') == 1)
                        <div class="row">
                            <input id="searchInput" class="controls" type="text"
                                placeholder="{{translate('Enter a location')}}">
                            <div id="map"></div>
                            <ul id="geoData">
                                <li style="display: none;">{{ translate('Full Address') }}: <span id="location"></span>
                                </li>
                                <li style="display: none;">{{ translate('Postal Code') }}: <span
                                        id="postal_code"></span></li>
                                <li style="display: none;">{{ translate('Country') }}: <span id="country"></span></li>
                                <li style="display: none;">{{ translate('Latitude') }}: <span id="lat"></span></li>
                                <li style="display: none;">{{ translate('Longitude') }}: <span id="lon"></span></li>
                            </ul>
                        </div>

                        <div class="row">
                            <div class="col-md-2" id="">
                                <label for="exampleInputuname">{{ translate('Longitude') }}</label>
                            </div>
                            <div class="col-md-10" id="">
                                <input type="text" class="form-control mb-3" id="longitude" name="longitude"
                                    readonly="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" id="">
                                <label for="exampleInputuname">{{ translate('Latitude') }}</label>
                            </div>
                            <div class="col-md-10" id="">
                                <input type="text" class="form-control mb-3" id="latitude" name="latitude" readonly="">
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Postal code')}}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3"
                                    placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value=""
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Phone')}}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3" placeholder="{{ translate('+880')}}"
                                    name="phone" value="" required>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Address Modal --}}
<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="edit_modal_body">

            </div>
        </div>
    </div>
</div>

@endsection

@section('script')


@endsection
