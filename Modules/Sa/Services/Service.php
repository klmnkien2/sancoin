<?php namespace Modules\Sa\Services;

use Auth;
use GuzzleHttp\Client;

class Service {

    public function __construct()
    {
    }

    public function checkValidator($data, $validators)
    {
        $rules = isset($validators['rules']) ? $validators['rules'] : [];
        $messages = isset($validators['messages']) ? $validators['messages'] : [];
        $attributes = isset($validators['attributes']) ? $validators['attributes'] : [];

        $validator = validator($data, $rules, $messages, $attributes);
        return $validator;
    }
}