<style>
    .container-fluid.px-1.px-sm-4.py-5.mx-auto {
        margin-left: 122px !important;
    }

    #create-appointment-modal .modal-dialog{
        padding-top: 100px;
    }

</style>

@extends('seller.layouts.app')

@section('panel_content')

<div class="card">
    <form action="{{route('seller.measurer.appointment.commission')}}" method="POST" style="margin-top: 25px;">
        @csrf
           <div class="form-group row col-md-4">
            <div class="col-md-4">
                <label>Commission Price For Measurer</label>
            </div>
            <div class="mb-3">
                <input type="text" name="set_commission" class="form-control"
                    value="{{ @$commission }}" disabled>
            </div>
            <input type="hidden" name="request_id" value="{{ \Request::get('request_id') }}">
            {{-- <input type="hidden" name="measurer_id" value="{{ \Request::get('measurer_id') }}"> --}}
        </div>
    </form>

    <div class="container-fluid px-1 px-sm-4 py-5 mx-auto">
        <div class="">
            <div class="col-md-10 col-lg-9 col-xl-8">
                <div class="border-0">
                    <div class="row px-3">
                        <div class="col-sm-2"> <label class="text-grey mt-1 mb-3">Availiablity Hours</label> </div>
                        <div class="col-sm-10 list">

                            @if ($measurer_avaliablity->isNotEmpty())


                            @foreach ($measurer_avaliablity as $item)

                            <div class="mb-2 row justify-content-between px-3">
                                <input type="hidden" name="days[]" value="{{$item->days}}">
                                {!! Str::limit("$item->days", 3, '') !!}
                                <div class="mob"> <label class="text-grey mr-1">From</label> <input class="ml-1"
                                        type="time" name="from[]" value="{{$item->from_time}}" disabled> </div>
                                <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input class="ml-1"
                                        type="time" name="to[]" value="{{$item->to_time}}" disabled> </div>

                            </div>


                            @endforeach


                            @else
                            <div class="mb-2 row justify-content-between px-3">
                                <input type="hidden" name="days[]" value="Monday">
                                Mon
                                <div class="mob"> <label class="text-grey mr-1">From</label> <input class="ml-1"
                                        type="time" name="from[]" disabled> </div>
                                <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input class="ml-1"
                                        type="time" name="to[]" disabled> </div>



                            </div>



                            <div class="mb-2 row justify-content-between px-3">
                                <input type="hidden" name="days[]" value="Tuesday">
                                Tue
                                <div class="mob"> <label class="text-grey mr-1">From</label> <input class="ml-1"
                                        type="time" name="from[]" disabled> </div>
                                <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input class="ml-1"
                                        type="time" name="to[]" disabled> </div>



                            </div>

                            <div class="mb-2 row justify-content-between px-3">
                                <input type="hidden" name="days[]" value="Wednesday">
                                Wed
                                <div class="mob"> <label class="text-grey mr-1">From</label> <input class="ml-1"
                                        type="time" name="from[]" disabled> </div>
                                <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input class="ml-1"
                                        type="time" name="to[]" disabled> </div>



                            </div>


                            <div class="mb-2 row justify-content-between px-3">
                                <input type="hidden" name="days[]" value="Thursday">
                                Thu
                                <div class="mob"> <label class="text-grey mr-1">From</label> <input class="ml-1"
                                        type="time" name="from[]" disabled> </div>
                                <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input class="ml-1"
                                        type="time" name="to[]" disabled> </div>



                            </div>


                            <div class="mb-2 row justify-content-between px-3">
                                <input type="hidden" name="days[]" value="Friday">
                                Fri

                                <div class="mob"> <label class="text-grey mr-1">From</label> <input class="ml-1"
                                        type="time" name="from[]" disabled> </div>
                                <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input class="ml-1"
                                        type="time" name="to[]" disabled> </div>



                            </div>



                            <div class="mb-2 row justify-content-between px-3">
                                <input type="hidden" name="days[]" value="Saturday">
                                Sat
                                <div class="mob"> <label class="text-grey mr-1">From</label> <input class="ml-1"
                                        type="time" name="from[]" disabled> </div>
                                <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input class="ml-1"
                                        type="time" name="to[]" disabled> </div>



                            </div>


                            <div class="mb-2 row justify-content-between px-3">
                                <input type="hidden" name="days[]" value="Sunday">
                                Sun
                                <div class="mob"> <label class="text-grey mr-1">From</label> <input class="ml-1"
                                        type="time" name="from[]" disabled> </div>
                                <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input class="ml-1"
                                        type="time" name="to[]" disabled> </div>



                            </div>


                            @endif
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>



    <div class="card-header">
        <h5 class="card-title fs-16 fw-600 mb-0">
            (
            {{ translate('Between you and') }}
            @if ($conversation->sender_id == Auth::user()->id)
            {{ $conversation->receiver->name }}
            @else
            {{ $conversation->sender->name }}
            @endif
            )


    </div>

    <div class="card-body">
        <ul class="list-group list-group-flush" id="messages">
            @foreach($conversation->messages as $message)
            <li class="list-group-item px-0">
                <div class="media mb-2">
                    <img class="avatar avatar-xs mr-3" @if($message->user != null)
                    src="{{ uploaded_asset($message->user->avatar_original) }}" @endif
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    <div class="media-body">
                        <h6 class="mb-0 fw-600">
                            @if ($message->user != null)
                            {{ $message->user->name }}
                            @endif
                        </h6>
                        <p class="opacity-50">{{$message->created_at}}</p>
                    </div>
                </div>
                <p>
                    {{ $message->message }}
                </p>
            </li>
            @endforeach
        </ul>
        <form class="pt-4" action="{{ route('seller.measurer.conversations', encrypt($conversation->id)) }}"
            method="POST">
            @csrf
            <input type="hidden" name="conversation_id" value="{{$conversation->id}}">
            <div class="form-group">
                <textarea class="form-control" rows="4" name="message" placeholder="Type your reply"
                    required=""></textarea>
            </div>
            <div class="form-group mb-0 text-right">
                <a class="btn btn-primary" data-toggle="modal" data-target="#create-appointment-modal">Create
                    Appointment</a>
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="create-appointment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">Appointment</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">

                <div class="p-3">
                    <form class="form-default create-appointment-form" role="form"
                        action="{{ route('seller.measurer.appointment.create') }}" method="get">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <label>Appointment Date</label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <input type="text" name="datetime" class="datetime form-control"
                                        placeholder="Appointment Datetime" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <input type="text" name="measurer_commission" class="form-control" value="{{ @$commission }}" hidden>
                            <input type="hidden" name="measurer_id" value="{{ encrypt(\Request::get('measurer_id')) }}">
                            <input type="hidden" name="request_id" value="{{ encrypt(\Request::get('request_id')) }}">
                            <button type="submit" class="btn btn-primary btn-block fw-600">Create Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    $(function () {

        $('.datetime').datetimepicker();

        function refresh_messages() {
            $.post('{{ route("seller.measurer.conversations.refresh") }}', {
                _token: '{{csrf_token()}}',
                id: '{{ encrypt($conversation->id) }}'
            }, function (data) {
                $('#messages').html(data);
            })
        }

        refresh_messages(); // This will run on page load
        setInterval(function () {
            refresh_messages() // this will run after every 5 seconds
        }, 4000);
    });

</script>
@endsection
