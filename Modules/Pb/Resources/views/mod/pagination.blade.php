<?php
$totalPage = $total > 0 ? ceil($total / $per) : 1;
unset($condition['page']);
$url = !empty($url) ? $url : route(Route::currentRouteName());

$url .= '?' . http_build_query($condition);
$pageParam = empty($condition) ? 'page=' : '&page=';
?>

@if($total > 0)
<div class="table-pagination">
    <div class="pagination-summary">
        <span>{!! trans('messages.label.total_result', ['total_record' => $total])!!}</span>
        <span>{!! trans('messages.label.pagination_display', ['from' => ($page - 1) * $per + 1, 'to' => $page * $per > $total ? $total : $page * $per]) !!}</span>
    </div>
    <?php
    $prevPage = $page - 1;
    $afterPage = $page + 1;
    ?>

    <ul class="pagination">
        @if ($prevPage > 0)
            <li><a href='{{$url . $pageParam . $prevPage}}' class="pg-pagination" data-pagination="{{$prevPage}}">{{trans('messages.label.previous_page')}}</a></li>
        @else
            <li class='disabled'><span aria-hidden='true'>{{trans('messages.label.previous_page')}}</span></li>
        @endif

        @if($totalPage > 6)
            @if($page <= 3)
                @for ($i = 1; $i <= 3; $i++)
                    {!! $i == $page ? "<li class='disabled'><span aria-hidden='true'>{$i}</span></li>" : "<li><a href='{$url}{$pageParam}{$i}' class='pg-pagination' data-pagination='{$i}'>{$i}</a></li>" !!}
                @endfor
                <li class='disabled'><span aria-hidden='true'>...</span></li>
                {!! $totalPage == $page ? "<li class='active'><span aria-hidden='true'>{$totalPage}</span></li>" : "<li><a href='{$url}{$pageParam}{$totalPage}' class='pg-pagination' data-pagination='$totalPage'>{$totalPage}</a></li>" !!}
            @elseif ($page > 3 && $page < ($totalPage - 2))
                <li><a href='{{$url . $pageParam . '1'}}' class='pg-pagination' data-pagination='1'>1</a></li>
                <li class='disabled'><span aria-hidden='true'>...</span></li>
                <li><a href='{{$url . $pageParam . ($page - 1)}}' class='pg-pagination' data-pagination='{{$page - 1}}'>{{$page - 1}}</a></li>
                <li class='active'><span aria-hidden='true'>{{$page}}</span></li>
                <li><a href='{{$url . $pageParam . ($page + 1)}}' class='pg-pagination' data-pagination='{{$page + 1}}'>{{$page + 1}}</a></li>
                <li class='disabled'><span aria-hidden='true'>...</span></li>
                <li><a href='{{$url . $pageParam . $totalPage}}' class='pg-pagination' data-pagination='{{$totalPage}}'>{{$totalPage}}</a></li>
            @elseif ($page >= ($totalPage - 2))
                <li><a href='{{$url . $pageParam . '1'}}' class='pg-pagination' data-pagination='1'>1</a></li>
                <li class='disabled'><span aria-hidden='true'>...</span></li>
                @for ($i = ($totalPage - 2); $i <= $totalPage; $i++)
                    {!! $i == $page ? "<li class='active'><span aria-hidden='true'>{$i}</span></li>" : "<li><a href='{$url}{$pageParam}{$i}' class='pg-pagination' data-pagination='{$i}'>{$i}</a></li>" !!}
                @endfor
            @endif
        @else
            @for ($i = 1; $i <= $totalPage; $i++)
                {!! $i == $page ? "<li class='active'><span aria-hidden='true'>{$i}</span></li>" : "<li><a href='{$url}{$pageParam}{$i}' class='pg-pagination' data-pagination='{$i}'>{$i}</a></li>" !!}
            @endfor
        @endif

            @if ($afterPage > $totalPage)
                <li class='disabled'><span aria-hidden='true'>{{trans('messages.label.next_page')}}</span></li>
            @else
                <li><a href='{{$url . $pageParam . $afterPage}}' class='pg-pagination' data-pagination='{{$afterPage}}'>{{trans('messages.label.next_page')}}</a></li>
            @endif

    </ul>

</div>
@endif