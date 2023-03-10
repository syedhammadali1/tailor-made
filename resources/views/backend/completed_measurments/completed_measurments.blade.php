@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Completed Measures') }}</h1>
        </div>
    </div>
</div>
<div class="row gutters-10">
    <div class="card col-md-12">
        @if(session('success_message'))
            <div class="alert alert-success">
                <h5>{{ session('success_message') }}</h5>
            </div>
        @endif
        @if (count($appointments) > 0)
            <div class="card-body p-3">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Customer')}}</th>
                            <th data-breakpoints="lg">{{ translate('Measurer')}}</th>
                            <th data-breakpoints="lg">{{ translate('Price')}}</th>
                            <th data-breakpoints="lg">{{ translate('Status')}}</th>
                            <th data-breakpoints="lg">{{ translate('Share Measurement')}}</th>
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
                                        @if ($appointment->user_id != '')
                                        {{@$appointment->user->name}}
                                        @else
                                        {{@$appointment->request->customer->name}}
                                        @endif
                                        {{-- {{ $appointment->product->name }} --}}
                                    </td>


                                    <td>
                                    @if (@$appointment->measurer != null)
                                        {{ @$appointment->measurer->name }}


                                    @else

                                        -
                                    @endif
                                    </td>


                                    <td>
                                        {{ get_system_default_currency()->symbol.' '.$appointment->measurer_commission }}
                                    </td>

                                    <td>
                                        {{ appointment_stutus($appointment->appointment_status) }}
                                    </td>
                                    {{-- @dd($appointment->measurement->uuid) --}}
                                    @if (@$appointment->measurement->uuid != null)
                                    <td>
                                        <a class="btn m-action-btn btn-xs" href="{{route('show_user_measurement',[@$appointment->measurement->uuid])}}" >
                                            See Measurement
                                        </a>
                                    </td>
                                    @else
                                    <td>
                                      -
                                      </td>
                                    @endif



                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $appointments->links() }}
              	</div>
            </div>

        @else
            <div class="card-body p-3">
                <h5>There is no appointment</h5>
            </div>
        @endif
    </div>

</div>

@endsection

@section('modal')

@endsection


@section('script')

@endsection




