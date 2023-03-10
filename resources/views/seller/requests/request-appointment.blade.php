@extends('seller.layouts.app')

@section('panel_content')

    <div class="card">

        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Appointment') }}</h5>
            </div>

        </div>


        @if ($appointment->count() > 0)
            <div class="card-body p-3">
                <div class="col-md-6">
                    <table class="table">
                        <tbody>

                            <tr>
                                <td class="text-main text-bold">Measurer</td>


                                @if ($appointment->measurer != null)
                                <td class="text-right">
                                   {{ $appointment->measurer->name }}
                                </td>

                               @else
                                <td>

                                </td>
                               @endif
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Measurer Commission</td>
                                <td class="text-right">${{ $appointment->measurer_commission }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Date & Time</td>
                                <td class="text-right">{{ \Carbon\Carbon::parse($appointment->datetime)->format('d/m/Y h:i: a') }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Owner</td>
                                <td class="text-right">{{ $appointment->owner->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Product</td>
                                <td class="text-right">{{ $appointment->product->name }}</td>

                            </tr>
                            <tr>
                                <td class="text-main text-bold">Request</td>
                                <td class="text-right"><a href="{{ route('seller.requests.index').'?request_id='.$appointment->request_id }}">View Request</a></td>
                            </tr>

                            <tr>
                                <td class="text-main text-bold">Appointment Status</td>
                                <td class="text-right">{{ appointment_stutus($appointment->appointment_status) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        @if ($appointment->measurement)

        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Measurements') }}</h5>
            </div>

        </div>

            <div class="card-body p-3">
                <div class="col-md-6">
                    <table class="table">
                        <tbody>

                            <tr>
                                <td class="text-main text-bold">Measurement Text</td>
                                <td class="text-right">
                                    {{ $appointment->measurement->measurements_text }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Measurement Image</td>
                                <td class="text-right">
                                    <a href="{{ asset('/public/assets/img/'.$appointment->measurement->measurements_image) }}"  data-lightbox="photo">
                                        <img src="{{ asset('/public/assets/img/'.$appointment->measurement->measurements_image) }}" width="100">
                                    </a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

@endsection

@section('script')
    <script type="text/javascript">

    </script>
@endsection
