<?php
$totalPage = $total > 0 ? ceil($total / $per) : 1;
unset($condition['page']);
$url = !empty($url) ? $url : route(Route::currentRouteName());
$url .= '?' . http_build_query($condition);
$pageParam = empty($condition) ? 'page=' : '&page=';
?>
<div class="text-right dataTables_paginate paging_simple_numbers">
    <ul class="pagination">
        <li class="paginate_button previous {{$page == 1 ? 'disabled' : ''}}">
            <a href="{{$page == 1 ? 'javascript:void(0)' : $url . $pageParam . ($page - 1)}}">{{trans('admin.label.previous')}}</a>
        </li>
        @if ($totalPage < 9)
            @for($i = 1; $i <= $totalPage; $i++)
                <li class="paginate_button {{$i == $page ? 'active' : ''}}">
                    <a href="{{$url . $pageParam . $i}}">{{$i}}</a>
                </li>
            @endfor
        @else
            <li class="paginate_button {{$page == 1 ? 'active' : ''}}">
                <a href="{{$url . $pageParam . '1'}}">1</a>
            </li>
            @if ($page > 3 && $page < ($totalPage - 2))
                <li class="paginate_button disabled">
                    <a>…</a>
                </li>
                <li class="paginate_button">
                    <a href="{{$url . $pageParam . ($page - 1)}}">{{$page - 1}}</a>
                </li>
                <li class="paginate_button active">
                    <a href="{{$url . $pageParam . $page}}">{{$page}}</a>
                </li>
                <li class="paginate_button">
                    <a href="{{$url . $pageParam . ($page + 1)}}">{{$page + 1}}</a>
                </li>
                <li class="paginate_button disabled">
                    <a>…</a>
                </li>
            @else
                @if ($page < 4)
                    <li class="paginate_button {{$page == 2 ? 'active' : ''}}">
                        <a href="{{$url .  $pageParam . '2'}}">2</a>
                    </li>
                    <li class="paginate_button {{$page == 3 ? 'active' : ''}}">
                        <a href="{{$url . $pageParam . '3'}}">3</a>
                    </li>
                    @if($page == 3)
                        <li class="paginate_button {{$page == 4 ? 'active' : ''}}">
                            <a href="{{$url . $pageParam . '4'}}">4</a>
                        </li>
                    @endif
                @endif
                <li class="paginate_button disabled">
                    <a>…</a>
                </li>
                @if ($page > $totalPage - 4)
                    @if ($page == $totalPage - 2)
                        <li class="paginate_button {{$page == $totalPage - 3 ? 'active' : ''}}">
                            <a href="{{$url . $pageParam . ($totalPage - 3)}}">{{$totalPage - 3}}</a>
                        </li>
                    @endif
                    <li class="paginate_button {{$page == $totalPage - 2 ? 'active' : ''}}">
                        <a href="{{$url . $pageParam . ($totalPage - 2)}}">{{$totalPage - 2}}</a>
                    </li>
                    <li class="paginate_button {{$page == $totalPage - 1 ? 'active' : ''}}">
                        <a href="{{$url . $pageParam . ($totalPage - 1)}}">{{$totalPage - 1}}</a>
                    </li>
                @endif
            @endif
            <li class="paginate_button {{$page == $totalPage ? 'active' : ''}}">
                <a href="{{$url . $pageParam . $totalPage}}">{{$totalPage}}</a>
            </li>
        @endif
        <li class="paginate_button next {{$page == $totalPage ? 'disabled' : ''}}">
            <a href="{{$page == $totalPage ? 'javascript:void(0)' : $url . $pageParam . ($page + 1)}}">{{trans('admin.label.next')}}</a>
        </li>
    </ul>
</div>

