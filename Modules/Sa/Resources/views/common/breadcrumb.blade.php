<section class="content-header">
    <ol class="breadcrumb">
        @foreach($breadcrumb as $key => $item)
            <li {{ $item == end($breadcrumb) ? "class=active" : "" }}>
                <a href="{{ !empty($item) ? $item : "#"}}">{{$key }}</a>
            </li>
        @endforeach
    </ol>
</section>
