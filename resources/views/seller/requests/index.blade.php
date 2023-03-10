@extends('seller.layouts.app')

@section('panel_content')

    <div class="card">
        <form id="sort_orders" action="" method="GET">
          <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
              <h5 class="mb-md-0 h6">{{ translate('Requests') }}</h5>
            </div>



              {{-- <div class="col-md-3">
                <div class="from-group mb-0">
                    <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type Order code & hit Enter') }}">
                </div>
              </div> --}}
          </div>
        </form>

        @if (count($requests) > 0)
            <div class="card-body p-3">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Product')}}</th>
                            <th data-breakpoints="lg">{{ translate('Customer')}}</th>
                            <th data-breakpoints="lg">{{ translate('Quantity')}}</th>
                            <th data-breakpoints="md">{{ translate('Price')}}</th>
                            <th data-breakpoints="lg">{{ translate('Status')}}</th>
                            <th data-breakpoints="lg">{{ translate('Requirement By Customer')}}</th>

                            <th data-breakpoints="lg">{{ translate('Options')}}</th>

                            <th data-breakpoints="lg">{{ translate('Nearby Measurers')}}</th>
                            
                            
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($requests as $key => $request)

                            @if($request != null)
                                <tr>
                                    <td>
                                        {{ $key+1 }}
                                    </td>
                                    <td>
                                        {{ $request->product->name }}
                                        @if(!$request->appointment)
                                        <span class="badge badge-inline badge-success">new</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $request->customer->name }}
                                    </td>
                                    <td>
                                        {{ $request->quantity }}


                                    </td>
                                    <td>
                                        {{ get_system_default_currency()->symbol.' '.$request->price }}
                                    </td>
                                    <td>
                                        {{ appointment_stutus($request->appointment ? $request->appointment->appointment_status : 0 ) }}
                                    </td>

                                    <td> 
                                        {{$request->product_description ?? '-'}}
                                    </td>
                                    <td>
                                        {{-- @if(!$request->appointment)
                                        <button type="button" data-toggle="modal" data-target="#find-measurer-modal"  data-request-id="{{$request->id}}" data-customer-id="{{$request->customer->id}}"  class="f-m-btn btn btn-soft-primary mr-2 find-measurer-btn fw-600" >
                                            <i class="las la-arrow-right"></i>
                                            <span class="d-none d-md-inline-block"> Find Measurer</span>
                                        </button>
                                        @endif --}}
                                        @if($request->appointment)

                                        @if (in_array($request->appointment->appointment_status,  array('5','7')))

                                        <button type="button" data-toggle="modal" data-target="#find-nearby-modal"  data-request-id="{{$request->id}}" data-customer-id="{{$request->customer->id}}" data-address-id="{{$request->address_id}}"  class="f-m-btn-nearby f-m-btn btn btn-soft-primary mr-2 find-measurer-btn fw-600" >
                                            <i class="las la-arrow-right"></i>
                                            <span class="d-none d-md-inline-block"> Rebook Appointment</span>
                                        </button>
                                        @else
                                        <a href="{{ route('seller.measurer.appointment.show', $request->appointment->id) }}" class="f-m-btn btn btn-soft-primary mr-2 find-measurer-btn fw-600" >
                                            <i class="las la-arrow-right"></i>
                                            <span class="d-none d-md-inline-block"> View Appointment</span>
                                        </a>
                                        @endif





                                        @endif
                                    </td>

                                    {{-- @if (isset($request->addresses->latitude)) --}}
                                    <td>

                                        {{-- for nearby measurer --}}
                                        @if(!$request->appointment)



                                        <button type="button" data-toggle="modal" data-target="#find-nearby-modal"  data-request-id="{{$request->id}}" data-customer-id="{{$request->customer->id}}"  data-address-id="{{$request->address_id}}" class="f-m-btn-nearby btn btn-soft-primary mr-2 find-measurer-btn fw-600" >
                                            <i class="las la-arrow-right"></i>
                                            <span class="d-none d-md-inline-block"> Find Nearby</span>
                                        </button>

                                        @endif
                                        {{-- @endif --}}
                                        {{-- for nearby measurer --}}
                                    </td>
                                    {{-- @endif --}}

                                 

                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $requests->links() }}
              	</div>
            </div>
        @endif
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }
        $(document).on('click', '.f-m-btn', function(){

            var requestID = $(this).data('request-id');


            $('#find-measurer-modal [name="request_id"]').val(requestID);
        });



        $(document).on('click', '.f-m-btn-nearby', function(){


        var requestID = $(this).data('request-id');
        var customerID = $(this).data('customer-id');
        var addressID = $(this).data('address-id');


        var url = "{{ route('seller.requests.nearby_measurers', ":customerID") }}";
        url = url.replace(':id', customerID);

        $.ajax({
            type:'GET',
            url: url,
            data:{id:requestID,user_id:customerID,address_id:addressID},
            success:function(data){
                $('#measurer-dropdown').html('');
                $("#measurer-dropdown").append('<option value="' + {!! auth()->id() !!} + '">  Book Myself </option>');
                    $.each(data.measurers, function (key, value) {

                        $("#measurer-dropdown").append('<option value="' + value
                            .user_id + '">' + value.name + '    ( '  +value.distance.toFixed(2)+' Km )</option>');

                    });


            }
        });


    //  alert(customerID);
    $('#find-measurer-modal [name="request_id"]').val(requestID);

    //set value for request_id
    $('#request_id').val(requestID);




});
    </script>
@endsection

<div class="modal fade" id="find-measurer-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">Find Measurer</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">

                <div class="p-3">
                    <form class="form-default find-measurer-form" role="form" action="{{ route('seller.measurer.conversations.create') }}" method="post">
                        @csrf

                        <div class="row">
                            <div class="col-md-2">
                                <label>Find Measurer</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="Select your measurer" name="measurer_id" required>
                                        <option value="{{ auth()->id() }}"> Book Myself</option>
                                        @if($measurers->count() > 0)
                                            @foreach($measurers as $measurer)
                                            <option value="{{ $measurer->id }}">{{ $measurer->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>




                        <div class="mb-5">
                            <input type="hidden" name="request_id">
                            <button type="submit" class="btn btn-primary btn-block fw-600">Start Conversation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="find-nearby-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">Find Nearby Measurer</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">

                <div class="p-3">
                    <form class="form-default find-measurer-form" role="form" action="{{ route('seller.measurer.conversations.create') }}" method="post">
                        @csrf

                        <div class="row">
                            <div class="col-md-2">
                                <label>Nearby Measurers</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control"  name="measurer_id" id="measurer-dropdown" required>
                                        <option value="{{ auth()->id() }}"> Book Myself</option>
                                    </select>
                                </div>
                            </div>
                        </div>




                        <div class="mb-5">
                            <input type="hidden" name="request_id" id="request_id"  >
                            <button type="submit" class="btn btn-primary btn-block fw-600">Start Conversation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
