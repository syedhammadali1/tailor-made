@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center justify-content-between">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Appointments') }}</h1>
        </div>
        <div class="d-inline-block d-md-block ">
            <form class="form-inline" method="POST" action="{{ route('search.measurment') }}">
                @csrf
                <div class="form-group mb-0">
                    <input type="text" class="form-control" placeholder="{{ translate('Enter Id') }}" name="uuid"
                        required>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ translate('Search') }}
                </button>
            </form>
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
                            <th>{{ translate('Product')}}</th>
                            <th data-breakpoints="lg">{{ translate('Owner')}}</th>
                            <th data-breakpoints="lg">{{ translate('Measurer')}}</th>
                            <th data-breakpoints="lg">{{ translate('Customer')}}</th>
                            <th data-breakpoints="lg">{{ translate('Commission Price')}}</th>
                            <th data-breakpoints="lg">{{ translate('Status')}}</th>
                            <th data-breakpoints="lg">{{ translate('Requirement By Customer')}}</th>
                            <th data-breakpoints="lg">{{ translate('Share Measurement')}}</th>
                            <th>{{ translate('Appointment Date Time')}}</th>
                            <th class="text-left">{{ translate('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php
                            $today = date('Y-m-d');
                        @endphp --}}
                        @isset($appointments)
                        @foreach ($appointments as $key => $appointment)
                            @php
                                $today = new DateTime();
                                $addADayOnCreatedAt = new DateTime($appointment->created_at->modify('+1 day') );

                            @endphp
                            @if($appointment != null)
                                <tr>
                                    <td>
                                        {{ $key+1 }}
                                    </td>
                                    <td>
                                        {{ $appointment->product->name }}
                                        {{-- @if (date('Y-m-d' , strtotime($appointment->datetime)) == $today)
                                            @if (!in_array($appointment->appointment_status,  array('5','6')))
                                            <span class="badge badge-inline badge-success">new</span>
                                            @endif
                                        @endif --}}

                                        @if ($today < $addADayOnCreatedAt)
                                            @if (!in_array($appointment->appointment_status,  array('5','6')))
                                            <span class="badge badge-inline badge-success">new</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        {{ $appointment->owner->name }}
                                    </td>
                                    <td>
                                        {{ $appointment->measurer->name }}
                                    </td>
                                    <td>
                                        {{ $appointment->request->customer->name }}
                                    </td>
                                    <td>
                                        {{ get_system_default_currency()->symbol.' '.$appointment->measurer_commission }}
                                    </td>

                                    <td>
                                        {{ appointment_stutus($appointment->appointment_status) }}
                                    </td>

                                    <td>
                                        {{ $appointment->request->product_description ?? '-' }}
                                    </td>

                                    @if ($appointment->measurement_uuid != null)
                                    <td>
                                        <a class="btn m-action-btn btn-xs" href="{{route('show_user_measurement',[$appointment->measurement_uuid])}}" >
                                            See Measurement
                                        </a>
                                    </td>
                                    @else
                                    <td>
                                      -
                                      </td>
                                    @endif

                                    <td>
                                        {{ date('d-m-Y | h:i a',strtotime($appointment->datetime)) }}
                                    </td>
                                    <td>

                                        @php
                                        $today = new DateTime();
                                        $addADayOnCreatedAt = new DateTime($appointment->created_at->modify('+1 day') );
                                        @endphp

                                        @if( $today > $addADayOnCreatedAt)
                                            <p>Expired</p>
                                        @elseif(  $today < $addADayOnCreatedAt)
                                            <div class="dropdown">
                                                @if($appointment->appointment_status == 5)
                                                <p>Rejected</p>
                                                @elseif($appointment->appointment_status == 6)
                                                <p>Completed </p>
                                                @else
                                                <button class="btn m-action-btn btn-xs dropdown-toggle " type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                @endif
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    @if($appointment->appointment_status == 0)
                                                    <a onclick="actionConfirmCheck('{{ route('measurer-appointment.accept-reject', ['id' => encrypt(@$appointment->id), 'status' => encrypt('accept')] ) }}', 'Are you sure you want accept this appointment')" href="javascript:;" class="dropdown-item  m-a-accept-btn">Accept</a>
                                                        {{-- <a href="javascript:;" class="dropdown-item  m-a-accept-btn">Accept {{ $appointment->id}}</a> --}}
                                                        <a onclick="actionConfirmCheck('{{ route('measurer-appointment.accept-reject', ['id' => encrypt(@$appointment->id), 'status' => encrypt('reject')] ) }}', 'Are you sure you want reject this appointment')" href="javascript:;" class="dropdown-item" >Reject</a>

                                                    @else
                                                        @isset($appointment->product->personalizeProductTypeName)
                                                            @if($appointment->product->personalizeProductTypeName->slug == 'clothing')
                                                                <a class="dropdown-item m-add-measurements" href="javascript:;" a-m-url="{{ route("measurer-measurement", encrypt(@$appointment->id)) }}">Clothes Measurement</a>
                                                            @endif
                                                            @if($appointment->product->personalizeProductTypeName->slug == 'shoes')
                                                                <a class="dropdown-item m-add-measurements-shoes" href="javascript:;" a-m-url="{{ route("measurer-measurement", encrypt(@$appointment->id)) }}">Shoes Measurement</a>


                                                            @endif
                                                        @endisset

                                                    @if(@$appointment->id == 2)
                                                        @endif
                                                        {{-- @if ($appointment->product->category->slug == "clothing" || $appointment->product->category->slug == "menclothingandfashion" ) --}}
                                                            <a class="dropdown-item m-add-measurements-video" href="{{route('appointments_video', ['id' => @$appointment->id])}}">Measurement With Video</a>
                                                        {{-- @else --}}
                                                        {{-- @endif --}}

                                                    @endif

                                                    @if ($appointment->appointment_status == 1)
                                                    <a class="dropdown-item" href="{{ route("user.mark_as_complete", ['id' => @$appointment->id]) }}">Mark as Complete</a>
                                                    @endif

                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                </tr>
                            @endif

                        @endforeach
                        @endisset
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
@section('modal')
    @if(\Auth::check())
 {{--     clothes modal start   --}}
    <div class="modal fade" id="measurer-measurement-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Clothes Measurements')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="p-0">
                        <form class="form-default" role="form" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 text-center pb-1">
                                    <p class="font-weight-bold">Put All Measurements in Inches* .</p>
                                    <img src="{{static_asset('assets/img/clothing.png')}}" alt="">
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="neck_circumference">{{ translate('Neck Circumference')}} (1)</label>
                                        <input type="number" step=".01" required name="neck_circumference1" id="neck_circumference" class="form-control" placeholder="Neck Circumference">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="shoulder_to_shoulder">{{ translate('Shoulder to shoulder')}} (2)</label>
                                        <input type="number" step=".01" required name="shoulder_to_shoulder2" id="shoulder_to_shoulder" class="form-control" placeholder="Shoulder to shoulder">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="chest_circumference">{{ translate('Chest circumference')}} (3)</label>
                                        <input type="number" step=".01" required name="chest_circumference3" id="chest_circumference" class="form-control" placeholder="Chest circumference">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="circumference_under_the_breast">{{ translate('Circumference under the breast (women only)')}} (3a)</label>
                                        <input type="number" step=".01" name="circumference_under_the_breast3a" id="circumference_under_the_breast" class="form-control" placeholder="Circumference under the breast">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="waist_circumference">{{ translate('Waist circumference')}} (4)</label>
                                        <input type="number" step=".01" required name="waist_circumference4" id="waist_circumference" class="form-control" placeholder="Waist circumference">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="back_length">{{ translate('Back length')}} (5)</label>
                                        <input type="number" step=".01" required name="back_length5" id="back_length" class="form-control" placeholder="Back length">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="shoulder_to_wrist">{{ translate('Shoulder to wrist')}} (6)</label>
                                        <input type="number" step=".01" required name="shoulder_to_wrist6" id="shoulder_to_wrist" class="form-control" placeholder="Shoulder to wrist">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="shoulder_to_elbow">{{ translate('Shoulder to elbow')}} (7)</label>
                                        <input type="number" step=".01" required name="shoulder_to_elbow7" id="shoulder_to_elbow" class="form-control" placeholder="Shoulder to elbow">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="wrist_to_elbow">{{ translate('Wrist to elbow')}} (8)</label>
                                        <input type="number" step=".01" required name="wrist_to_elbow8" id="wrist_to_elbow" class="form-control" placeholder="Wrist to elbow">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="biceps">{{ translate('Biceps (circumference)')}} (9)</label>
                                        <input type="number" step=".01" required name="biceps9" id="biceps" class="form-control" placeholder="Biceps (circumference)">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="forearm">{{ translate('Forearm (circumference)')}} (10)</label>
                                        <input type="number" step=".01" required name="forearm10" id="forearm" class="form-control" placeholder="Forearm (circumference)">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="wrist_circumference">{{ translate('Wrist circumference')}} (11)</label>
                                        <input type="number" step=".01" required name="wrist_circumference11" id="wrist_circumference" class="form-control" placeholder="Wrist circumference">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="waist_to_ankle_length">{{ translate('Waist to ankle length')}} (12)</label>
                                        <input type="number" step=".01" required name="waist_to_ankle_length12" id="waist_to_ankle_length" class="form-control" placeholder="Waist to ankle length">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="hip_circumference">{{ translate('Hip circumference')}} (13)</label>
                                        <input type="number" step=".01" required name="hip_circumference13" id="hip_circumference" class="form-control" placeholder="Hip circumference">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="thigh_circumference">{{ translate('Thigh circumference')}} (14)</label>
                                        <input type="number" step=".01" required name="thigh_circumference14" id="thigh_circumference" class="form-control" placeholder="Thigh circumference">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="circumference_of_knees">{{ translate('Circumference of knees')}} (15)</label>
                                        <input type="number" step=".01" required name="circumference_of_knees15" id="circumference_of_knees" class="form-control" placeholder="Circumference of knees">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="calf_circumference">{{ translate('Calf circumference')}} (16)</label>
                                        <input type="number" step=".01" required name="calf_circumference16" id="calf_circumference" class="form-control" placeholder="Calf circumference">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="crotch_to_ankle">{{ translate('Crotch to ankle')}} (17)</label>
                                        <input type="number" step=".01" required name="crotch_to_ankle17" id="crotch_to_ankle" class="form-control" placeholder="Crotch to ankle">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="knees_to_ankle">{{ translate('Knees to ankle')}} (18)</label>
                                        <input type="number" step=".01" required name="knees_to_ankle18" id="knees_to_ankle" class="form-control" placeholder="Knees to ankle">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="ankle_circumference">{{ translate('Ankle circumference')}} (19)</label>
                                        <input type="number" step=".01" required name="ankle_circumference19" id="ankle_circumference" class="form-control" placeholder="Ankle circumference">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="neck_to_ankle">{{ translate('Neck to ankle')}} (20)</label>
                                        <input type="number" step=".01" required name="neck_to_ankle20" id="neck_to_ankle" class="form-control" placeholder="Neck to ankle">
                                    </div>
                                </div>

                            </div>

{{--                            <div class="form-group">--}}
{{--                                <label for="measurements_text">Measurements</label>--}}
{{--                                <textarea name="measurements_text" id="measurements_text" class="form-control" placeholder="Measurements" rows="5"> .</textarea>--}}
{{--                            </div>--}}

{{--                            <div class="form-group">--}}
{{--                                <label for="measurements_image">Measurements Image</label>--}}
{{--                                <br>--}}
{{--                                <input type="file" name="measurements_image">--}}
{{--                                <a href="" data-lightbox="photo">--}}
{{--                                    <img src="" width="100" class="m-img-preview ">--}}
{{--                                </a>--}}
{{--                            </div>--}}
                            <div class="mb-2">
{{--                                <input type="hidden" name="measurements_image_h">--}}
                                <input type="hidden" name="measurement_type" id="measurement_type" value="clothes">
                                <button type="submit" class="btn btn-primary btn-block fw-600">{{  translate('Submit') }}</button>
                            </div>
                            <div class="alert alert-success  m-msg-show"></div>
                            <div class="alert alert-danger  m-error-msg-show"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--     clothes modal end   --}}

{{--     shoes modal start   --}}
<div class="modal fade" id="measurer-shoes-measurement-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">{{ translate('Shoes Measurements')}}</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">

                <div class="p-0">
                    <form class="form-default" role="form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 text-center pb-1">
                                <p class="font-weight-bold">Put All Measurements in Inches* .</p>
                                <img src="{{static_asset('assets/img/foot-size.jpg')}}" alt="">
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_a">a</label>
                                    <input type="number" step=".01" required name="foot_a" id="foot_a" class="form-control" placeholder="Enter a">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_b">b</label>
                                    <input type="number" step=".01" required name="foot_b" id="foot_b" class="form-control" placeholder="Enter b">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_c">c</label>
                                    <input type="number" step=".01" required name="foot_c" id="foot_c" class="form-control" placeholder="Enter c">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_d">d</label>
                                    <input type="number" step=".01" required name="foot_d" id="foot_d" class="form-control" placeholder="Enter d">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_e">e</label>
                                    <input type="number" step=".01" required name="foot_e" id="foot_e" class="form-control" placeholder="Enter e">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_f">f</label>
                                    <input type="number" step=".01" required name="foot_f" id="foot_f" class="form-control" placeholder="Enter f">
                                </div>
                            </div>


                        </div>
                        <div class="mb-2">
                            <input type="hidden" name="measurement_type" id="measurement_type" value="shoes">
                            <button type="submit" class="btn btn-primary btn-block fw-600">{{  translate('Submit') }}</button>
                        </div>
                        <div class="alert alert-success  m-msg-shows"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{--     shoes modal end   --}}

    @endif



    {{--     shoes modal start   --}}
    {{-- <form action="{{ route('measurer-appointment.accept-reject', ['id' => encrypt(@$appointment->id), 'status' => encrypt('accept')] ) }}')">
    <div class="modal fade" id="check-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Accept To Do Measurements')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <div class="col-md-8">
                    <p>Are You Sure? Mandatorily checked in order to accept to do measurements following the International System of Units, meaning in Centimeter? </p>
                    </div>

                        <label class="aiz-checkbox" style="margin: 55px;">
                            <input type="checkbox" required >
                            <span class="aiz-square-check"></span>

                        </label>

                    </div>

                    <button type="submit" class="btn btn-primary btn-block fw-600">{{  translate('Submit') }}</button>

                </div>
            </div>
        </div>
    </div>
</form> --}}
    {{--     shoes modal end   --}}

    <div class="modal fade" id="check-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom modal-md" role="document">
            <div class="modal-content">
                <form action="{{ route('measurer-appointment.accept-reject', ['id' => encrypt(@$appointment->id), 'status' => encrypt('accept')] ) }}')">
                    <div class="modal-header">
                        <h6 class="modal-title fw-600">{{ translate('Accept To Do Measurements')}}</h6>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-8">
                        <p>Are You Sure? Mandatorily checked in order to accept to do measurements following the International System of Units, meaning in Centimeter? </p>
                        </div>

                            <label class="aiz-checkbox" style="margin: 55px;">
                                <input type="checkbox" required >
                                <span class="aiz-square-check"></span>

                            </label>

                        </div>

                        <button type="submit" class="btn btn-primary btn-block fw-600">{{  translate('Submit') }}</button>

                    </div>
                </form>
                </div>
            </div>
        </div>

@endsection
@section('script')
    <script>
        function actionConfirmCheck(...args) {
             if(confirm(args[1])) {
                window.location.href = args[0];
             }
        }
        $(function(){
            /* $(document).on('click', '.m-a-accept-btn', function(e) {
                e.preventDefault();
                if($(this).hasClass('m-a-a-not-trigger'))
                    return false;

                if(confirm('Are you sure you want accept this appointment')) {
                    window.location.href = $(this).attr('href');
                }
            }); */


            //  $(document).on('click', '.m-a-accept-btn', function() {

            //     $('#check-modal').modal();


            // });


            $(document).on('click', '.m-add-measurements', function(e) {
                $('#measurer-measurement-modal').modal();
                $('#measurer-measurement-modal form')[0].reset();
                // $('[name="measurements_image_h"]').val('');
                $('.m-msg-show').hide();
                var url = $(this).attr('a-m-url');
                $('#measurer-measurement-modal form').attr('action', url);
                var imgUrl = '{{ url("public/assets/img") }}';
                console.log(imgUrl);
                $.ajax({
                    url: url,
                    type: 'get',
                }).then(function(response) {
                    if(!response.data) {
                        $('.m-img-preview ').attr('src', '');
                    }
                    $.each(response.data, function(key, val){
                        // if(key === 'measurements_image') {
                            // $('[name="measurements_image_h"]').val(val);
                            // if(val) {
                            //     $('.m-img-preview').attr('src', imgUrl+"/"+val).show();
                            //     $('.m-img-preview').parent().attr('href', imgUrl+"/"+val).show();
                            //
                            // }
                        // }
                        // else {
                            $('[name="'+key+'"]').val(val);
                        // }
                    })
                }).catch(function(error){
                    console.log(error);
                    alert('Something went wrong!');
                });
            });
            $(document).on('submit', '#measurer-measurement-modal form', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = $('#measurer-measurement-modal form').attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                }).then(function(response) {
                    if(response.status) {
                        $('.m-msg-show').text(response.message).show();
                        setTimeout(() => {
                            $('#measurer-measurement-modal').modal('toggle');
                        }, 2000);
                    }
                    else {
                        $('.m-error-msg-show').text(response.message).show();

                    }
                    // console.log(response);
                }).catch(function(error){
                    console.log(error);
                    alert('Something went wrong!');
                });
            });

            // for shoes measurement

            $(document).on('click', '.m-add-measurements-shoes', function(e) {
                $('#measurer-shoes-measurement-modal').modal();
                $('#measurer-shoes-measurement-modal form')[0].reset();
                $('.m-msg-shows').hide();
                var url = $(this).attr('a-m-url');
                $('#measurer-shoes-measurement-modal form').attr('action', url);
                var imgUrl = '{{ url("public/assets/img") }}';
                console.log(imgUrl);
                $.ajax({
                    url: url,
                    type: 'get',
                }).then(function(response) {
                    $.each(response.data, function(key, val){
                        $('[name="'+key+'"]').val(val);
                    })
                }).catch(function(error){
                    console.log(error);
                    alert('Something went wrong!!!');
                });
            });
            $(document).on('submit', '#measurer-shoes-measurement-modal form', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = $('#measurer-shoes-measurement-modal form').attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                }).then(function(response) {
                    if(response.status) {
                        $('.m-msg-shows').text(response.message).show();
                        setTimeout(() => {
                            $('#measurer-shoes-measurement-modal').modal('toggle');
                        }, 2000);
                    }
                    else {
                        $('.m-error-msg-shows').text(response.message).show();

                    }
                    // console.log(response);
                }).catch(function(error){
                    console.log(error);
                    alert('Something went wrong!');
                });
            });

        });
    </script>
@endsection
@endsection
