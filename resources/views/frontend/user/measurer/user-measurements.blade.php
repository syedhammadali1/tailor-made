<style>
.form-group-video {
    margin-left: 340px;
}
</style>

@extends('frontend.layouts.user_panel_without_auth')

{{-- @extends('frontend.layouts.app') --}}

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <b class="h4">{{ translate('User Measurements') }}</b>
             <p>   ({{$request_measurement->uuid}}) </p>
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

                <div class="card-body p-3">

                    <div class="row">


                        {{-- @isset($request_measurement->appointment->product->personalizeProductTypeName) --}}


                        @if ($request_measurement->foot_a != "")


                            {{-- <img src="{{asset('public/assets/img/foot-size.jpg')}}" alt="" style="margin-left: 240px; height: 280px;"> --}}


                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="neck_circumference">Foot A</label>
                                    <input type="text" disabled value="{{$request_measurement->foot_a}}" step=".01" required="" name="neck_circumference1" id="neck_circumference" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="shoulder_to_shoulder">Foot B</label>
                                    <input type="text" disabled value="{{$request_measurement->foot_b}}" step=".01" required="" name="shoulder_to_shoulder2" id="shoulder_to_shoulder" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="chest_circumference">Foot C</label>
                                    <input type="text" disabled  value="{{$request_measurement->foot_c}}" step=".01" required="" name="chest_circumference3" id="chest_circumference" class="form-control" >
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="circumference_under_the_breast">Foot D</label>
                                    <input type="text" disabled value="{{$request_measurement->foot_d}}"  step=".01" name="circumference_under_the_breast3a" id="circumference_under_the_breast" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="waist_circumference">Foot E</label>
                                    <input type="text" disabled  value="{{$request_measurement->foot_e}}" step=".01" required="" name="waist_circumference4" id="waist_circumference" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="back_length">Foot F</label>
                                    <input type="text" disabled value="{{$request_measurement->foot_f}}" step=".01" required="" name="back_length5" id="back_length" class="form-control">
                                </div>
                            </div>
                            {{-- @if (isset($request_measurement->measurement_video))
                            <div class="col-lg-4">
                                <div class="form-group-video">
                                    <video width="400" height="240" controls autoplay>

                                        <source src="{{asset('public/'.$request_measurement->measurement_video)}}"  type="video/webm">
                                            Your browser does not support the video tag.

                                    </video>
                                </div>
                            </div>
                            @endif --}}

                        @endif
                        @if($request_measurement->neck_circumference1 != "")

                            {{-- <img src="{{asset('public/assets/img/clothing.png')}}" alt="" style="margin-left: 130px;"> --}}

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="neck_circumference">Neck Circumference (1)</label>
                                    <input type="text" disabled value="{{$request_measurement->neck_circumference1}}" step=".01" required="" name="neck_circumference1" id="neck_circumference" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="shoulder_to_shoulder">Shoulder to shoulder (2)</label>
                                    <input type="text" disabled value="{{$request_measurement->shoulder_to_shoulder2}}" step=".01" required="" name="shoulder_to_shoulder2" id="shoulder_to_shoulder" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="chest_circumference">Chest circumference (3)</label>
                                    <input type="text" disabled  value="{{$request_measurement->chest_circumference3}}" step=".01" required="" name="chest_circumference3" id="chest_circumference" class="form-control" >
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="circumference_under_the_breast">Circumference under the breast (women only) (3a)</label>
                                    <input type="text" disabled value="{{$request_measurement->circumference_under_the_breast3a}}"  step=".01" name="circumference_under_the_breast3a" id="circumference_under_the_breast" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="waist_circumference">Waist circumference (4)</label>
                                    <input type="text" disabled  value="{{$request_measurement->waist_circumference4}}" step=".01" required="" name="waist_circumference4" id="waist_circumference" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="back_length">Back length (5)</label>
                                    <input type="text" disabled value="{{$request_measurement->back_length5}}" step=".01" required="" name="back_length5" id="back_length" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="shoulder_to_wrist">Shoulder to wrist (6)</label>
                                    <input type="text" disabled value="{{$request_measurement->shoulder_to_wrist6}}" step=".01" required="" name="shoulder_to_wrist6" id="shoulder_to_wrist" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="shoulder_to_elbow">Shoulder to elbow (7)</label>
                                    <input type="text" disabled value="{{$request_measurement->shoulder_to_elbow7}}" step=".01" required="" name="shoulder_to_elbow7" id="shoulder_to_elbow" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="wrist_to_elbow">Wrist to elbow (8)</label>
                                    <input type="text" disabled value="{{$request_measurement->wrist_to_elbow8}}" step=".01" required="" name="wrist_to_elbow8" id="wrist_to_elbow" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="biceps">Biceps (circumference) (9)</label>
                                    <input type="text" disabled value="{{$request_measurement->biceps9}}" step=".01" required="" name="biceps9" id="biceps" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="forearm">Forearm (circumference) (10)</label>
                                    <input type="text" disabled  value="{{$request_measurement->forearm10}}" step=".01" required="" name="forearm10" id="forearm" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="wrist_circumference">Wrist circumference (11)</label>
                                    <input type="text" disabled value="{{$request_measurement->wrist_circumference11}}" step=".01" required="" name="wrist_circumference11" id="wrist_circumference" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="waist_to_ankle_length">Waist to ankle length (12)</label>
                                    <input type="text" disabled value="{{$request_measurement->waist_to_ankle_length12}}" step=".01" required="" name="waist_to_ankle_length12" id="waist_to_ankle_length" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="hip_circumference">Hip circumference (13)</label>
                                    <input type="text" disabled value="{{$request_measurement->hip_circumference13}}" step=".01" required="" name="hip_circumference13" id="hip_circumference" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="thigh_circumference">Thigh circumference (14)</label>
                                    <input type="text" disabled  value="{{$request_measurement->thigh_circumference14}}" step=".01" required="" name="thigh_circumference14" id="thigh_circumference" class="form-control" >
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="circumference_of_knees">Circumference of knees (15)</label>
                                    <input type="text" disabled value="{{$request_measurement->circumference_of_knees15}}" step=".01" required="" name="circumference_of_knees15" id="circumference_of_knees" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="calf_circumference">Calf circumference (16)</label>
                                    <input type="text" disabled value="{{$request_measurement->calf_circumference16}}" step=".01" required="" name="calf_circumference16" id="calf_circumference" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="crotch_to_ankle">Crotch to ankle (17)</label>
                                    <input type="text" disabled value="{{$request_measurement->crotch_to_ankle17}}" step=".01" required="" name="crotch_to_ankle17" id="crotch_to_ankle" class="form-control" >
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="knees_to_ankle">Knees to ankle (18)</label>
                                    <input type="text" disabled value="{{$request_measurement->knees_to_ankle18}}" step=".01" required="" name="knees_to_ankle18" id="knees_to_ankle" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="ankle_circumference">Ankle circumference (19)</label>
                                    <input type="text" disabled value="{{$request_measurement->ankle_circumference19}}" step=".01" required="" name="ankle_circumference19" id="ankle_circumference" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="neck_to_ankle">Neck to ankle (20)</label>
                                    <input type="text" disabled value="{{$request_measurement->neck_to_ankle20}}" step=".01" required="" name="neck_to_ankle20" id="neck_to_ankle" class="form-control" >
                                </div>
                            </div>

                        @endif

                        @if (isset($request_measurement->measurement_video))
                            <div class="col-lg-4">
                                <div class="form-group-video">

                                    <video width="400" height="240" controls autoplay>

                                        <source src="{{asset('public/'.$request_measurement->measurement_video)}}"  type="video/webm">
                                            Your browser does not support the video tag.


                                    </video>
                                </div>
                            </div>
                        @endif
                        {{-- @endisset --}}





                    </div>

                </div>

            {{-- @else
                <div class="card-body p-3">
                    <h5>There is no measurments</h5>
                </div>
            @endif --}}
        </div>

    </div>
    <div class="aiz-pagination">
        {{-- {{ $wishlists->links() }} --}}
    </div>
@endsection



@section('script')

@endsection
