@foreach($conversation->messages as $message)
<li class="list-group-item px-0">
    <div class="media mb-2">
      <img class="avatar avatar-xs mr-3" @if($message->user != null) src="{{ uploaded_asset($message->user->avatar_original) }}" @endif onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
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
<!--
@foreach ($conversation->messages as $key => $message)
    @if ($message->user_id == Auth::user()->id)
        <div class="block block-comment mb-3">
            <div class="d-flex flex-row-reverse">
                <div class="pl-3">
                    <div class="block-image">
                        @if ($message->user->avatar_original != null)
                            <img src="{{ uploaded_asset($message->user->avatar_original) }}" class="rounded-circle">
                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="rounded-circle">
                        @endif
                    </div>
                </div>
                <div class="flex-grow-1 ml-5 pl-5">
                    <div class="p-3 bg-gray rounded">
                        {{ $message->message }}
                    </div>
                    <span class="comment-date alpha-7 small mt-1 d-block text-right">
                        {{ date('h:i:m d-m-Y', strtotime($message->created_at)) }}
                    </span>
                </div>
            </div>
        </div>
    @else
        <div class="block block-comment mb-3">
            <div class="d-flex">
                <div class="pr-3">
                    <div class="block-image">
                        @if ($message->user->avatar_original != null)
                            <img src="{{ uploaded_asset($message->user->avatar_original) }}" class="rounded-circle">
                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="rounded-circle">
                        @endif
                    </div>
                </div>
                <div class="flex-grow-1 mr-5 pr-5">
                    <div class="p-3 bg-gray rounded">
                        {{ $message->message }}
                    </div>
                    <span class="comment-date alpha-7 small mt-1 d-block">
                        {{ date('h:i:m d-m-Y', strtotime($message->created_at)) }}
                    </span>
                </div>
            </div>
        </div>
    @endif
@endforeach
-->