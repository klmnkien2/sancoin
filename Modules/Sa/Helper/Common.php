<?php

namespace Modules\Sa\Helper;

use Illuminate\Http\Request;

class Common
{

    public static function setMessage($request, $type, $messages)
    {
        $request->session()->flash(
            'alertMsg',
            view('sa::errors.message')->with([
                'type' => $type,
                'messages' => $messages
            ])->render()
        );
    }

    public static function getMessage($request)
    {
        $message = null;
        if ($request->session()->has('alertMsg')) {
            $message = $request->session()->get('alertMsg');
        }

        return $message;
    }

    public static function setFieldError($request, $field_error)
    {
        $request->session()->flash('errField', $field_error);
    }

    public static function getFieldError($request)
    {
        $field_error = [];
        if ($request->session()->has('errField')) {
            $field_error = $request->session()->get('errField');
        }
        return $field_error;
    }
}