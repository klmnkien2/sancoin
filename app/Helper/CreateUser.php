<?php
namespace App\Helper;

use Illuminate\Support\Facades\Hash;
use App\User;
use Modules\Pb\Validators\UserValidator;

class CreateUser
{

    public static function compile($username, $email, $password)
    {
        try {
            $dataUser = [
                'email' => $email,
                'password' => $password,
                'username' => $username
            ];
            $userValidator = new UserValidator();
            $validator = validator($dataUser, $userValidator->validateLogin()['rules']);
            if ($validator->fails()) {
                $error = $validator->errors()->all();
                foreach ($error as $value) {
                    $message = $value . "\n";
                    echo $message;
                }
                exit();
            }
            $user = User::where('email', $email)->orWhere('username', '=', $username)->first();
            if ($user) {
                $message = "Error: Email already exist.\n";
                echo $message;
                exit();
            }
            $dataUser['password'] = Hash::make($password);
            $model = new User();
            $model->fill($dataUser);
            $model->save();
        } catch (\Exception $e) {
            $message = "Error : " . $e->getMessage() . "\n";
            echo $message;
        }
    }
}
