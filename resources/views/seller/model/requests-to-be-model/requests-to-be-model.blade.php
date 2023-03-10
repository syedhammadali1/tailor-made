@extends('seller.layouts.app')

@section('panel_content')

    <div class="card">
        <form id="sort_orders" action="" method="GET">
          <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
              <h5 class="mb-md-0 h6">{{ translate('Requests') }}</h5>
            </div>
          </div>
        </form>

        @if (count($requests) > 0)
            <div class="card-body p-3">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Model')}}</th>
                            <th data-breakpoints="lg">{{ translate('Seller')}}</th>
                            <th data-breakpoints="lg">{{ translate('Status')}}</th>
                            <th data-breakpoints="lg">{{ translate('Nearby Models')}}</th>
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
                                        {{ $request->model->name }}
                                        {{-- @if(!$request->appointment)
                                        <span class="badge badge-inline badge-success">new</span>
                                        @endif --}}
                                    </td>
                                    <td>
                                        {{ $request->seller->name }}
                                        {{-- @if(!$request->appointment)
                                        <span class="badge badge-inline badge-success">new</span>
                                        @endif --}}
                                    </td>
                                    <td>
                                        {{request_to_be_model_stutus($request->request_status ) }}
                                    </td>

                                    <td>

                                        {{-- for nearby measurer --}}
                                        @if($request->request_status == 0)


                                        <button type="button" data-toggle="modal" data-target="#find-nearby-modal"  data-request-id="{{$request->id}}" data-model-id="{{$request->model->id}}" data-address-id="{{$request->model->customer_addresses->id}}"  class="f-m-btn-nearby btn btn-soft-primary mr-2 find-measurer-btn fw-600" >
                                            <i class="las la-arrow-right"></i>
                                            <span class="d-none d-md-inline-block"> Find Nearby</span>
                                        </button>

                                        @endif
                                        {{-- @endif --}}
                                        {{-- for nearby measurer --}}
                                    </td>



                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <div class="modal fade" id="find-nearby-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-zoom" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title fw-600">Find Nearby Model</h6>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="modal-body">
                
                                <div class="p-3">
                                    <form class="form-default find-measurer-form" role="form" action="{{ route('seller.model_conversations_create',['model_id' => encrypt($request->model_id)]) }}" method="post">
                                        @csrf
                
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>Nearby Models</label>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="mb-3">
                                                    <select class="form-control"  name="modal_id" id="modal-dropdown" required>
                                                     
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

        $(document).on('click', '.f-m-btn-nearby', function(){


        var requestID = $(this).data('request-id');
        var modelID = $(this).data('model-id');
        var addressID = $(this).data('address-id');




        var url = "{{ route('seller.requests.nearby_models', ":modelID") }}";
        url = url.replace(':id', modelID);

        $.ajax({
            type:'GET',
            url: url,
            data:{id:requestID,user_id:modelID,address_id:addressID},
            success:function(data){
              
                $('#modal-dropdown').html('');
               
                    $.each(data, function (key, value) {
                        $("#modal-dropdown").html('<option value="' + modelID + '">' + data.user_name + '    ( '  +data.distance.toFixed(2)+' Km )</option>');

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


