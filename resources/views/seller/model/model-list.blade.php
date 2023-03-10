@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 text-primary">{{ translate('Models') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($models as $model)
            <div class="col-sm-6 col-md-4  col-xxl-3">
                <div class="card">
                    <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                    <img src="{{ @$model->avatarImage->file_name ? url('/public').'/'.@$model->avatarImage->file_name : url('/public').'/assets/img/avatar-place.png' }}" class="img-fluid w-100"/>
                    <a href="#!">
                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                    </a>
                    </div>
                    <div class="card-body">
                    <h5 class="card-title">{{@$model->name}}</h5>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('seller.single_model_gallery',@$model->id)}}" class="btn btn-dark">See Images</a>
                        <a href="{{ route('seller.model_conversations_create',['model_id' => encrypt(@$model->id) ])}}" class="btn btn-primary">Hire Model</a>
                    </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


@endsection

