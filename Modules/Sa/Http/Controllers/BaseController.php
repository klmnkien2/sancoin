<?php

namespace Modules\Sa\Http\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
    }

    public function checkValidator($data, $validators) {
        $rules = isset($validators['rules']) ? $validators['rules'] : [];
        $messages = isset($validators['messages']) ? $validators['messages'] : [];
        $attributes = isset($validators['attributes']) ? $validators['attributes'] : [];

        return validator($data, $rules, $messages, $attributes);
    }

}
