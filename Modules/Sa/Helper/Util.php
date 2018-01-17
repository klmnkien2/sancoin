<?php

namespace Modules\Sa\Helper;

class Util
{
    public static function render_pagination($total = 0, $per = 30, $current_page = 1, $condition = [], $url = '')
    {
        $paginationHtml = '';
        $paginationText = '';
        if ($total > 0) {
            $paginationHtml = view('sa::common.pagination')->with([
                'total' => $total,
                'page' => $current_page,
                'per' => $per,
                'condition' => $condition,
                'url' => $url
            ])->render();

            $from = $total > 0 && ($current_page - 1) * $per < $total ? ($current_page - 1) * $per + 1 : 0;
            $to = $from == 0 ? 0 : ($total < $current_page * $per ? $total : $current_page * $per);

            $paginationText = trans('admin.message.pagination_summary', [
                'from' => $from,
                'to' => $to,
                'total' => $total
            ]);
        }

        return [
            $paginationText,
            $paginationHtml
        ];
    }
}