@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Model Gallery') }}</h1>
            </div>
        </div>
    </div>
    <!-- Basic Info-->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Basic Info') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('model_upload_image') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('Photo') }}</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}
                                </div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="photo" class="selected-files" required>
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Upload Image') }}</button>
                </div>

            </form>

            <br>

          <form action="{{route('set_model_commission') }}" method="POST">
            @csrf
            <div class="form-group row">
                <label class="col-md-2 col-form-label">{{ translate('Your Commission') }}</label>
                <div class="col-md-10">
                    <input type="number" class="form-control" placeholder="{{ translate('Your Commission') }}" name="model_commission"
                        value="{{ Auth::user()->defaultModelCommission->model_commission ?? '' }}">
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Set Commission') }}</button>
                </div>
            </div>
          </form>

        </div>
    </div>



    <br>


    <div class="row">
        @foreach ($imagesPath as $imagePath)
            <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                <img src="{{ url('/public') . '/' . $imagePath }}" class="w-100 shadow-1-strong rounded mb-4"
                    alt="Boat on Calm Water" />
            </div>
        @endforeach
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('.new-email-verification').on('click', function() {
            $(this).find('.loading').removeClass('d-none');
            $(this).find('.default').addClass('d-none');
            var email = $("input[name=email]").val();

            $.post('{{ route('user.new.verify') }}', {
                _token: '{{ csrf_token() }}',
                email: email
            }, function(data) {
                data = JSON.parse(data);
                $('.default').removeClass('d-none');
                $('.loading').addClass('d-none');
                if (data.status == 2)
                    AIZ.plugins.notify('warning', data.message);
                else if (data.status == 1)
                    AIZ.plugins.notify('success', data.message);
                else
                    AIZ.plugins.notify('danger', data.message);
            });
        });
    </script>

    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif
@endsection
