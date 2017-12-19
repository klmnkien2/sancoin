<?php

namespace Modules\Pb\Validators;

class UserValidator
{
    /**
     * check validate login
     *
     * @return mixed
     */
    public function validateLogin() {

        $res['rules'] = $this->_defaultRule();
        $res['messages'] = $this->_defaultMessage();
        $res['attributes'] = $this->_defaultAttribute();

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