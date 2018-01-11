<div class="alert {{$type}} alert-dismissable text-left">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <!-- Custom message -->
    @if ($messages && is_array($messages))
        @foreach ($messages as $key => $message)
            {!! $message !!} {!! ($key == count($messages) - 1) ? null : '<br>' !!}
        @endforeach
    @endif

    <!-- System message -->
    @if ($messages && !is_array($messages))
        @if (is_string($messages))
            {!! $messages !!}
        @else ($messages->any())
            @foreach ($messages->all() as $key => $message)
                {!! $message !!} {!! ($key == count($messages->all()) - 1) ? null : '<br>' !!}
            @endforeach
        @endif
    @endif
</div>
