<?php
use \Illuminate\Support\Facades\Request;
use GuzzleHttp\Client;
use Models\Currency;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getBrowserLocale')) {
    function getBrowserLocale()
    {
        $default_lang = env('APP_LOCALE');
        try {
            if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                return $default_lang;
            }
            // Credit: https://gist.github.com/Xeoncross/dc2ebf017676ae946082
            $websiteLanguages = explode(',', env('APP_LOCALES'));
            // Parse the Accept-Language according to:
            // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
            preg_match_all(
                '/([a-z]{1,8})' .       // M1 - First part of language e.g en
                '(-[a-z]{1,8})*\s*' .   // M2 -other parts of language e.g -us
                // Optional quality factor M3 ;q=, M4 - Quality Factor
                '(;\s*q\s*=\s*((1(\.0{0,3}))|(0(\.[0-9]{0,3}))))?/i',
                $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                $langParse);

            $langs = $langParse[1]; // M1 - First part of language
            $quals = $langParse[4]; // M4 - Quality Factor

            $numLanguages = count($langs);
            $langArr = array();

            for ($num = 0; $num < $numLanguages; $num++) {
                $newLang = $langs[$num];
                $newQual = isset($quals[$num]) ?
                    (empty($quals[$num]) ? 1.0 : floatval($quals[$num])) : 0.0;

                // Choose whether to upgrade or set the quality factor for the
                // primary language.
                $langArr[$newLang] = (isset($langArr[$newLang])) ?
                    max($langArr[$newLang], $newQual) : $newQual;
            }

            // sort list based on value
            // langArr will now be an array like: array('EN' => 1, 'ES' => 0.5)
            arsort($langArr, SORT_NUMERIC);

            // The languages the client accepts in order of preference.
            $acceptedLanguages = array_keys($langArr);

            // Set the most preferred language that we have a translation for.
            foreach ($acceptedLanguages as $preferredLanguage) {
                if (in_array($preferredLanguage, $websiteLanguages)) {
                    return $preferredLanguage;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
            return $default_lang;
        }
    }
}

if (!function_exists('check_domain')) {
    function check_domain($domain)
    {
        return Request::server('HTTP_HOST') == $domain;
    }
}

if (!function_exists('set_cookie')) {
    function set_cookie($key, $value, $time = 10)
    {
        return response($value)->withCookie($key, $value, $time);
    }
}

if (!function_exists('render_pagination')) {
    function render_pagination($total = 0, $per = 30, $current_page = 1, $condition = [], $url = '')
    {
        $paginationHtml = '';
        $paginationText = '';
        if ($total > 0) {
            $paginationHtml = view('common.pagination')->with([
                'total' => $total,
                'page' => $current_page,
                'per' => $per,
                'condition' => $condition,
                'url' => $url
            ])->render();

            $from = $total > 0 && ($current_page - 1) * $per < $total ? ($current_page - 1) * $per + 1 : 0;
            $to = $from == 0 ? 0 : ($total < $current_page * $per ? $total : $current_page * $per);

            $paginationText = trans('labels_c.C_L026', [
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

if (!function_exists('menu_active')) {
    function menu_active($url, $type = 'pb', $activeParent = null)
    {
        if ($type == 'pb') {
            $curUrl = Request::url();
            if (strpos($curUrl, $url) !== false) {
                return 'active';
            } else {
                return '';
            }
        }

        if ($type == 'admin') {
            $curUrl = Request::url();
            if (!empty($activeParent)) {
                foreach ($activeParent as $childMenu) {
                    if (strpos($curUrl, $childMenu) !== false) {
                        return 'active menu-open';
                    }
                }
                return '';
            }

            if (strpos($curUrl, $url) !== false) {
                return 'active';
            } else {
                return '';
            }
        }
    }
}

if (!function_exists('filterFieldError')) {
    function filterFieldError($field_error, $key, $exact = true)
    {
        $class_error = '';

        if (!empty($field_error)) {
            if ($exact) {
                if (array_search($key, $field_error) !== false) {
                    $class_error = ' has-error';
                }
            } else {
                foreach ($field_error as $error) {
                    if (stripos($error, $key) !== FALSE) {
                        $class_error = ' has-error';
                    }
                }
            }
        }
        return $class_error;
    }
}

if (!function_exists('render_select_per_page')) {
    function render_select_per_page($per = QUANTITY_PER_PAGE)
    {
        $perPageList = unserialize(LIST_QUANTITY_PER_PAGE);
        $html = '<select class="form-control" id="pg-per-page">';
        foreach ($perPageList as $value) {
            $selected = $value == $per ? 'selected' : '';
            $html .= "<option value='{$value}' {$selected}>{$value}</option>";
        }
        $html .= '</select>';
        return $html;
    }
}

if (!function_exists('calculate_build_time')) {
    function calculate_build_time($year, $month)
    {
        $month = !empty($month) ? $month : '01';
        $datetime1 = new DateTime($year . '-' . $month . '-01');
        $datetime2 = new DateTime();
        $interval = $datetime1->diff($datetime2);

        return trans('labels_cm.CM_BS0010_L001', [
                'year' => !$interval->invert ? $interval->format('%y') : 0,
                'month' => !$interval->invert ? $interval->format('%m') : 0
            ]
        );
    }
}

if (!function_exists('validate_date')) {
    function validate_date($string)
    {
        $date = explode('/', $string);
        if (count($date) == 3 && is_numeric($date[0]) && is_numeric($date[1]) && is_numeric($date[2]) && checkdate($date[1], $date[2], $date[0])) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('convert_money')) {
    function convert_money($money, $type = null)
    {
        if (!is_numeric($money)) {
            return false;
        }
        if (!empty($type)) {
            $new_money = number_format($money);
            $new_money = trans('labels_pb.PS_BD0010_L010', ['money' => $new_money]);
        } else {
            $new_money = trans('labels_c.C_L110', ['money' => number_format($money)]);
        }
        return $new_money;
    }
}

if (!function_exists('convert_search_keyword')) {
    function convert_search_keyword($string)
    {
        $string = preg_replace('/[\\+\\-\\=\\&\\|\\!\\(\\)\\{\\}\\[\\]\\^\\"\\~\\*\\<\\>\\?\\:\\\\\\/]/', addslashes('\\$0'), $string);
        $arrayString = preg_split('/(?<!^)(?!$)/u', $string);
        $convertedString = '';
        foreach ($arrayString as $character) {
            if (mb_strtoupper($character) != mb_strtolower($character)) {
                $character = '[' . mb_strtoupper($character) . mb_strtolower($character) . ']';
            }
            $convertedString .= $character;
        }
        return $convertedString;
    }
}

if (!function_exists('check_selected')) {
    function check_selected($value, $value_compare, $type = 'select')
    {
        if ($value_compare != '') {
            $value_compare = is_numeric($value_compare) ? intval($value_compare) : $value_compare;
        }
        $str_response = '';
        switch ($type) {
            case 'select':
                $str_response = $value === $value_compare ? 'selected="selected"' : '';
                break;
            case 'radio':
            case 'checkbox':
                $str_response = $value === $value_compare ? 'checked="checked"' : '';
                break;
            default:
                break;
        }
        return $str_response;
    }
}

if (!function_exists('url_sync')) {
    function url_sync($path)
    {
        $url_response = '';
        if (!empty($path) && file_exists($path)) {
            $version = filemtime($path);
            $url_response = url($path) . '?v=' . $version;
        }
        return $url_response;
    }
}

if (!function_exists('get_string_between')) {
    function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}

if (!function_exists('get_currencies')) {
    function get_currencies($symbol)
    {
        $returnResults = Cache::get('currency-' . $symbol);
        if (!empty($returnResults)) {
            return $returnResults;
        }

        $returnResults = [];
        $client = new Client();
        $response = $client->get("https://min-api.cryptocompare.com/data/price?fsym=$symbol&tsyms=USD");
        $json = $response->getBody();
        $aResult = json_decode($json, TRUE);
        $returnResults['lastest'] = $aResult['USD'];
        $listInDB = Currency::where('symbol', $symbol)->orderBy('created_at')->take(2)->get();
        $first = null;
        $second = null;
        foreach($listInDB as $currency) {
            if (empty($first)) {
                $first = $currency['to_usd'];
            } else if (empty($second)) {
                $second = $currency['to_usd'];
            }
        }

        if (empty($first) || $first != $aResult['USD']) {
            Currency::create(['name' => $symbol, 'symbol' => $symbol, 'to_usd' => $aResult['USD']]);
            if (!empty($first)) {
                $second = $first;
            }
            $first = $aResult['USD'];
        }
        //dd($first, $second);
        $returnResults['change_percentage'] = 0;
        if (!empty($second)) {
            $returnResults['change_percentage'] = (floatval($first) - floatval($second)) * 100 / floatval($second);
        }

        $returnResults['high'] = Currency::where('symbol', $symbol)->max('to_usd');
        $returnResults['low'] = Currency::where('symbol', $symbol)->min('to_usd');
        $returnResults['avg'] = Currency::where('symbol', $symbol)->avg('to_usd');

        $expiresAt = now()->addMinutes(120);
        Cache::put('currency-' . $symbol, $returnResults, $expiresAt);

        return $returnResults;
    }
}