@extends('frontend.layouts.user_panel')

@section('panel_content')
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
                        <th>{{ translate('Product')}}</th>
                        <th data-breakpoints="lg">{{ translate('Owner')}}</th>
                        <th data-breakpoints="lg">{{ translate('Measurer')}}</th>
                        <th data-breakpoints="lg">{{ translate('Price')}}</th>
                        <th data-breakpoints="lg">{{ translate('Status')}}</th>
                        <th data-breakpoints="lg">{{ translate('Share Measurement')}}</th>
                        {{-- <th data-breakpoints="lg">{{ translate('action')}}</th> --}}
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
                            {{ @$appointment->product->name }}
                        </td>
                        <td>
                            {{ @$appointment->owner->name }}
                        </td>

                        @if (@$appointment->measurer != null)
                        <td>
                            {{ @$appointment->measurer->name }}
                        </td>

                        @else
                        <td>

                        </td>
                        @endif


                        <td>
                            {{ get_system_default_currency()->symbol.' '.@$appointment->measurer_commission }}
                        </td>

                        <td>
                            {{ appointment_stutus(@$appointment->appointment_status) }}
                        </td>

                        @if (@$appointment->measurement_uuid != null && @$appointment->appointment_status == 6)
                        <td>
                            <a class="btn m-action-btn btn-xs"
                                href="{{route('show_user_measurement',[@$appointment->measurement_uuid])}}">
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
@section('modal')
@if(\Auth::check())
<div class="modal fade" id="measurer-measurement-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">{{ translate('Measurements')}}</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">

                <div class="p-3">
                    <form class="form-default" role="form" method="POST" enctype="multipart/form-data">
                        @csrf



                         {{-- @if(@$appointment->product->personalizeProductTypeName->slug == 'clothing') --}}
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="neck_circumference">{{ translate('Neck Circumference')}} (1)</label>
                                    <input type="number" readonly step=".01" required name="neck_circumference1"
                                        id="neck_circumference" class="form-control" placeholder="Neck Circumference">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="shoulder_to_shoulder">{{ translate('Shoulder to shoulder')}} (2)</label>
                                    <input type="number" readonly step=".01" required name="shoulder_to_shoulder2"
                                        id="shoulder_to_shoulder" class="form-control"
                                        placeholder="Shoulder to shoulder">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="chest_circumference">{{ translate('Chest circumference')}} (3)</label>
                                    <input type="number" readonly step=".01" required name="chest_circumference3"
                                        id="chest_circumference" class="form-control" placeholder="Chest circumference">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label
                                        for="circumference_under_the_breast">{{ translate('Circumference under the breast (women only)')}}
                                        (3a)</label>
                                    <input type="number" readonly step=".01" name="circumference_under_the_breast3a"
                                        id="circumference_under_the_breast" class="form-control"
                                        placeholder="Circumference under the breast">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="waist_circumference">{{ translate('Waist circumference')}} (4)</label>
                                    <input type="number" readonly step=".01" required name="waist_circumference4"
                                        id="waist_circumference" class="form-control" placeholder="Waist circumference">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="back_length">{{ translate('Back length')}} (5)</label>
                                    <input type="number" readonly step=".01" required name="back_length5"
                                        id="back_length" class="form-control" placeholder="Back length">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="shoulder_to_wrist">{{ translate('Shoulder to wrist')}} (6)</label>
                                    <input type="number" readonly step=".01" required name="shoulder_to_wrist6"
                                        id="shoulder_to_wrist" class="form-control" placeholder="Shoulder to wrist">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="shoulder_to_elbow">{{ translate('Shoulder to elbow')}} (7)</label>
                                    <input type="number" readonly step=".01" required name="shoulder_to_elbow7"
                                        id="shoulder_to_elbow" class="form-control" placeholder="Shoulder to elbow">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="wrist_to_elbow">{{ translate('Wrist to elbow')}} (8)</label>
                                    <input type="number" readonly step=".01" required name="wrist_to_elbow8"
                                        id="wrist_to_elbow" class="form-control" placeholder="Wrist to elbow">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="biceps">{{ translate('Biceps (circumference)')}} (9)</label>
                                    <input type="number" readonly step=".01" required name="biceps9" id="biceps"
                                        class="form-control" placeholder="Biceps (circumference)">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="forearm">{{ translate('Forearm (circumference)')}} (10)</label>
                                    <input type="number" readonly step=".01" required name="forearm10" id="forearm"
                                        class="form-control" placeholder="Forearm (circumference)">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="wrist_circumference">{{ translate('Wrist circumference')}} (11)</label>
                                    <input type="number" readonly step=".01" required name="wrist_circumference11"
                                        id="wrist_circumference" class="form-control" placeholder="Wrist circumference">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="waist_to_ankle_length">{{ translate('Waist to ankle length')}}
                                        (12)</label>
                                    <input type="number" readonly step=".01" required name="waist_to_ankle_length12"
                                        id="waist_to_ankle_length" class="form-control"
                                        placeholder="Waist to ankle length">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="hip_circumference">{{ translate('Hip circumference')}} (13)</label>
                                    <input type="number" readonly step=".01" required name="hip_circumference13"
                                        id="hip_circumference" class="form-control" placeholder="Hip circumference">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="thigh_circumference">{{ translate('Thigh circumference')}} (14)</label>
                                    <input type="number" readonly step=".01" required name="thigh_circumference14"
                                        id="thigh_circumference" class="form-control" placeholder="Thigh circumference">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="circumference_of_knees">{{ translate('Circumference of knees')}}
                                        (15)</label>
                                    <input type="number" readonly step=".01" required name="circumference_of_knees15"
                                        id="circumference_of_knees" class="form-control"
                                        placeholder="Circumference of knees">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="calf_circumference">{{ translate('Calf circumference')}} (16)</label>
                                    <input type="number" readonly step=".01" required name="calf_circumference16"
                                        id="calf_circumference" class="form-control" placeholder="Calf circumference">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="crotch_to_ankle">{{ translate('Crotch to ankle')}} (17)</label>
                                    <input type="number" readonly step=".01" required name="crotch_to_ankle17"
                                        id="crotch_to_ankle" class="form-control" placeholder="Crotch to ankle">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="knees_to_ankle">{{ translate('Knees to ankle')}} (18)</label>
                                    <input type="number" readonly step=".01" required name="knees_to_ankle18"
                                        id="knees_to_ankle" class="form-control" placeholder="Knees to ankle">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="ankle_circumference">{{ translate('Ankle circumference')}} (19)</label>
                                    <input type="number" readonly step=".01" required name="ankle_circumference19"
                                        id="ankle_circumference" class="form-control" placeholder="Ankle circumference">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="neck_to_ankle">{{ translate('Neck to ankle')}} (20)</label>
                                    <input type="number" readonly step=".01" required name="neck_to_ankle20"
                                        id="neck_to_ankle" class="form-control" placeholder="Neck to ankle">
                                </div>
                            </div>

                        </div>


                        {{-- @if(@$appointment->product->personalizeProductTypeName->slug == 'shoes') --}}
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_a">{{ translate('Foot A')}} </label>
                                    <input type="text" readonly step=".01" required name="foot_a" id="foot_a"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_b">{{ translate('Foot B')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_a" id="foot_b"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_c">{{ translate('Foot C')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_a" id="foot_c"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_d">{{ translate('Foot D')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_a" id="foot_d"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_e">{{ translate('Foot E')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_e" id="foot_e"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="foot_f">{{ translate('Foot F')}} </label>
                                    <input type="number" readonly step=".01" required name="foot_f" id="foot_f"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        {{-- @endif --}}

                        @if (@$appointment->product->personalizeProductTypeName->slug == 'clothing' ||
                        @$appointment->product->personalizeProductTypeName->slug == "shoes")
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="measurements_video">Measurements Video</label>
                                    <video width="320" height="240" controls autoplay>
                                        @if (isset($appointment->measurement->measurement_video))
                                        <source src="{{asset('public/'.@$appointment->measurement->measurement_video)}}"
                                            type="video/webm">
                                        Your browser does not support the video tag.
                                        @endif

                                    </video>
                                </div>
                            </div>
                        </div>
                        @endif



                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@section('script')
<script>
    $(function () {
        $(document).on('click', '.m-add-measurements', function (e) {


            $('#measurer-measurement-modal').modal();
            $('#measurer-measurement-modal form')[0].reset();
            $('[name="measurements_image_h"]').val('');
            var url = $(this).attr('a-m-url');

            var imgUrl = '{{ url("public/assets/img") }}';
            // console.log(imgUrl);
            $.ajax({
                url: url,
                type: 'get',
            }).then(function (response) {
                console.log(response.data);
                if (!response.data) {
                    $('.m-img-preview ').attr('src', '');
                }
                $.each(response.data, function (key, val) {


                    $('#foot_a').val(response.data.foot_a);
                    $('#foot_b').val(response.data.foot_b);
                    $('#foot_c').val(response.data.foot_c);
                    $('#foot_d').val(response.data.foot_d);
                    $('#foot_e').val(response.data.foot_e);
                    $('#foot_f').val(response.data.foot_f);


                    if (key === 'measurements_image') {
                        $('[name="measurements_image_h"]').val(val);
                        if (val) {
                            $('.m-img-preview').attr('src', imgUrl + "/" + val).show();
                            $('.m-img-preview').parent().attr('href', imgUrl + "/" +
                                val).show();

                        }
                    } else {
                        $('[name="' + key + '"]').val(val);
                    }
                })
            }).catch(function (error) {
                console.log(error);
                alert('Something went wrong!');
            });
        });
    });

</script>
@endsection
@endsection
