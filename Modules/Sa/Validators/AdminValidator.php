<?php

namespace Modules\Sa\Validators;

class AdminValidator
{
    /**
     * check validate login
     *
     * @return mixed
     */
    public function validateLogin() {

        $res['rules'] = $res['rules'] = [
            'username' => 'required',
            'password' => 'required',
        ];
        $res['messages'] = [];
        $res['attributes'] = [
            'username' => trans('messages.label.username'),
            'password' => trans('messages.label.password'),
        ];;

        return $res;
    }

    public function validateRegister() {
        $max_length_email = config('validate.email_max_length');
        $min_length_password = config('validate.password_min_length');
        $res['rules'] = [
            'username' => 'required|min:4|max:30',
            'email' => 'required|email|max:'.$max_length_email,
            'password' => 'required|confirmed|min:'.$min_length_password,
        ];
        $res['messages'] = [];
        $res['attributes'] = [
            'email' => trans('messages.label.email'),
            'username' => trans('messages.label.username'),
            'password' => trans('messages.label.password'),
        ];

        return $res;
    }

    private function _defaultRule() {
        $max_length_email = config('validate.email_max_length');
        $min_length_password = config('validate.password_min_length');
        return [
            'email' => 'required|email|max:'.$max_length_email,
            'password' => 'required|min:'.$min_length_password,
        ];
    }

    private function _defaultMessage() {
        return [];
    }

    private function _defaultAttribute() {
        return [
            'email' => trans('messages.label.email'),
            'password' => trans('messages.label.password'),
        ];
    }
}