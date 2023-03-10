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