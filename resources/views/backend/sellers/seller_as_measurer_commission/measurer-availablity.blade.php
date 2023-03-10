@extends('seller.layouts.app')


<style>
    .mb-2.row.justify-content-between.px-3 {
        padding: 8px;

    }

</style>

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Measurer Availiablity') }}</h1>
        </div>
    </div>
</div>

<div class="row gutters-10">
    <div class="col-md-12">


        <form method="POST" action="{{route('measurer.availablity.save')}}">
            @csrf
            <div class="card">
                <div class="container-fluid px-1 px-sm-4 py-5 mx-auto">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-10 col-lg-9 col-xl-8">
                            <div class="border-0">
                                <div class="row px-3">
                                    <div class="col-sm-2"> <label class="text-grey mt-1 mb-3">Availiablity Hours</label>
                                    </div>
                                    <div class="col-sm-10 list">


                                        @if ($measurer_avaliablity->isNotEmpty())


                                        @foreach ($measurer_avaliablity as $item)

                                        <div class="mb-2 row justify-content-between px-3">
                                            <input type="hidden" name="days[]" value="{{$item->days}}">
                                            {!! Str::limit("$item->days", 3, '') !!}
                                            <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                                    class="ml-1" type="time" name="from[]" value="{{$item->from_time}}">
                                            </div>
                                            <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                                    class="ml-1" type="time" name="to[]" value="{{$item->to_time}}">
                                            </div>

                                        </div>


                                        @endforeach


                                        @else
                                        <div class="mb-2 row justify-content-between px-3">
                                            <input type="hidden" name="days[]" value="Monday">
                                            Mon
                                            <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                                    class="ml-1" type="time" name="from[]"> </div>
                                            <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                                    class="ml-1" type="time" name="to[]"> </div>



                                        </div>



                                        <div class="mb-2 row justify-content-between px-3">
                                            <input type="hidden" name="days[]" value="Tuesday">
                                            Tue
                                            <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                                    class="ml-1" type="time" name="from[]"> </div>
                                            <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                                    class="ml-1" type="time" name="to[]"> </div>



                                        </div>

                                        <div class="mb-2 row justify-content-between px-3">
                                            <input type="hidden" name="days[]" value="Wednesday">
                                            Wed
                                            <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                                    class="ml-1" type="time" name="from[]"> </div>
                                            <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                                    class="ml-1" type="time" name="to[]"> </div>



                                        </div>


                                        <div class="mb-2 row justify-content-between px-3">
                                            <input type="hidden" name="days[]" value="Thursday">
                                            Thu
                                            <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                                    class="ml-1" type="time" name="from[]"> </div>
                                            <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                                    class="ml-1" type="time" name="to[]"> </div>



                                        </div>


                                        <div class="mb-2 row justify-content-between px-3">
                                            <input type="hidden" name="days[]" value="Friday">
                                            Fri

                                            <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                                    class="ml-1" type="time" name="from[]"> </div>
                                            <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                                    class="ml-1" type="time" name="to[]"> </div>



                                        </div>



                                        <div class="mb-2 row justify-content-between px-3">
                                            <input type="hidden" name="days[]" value="Saturday">
                                            Sat
                                            <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                                    class="ml-1" type="time" name="from[]"> </div>
                                            <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                                    class="ml-1" type="time" name="to[]"> </div>



                                        </div>


                                        <div class="mb-2 row justify-content-between px-3">
                                            <input type="hidden" name="days[]" value="Sunday">
                                            Sun
                                            <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                                    class="ml-1" type="time" name="from[]"> </div>
                                            <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                                    class="ml-1" type="time" name="to[]"> </div>



                                        </div>


                                        @endif
                                    </div>
                                </div>

                                <div class="row px-3 mt-3 justify-content-center">
                                    <button type="submit" class="btn btn-success ml-2">Submit</button> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

        <div class="">
            <form action="{{route('setDefaultCommission')}}" method="POST" style="margin-top: 25px;">
                @csrf
                <div class="form-group row col-md-6">
                    <div class="col-md-4">
                        <label>Set Commission Price in USD</label>
                    </div>
                    <div class="mb-3">
                        <input type="number" name="default_commission" class="form-control"
                            value="{{auth()->user()->defaultMeasurerCommission->default_commission ?? ''}}">
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </div>
            </form>
        </div>

    </div>

</div>

@endsection


@section('modal')
@include('frontend.partials.address_modal')
@endsection


@if (get_setting('google_map') == 0)

@include('frontend.partials.google_map')

@endif


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"> </script>
<script>
    $(document).ready(function () {

        $('.add').click(function () {
            $(".list").append(
                '<div class="mb-2 row justify-content-between px-3">' +
                '<select class="mob mb-2" name="days[]">' +
                '<option value="Mon">Mon</option>' +
                '<option value="Tues">Tues</option>' +
                '<option value="Wed">Wed</option>' +
                '<option value="Thurs">Thurs</option>' +
                '<option value="Fri">Fri</option>' +
                '<option value="Sat">Sat</option>' +
                '<option value="Sun">Sun</option>' +
                '</select>' +
                '<div class="mob">' +
                '<label class="text-grey mr-1">From</label>' +
                '<input class="ml-1" type="time" name="from[]">' +
                '</div>' +
                '<div class="mob mb-2">' +
                '<label class="text-grey mr-4">To</label>' +
                '<input class="ml-1" type="time" name="to[]">' +
                '</div>' +

                // '<div class="mt-1 cancel fa fa-times text-danger">' +
                // '</div>' +
                '</div>');
        });

        $(".list").on('click', '.cancel', function () {
            $(this).parent().remove();
        });

    });

</script>
