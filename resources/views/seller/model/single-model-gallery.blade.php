@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 text-primary">{{ translate('Images') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">


    @if (count($imagesPath) > 0)
        @foreach ($imagesPath as $imagePath)
            <div class="col-sm-6 col-md-4  col-xxl-3">
                <div class="card">
                    <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                        <img src="{{ url('/public').'/'.$imagePath }}" class="img-fluid w-100"/>
                        <a href="#!">
                            <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

    @else
            <div class="w-100 text-center ">
               <h5>
                   No Image
               </h5>
            </div>
    @endif

    </div>


@endsection

