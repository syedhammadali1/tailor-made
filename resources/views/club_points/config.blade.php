@extends('backend.layouts.app')
@section('content')

@php
    $club_point_convert_rate = \App\Models\BusinessSetting::where('type', 'club_point_convert_rate')->first();
    $club_point_registration_rate = \App\Models\BusinessSetting::where('type', 'club_point_registration_rate')->first() ?? 0;
    $club_point_review_rate = \App\Models\BusinessSetting::where('type', 'club_point_review_rate')->first() ?? 0;
    $club_point_booking_appointments_rate = \App\Models\BusinessSetting::where('type', 'club_point_booking_appointments_rate')->first() ?? 0;
@endphp
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Convert Point To Wallet')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('point_convert_rate_store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="club_point_convert_rate">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-from-label">{{translate('Set Point For ')}} {{ single_price(1) }}</label>
                            </div>
                            <div class="col-lg-5">
                                <input type="number" min="0" step="0.01" class="form-control" name="value" @if ($club_point_convert_rate != null) value="{{ $club_point_convert_rate->value }}" @endif placeholder="100" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('Points')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3 text-right">
								<button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
						</div>
                    </form>
                    <i class="fs-12"><b>{{ translate('Note: You need to activate wallet option first before using club point addon.') }}</b></i>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Registration Points')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('point_convert_rate_store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="club_point_registration_rate">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-from-label">{{translate('Set Point For Registration')}}</label>
                            </div>
                            <div class="col-lg-5">
                                <input type="number" min="0" step="0.01" class="form-control" name="value" @if ($club_point_registration_rate != null) value="{{ $club_point_registration_rate->value }}" @endif placeholder="100" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('Points')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3 text-right">
								<button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
						</div>
                    </form>
                    <i class="fs-12"><b>{{ translate('Note: You need to activate wallet option first before using club point addon.') }}</b></i>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Review Points')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('point_convert_rate_store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="club_point_review_rate">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-from-label">{{translate('Set Point For Review')}}</label>
                            </div>
                            <div class="col-lg-5">
                                <input type="number" min="0" step="0.01" class="form-control" name="value" @if ($club_point_review_rate != null) value="{{ $club_point_review_rate->value }}" @endif placeholder="100" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('Points')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3 text-right">
								<button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
						</div>
                    </form>
                    <i class="fs-12"><b>{{ translate('Note: You need to activate wallet option first before using club point addon.') }}</b></i>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Booking Appointments Points')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('point_convert_rate_store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="club_point_booking_appointments_rate">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-from-label">{{translate('Set Point For Booking Appointments')}}</label>
                            </div>
                            <div class="col-lg-5">
                                <input type="number" min="0" step="0.01" class="form-control" name="value" @if ($club_point_booking_appointments_rate != null) value="{{ $club_point_booking_appointments_rate->value }}" @endif placeholder="10" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('Points')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                    <i class="fs-12"><b>{{ translate('Note: You need to activate wallet option first before using club point addon.') }}</b></i>
                </div>
            </div>
        </div>
    </div>

@endsection
