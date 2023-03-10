@extends('frontend.layouts.user_panel')

@section('panel_content')
{{-- @dd($appointments) --}}
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Appointments') }}</h1>
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
        @if (count(@$appointments) > 0)
        <div class="card-body p-3">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Model')}}</th>
                        <th data-breakpoints="lg">{{ translate('Seller')}}</th>
                        <th data-breakpoints="lg">{{ translate('Price')}}</th>
                        <th data-breakpoints="lg">{{ translate('Status')}}</th>
                        <th data-breakpoints="lg">{{ translate('action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $key => $appointment)

                    @if(@$appointment != null)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ @$appointment->model->name }}
                        </td>
                        <td>
                            {{ @$appointment->seller->name }}
                        </td>

                        <td>
                            {{ get_system_default_currency()->symbol.' '.@$appointment->model_commission }}
                        </td>

                        <td>
                            {{ request_to_be_model_stutus(@$appointment->request_status) }}
                        </td>

                        <td>

                                <div class="dropdown">
                                    @if($appointment->request_status == 2 )
                                    <p>Rejected</p>
                                    @elseif($appointment->request_status == 3)
                                    <p>Completed </p>
                                    @else
                                    <button class="btn m-action-btn btn-xs dropdown-toggle " type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    @endif
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @if($appointment->request_status == 0)
                                            <a href="{{ route('update_request_model_status',['request' => @$appointment->id ,'status' => 1])}}" class="dropdown-item">Accept</a>
                                            <a href="{{ route('update_request_model_status',['request' => @$appointment->id ,'status' => 2])}}" class="dropdown-item" >Reject</a>

                                        @elseif ($appointment->request_status == 1)
                                        <a class="dropdown-item" href="{{ route('update_request_model_status',['request' => @$appointment->id ,'status' => 3])}}">Mark as Complete</a>
                                        @endif


                                    </div>
                                </div>

                        </td>

                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ @$appointments->links() }}
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
