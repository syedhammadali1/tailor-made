@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Appointments With Video') }}</h1>
        </div>
    </div>
</div>
<div class="row gutters-10">
    <div class="col-md-12">


        <form action="{{route('appointments_video_post')}}" method="POST">
            @csrf

            <div class="card-body text-center p-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="timer">
                            <p id="seconds">05 : 00 (Mins : Sec)</p>
                        </div>
                        
                        <video id="video" width="820" height="740" autoplay class="video-camera mt-5"></video>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-lg-12">
{{--                        <button type="button" id="start-camera" class="btn btn-dark btn-sm">Start Camera</button>--}}
{{--                        <button type="button" id="stop-camera" class="btn btn-dark btn-sm" >Stop Camera</button>--}}
                        <button type="button" id="pause-record" class="btn btn-primary d-none btn-sm" onclick="pauseTime()">Pause Recording</button>
                        <button type="button" id="start-record" class="btn btn-success btn-sm" onclick="startTime()"> Start Recording </button>
                        <button type="button" id="stop-record" class="btn btn-danger d-none btn-sm">Stop Recording</button>
                    </div>
                    <div class="col-lg-12 mt-3">

                    </div>
                </div>
            </div>


                <input type="hidden" id="appointment_id" name="appointment_id" value="{{$appointment_id}}">


                <div class="col-md-4">

                <div class="mt-4">
                    <a id="download-video" download="test.webm" class="btn btn-light  btn-sm">Download Video</a>
                </div>

                <div class="mt-2">
                    <a href="{{route('appointments_video_delete',['id' => $appointment_id])}}"  class="btn btn-danger btn-sm">Delete Video</a>
                </div>

                </div>

        </form>

    </div>

</div>


@endsection
@section('script')
    <script>

// function startTimer() {
//
//     var trig = setInterval(timer,1000);
//
//     var counter=0;
//     var min=4;
//     var sec=60;
//
//     function timer(){
//
//     sec=--sec;
//
//     if(sec===0){
//     min=--min;
//     sec=59;
//     counter=++counter;
//     }
//
//     if(counter===5){
//     sec=0;
//     min=0;
//     clearInterval(trig);
//
//     }
//
//     document.getElementById("output").innerHTML = min+" : "+sec;
//
//     }
//
//
// }

// function countDownTimer() {
//     var mins = 5
//     var seconds = 60;
//     function tick() {
//         var counter = document.getElementById("output");
//         var current_minutes = mins-1
//         seconds--;
//         counter.innerHTML = current_minutes.toString() + " : " + (seconds < 10 ? "0" : "") + String(seconds);
//         if( seconds > 0 ) {
//             timer = setTimeout(tick, 1000);
//         } else {
//             if(mins > 1){
//                 countdown(mins-1);
//             }
//         }
//     }
//     tick();
//
//     console.log(timer)
// }

var timer;
var c = 60*5; // 5mins

function startTime() {
    clearInterval(timer)
    timer = setInterval(( ) =>{
        updateUi()
    }, 1000);

    $("#start-record").addClass('d-none');
    $("#pause-record").removeClass('d-none');
    $("#stop-record").removeClass('d-none');
}
function updateUi() {
    var counter = document.getElementById("seconds");
    const secondsToMinSecPadded = time => {
        const minutes = "0" + Math.floor(time / 60);
        const seconds = "0" + (time - minutes * 60);
        return minutes.substr(-2) + " : " + seconds.substr(-2);
    };
    // var secc = document.getElementById("seconds").innerHTML = --c;
    var secc = counter.innerHTML = secondsToMinSecPadded(--c);
    console.log(secc);

    if (secc == '00 : 00'){
        media_recorder.stop();
        clearInterval(timer)
        alert('Video has been reached limit of 5 mins');
        $("#start-record").addClass('d-none');
        $("#pause-record").addClass('d-none');
        $("#stop-record").addClass('d-none');
    }
}
function pauseTime() {
    clearInterval(timer)
    $("#start-record").removeClass('d-none');
    $("#pause-record").addClass('d-none');
}

let camera_button = document.querySelector("#start-camera");
let camera_button_stop = document.querySelector("#stop-camera");
let video = document.querySelector("#video");
let start_button = document.querySelector("#start-record");
let pause_button = document.querySelector("#pause-record");
let stop_button = document.querySelector("#stop-record");
let download_link = document.querySelector("#download-video");

let camera_stream = null;
let media_recorder = null;
let blobs_recorded = [];

// camera_button.addEventListener('click', async function() {
//     // alert('clicked')
//    	camera_stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
// 	video.srcObject = camera_stream;
// });
$( document ).ready(async function() {
    camera_stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    video.srcObject = camera_stream;
});

start_button.addEventListener('click', function() {

    // set MIME type of recording as video/webm
    media_recorder = new MediaRecorder(camera_stream, { mimeType: 'video/webm' });

    // event : new recorded video blob available
    media_recorder.addEventListener('dataavailable', function(e) {
		blobs_recorded.push(e.data);
    });

    // event : recording stopped & all blobs sent
    media_recorder.addEventListener('stop', function() {
    	// create local object URL from the recorded video blobs
        var blob = new Blob(blobs_recorded, { type: 'video/webm' });
    	let video_local = URL.createObjectURL(blob);
    	download_link.href = video_local;

        uploadBlob();
    });

    // start recording with each recorded blob having 1 second video
    media_recorder.start(1000);


    // var myInterval = setInterval(function(){
    //
    //     media_recorder.stop();
    //     // uploadBlob();
    //     alert('Video has been reached limit of 5 mins');
    // }, 5000);

});

    stop_button.addEventListener('click', function() {
        media_recorder.stop();
        clearInterval(timer)
        alert('Video has been uploaded');
        $("#start-record").addClass('d-none');
        $("#pause-record").addClass('d-none');
        $("#stop-record").addClass('d-none');
    });


    camera_button_stop.addEventListener('click', function() {

	    video.srcObject = null;
    });

    pause_button.addEventListener('click', function() {
        media_recorder.pause();
    });

    // javascript function that uploads a blob to upload.php
function uploadBlob(){

    var appointment_id = $("#appointment_id").val();

    // create a blob here for testing
    var blob = new Blob(blobs_recorded, { type: 'video/webm' });
    //var blob = yourAudioBlobCapturedFromWebAudioAPI;// for example
    var reader = new FileReader();
    // this function is triggered once a call to readAsDataURL returns



    reader.onload = function(event){
        var fd = new FormData();
        fd.append('fname', 'test.txt');
        // fd.append('data', event.target.result);
        fd.append('measurement_video', blob);
        fd.append('appointment_id', appointment_id);

        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: '{{route("appointments_video_post")}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: fd,
            processData: false,
            contentType: false
        }).done(function(data) {
            // print the output from the upload.php script
            console.log(data);
        });
    };
    // trigger the read from the reader...
    reader.readAsDataURL(blob);

}

    </script>
@endsection
