<?php

namespace Modules\Pb\Services;

use Illuminate\Support\Facades\Hash;
use App\User;

class UserService
{

    public function __construct()
    {
        //TODO
    }

    public function createUser($username, $email, $password)
    {
        $dataUser = [
            'email' => $email,
            'password' => Hash::make($password),
            'username' => $username,
            'activate_code' => substr(md5($username . 'sancoinsaltstr'), 0, 8)
        ];

        $model = new User();
        $model->fill($dataUser);
        $model->save();

        return $model;
    }

}

