<?php

namespace Modules\Pb\Validators;

class ProfileValidator
{

    public function validate() {
        $max_length_email = config('validate.email_max_length');
        $min_length_password = config('validate.password_min_length');
        $res['rules'] = [
            'fullname' => 'required|max:255',
            'id_number' => 'required|max:30',
            'id_created_at' => 'required|max:20',
            'id_created_by' => 'required|max:255',
            'address' => 'required|max:500',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ];

        $res['messages'] = [];
        $res['attributes'] = [
            'fullname' => trans('messages.label.fullname'),
            'id_number' => trans('messages.label.id_number'),
            'id_created_at' => trans('messages.label.id_created_at'),
            'id_created_by' => trans('messages.label.id_created_by'),
            'address' => trans('messages.label.address'),
            'images.*' => trans('messages.label.image'),
        ];

        return $res;
    }
}