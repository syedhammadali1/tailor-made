
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

{{-- @dd($commission) --}}
<div class="card">
        <div class="form-group row col-md-4">
            <div class="col-md-4">
                <label>Commission Price For Model</label>
            </div>
            <div class="mb-3">
                <input type="text" name="set_commission" class="form-control"
                    value="{{ @$commission }}" disabled>
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
                        action="{{ route('seller.model_appointment_create') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <input type="text" name="model_commission" class="form-control" value="{{ @$commission }}" hidden>
                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                            <input type="hidden" name="seller_id" value="{{ auth()->id() }}">
                            <button type="submit" class="btn btn-primary btn-block fw-600">Create Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
