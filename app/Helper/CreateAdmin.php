<?php
namespace App\Helper;

use Illuminate\Support\Facades\Hash;
use Models\Admin;
use Modules\Sa\Validators\AdminValidator;

class CreateAdmin
{

    public static function compile($username, $email, $password)
    {
        try {
            $dataAdmin = [
                'email' => $email,
                'password' => $password,
                'username' => $username
            ];
            $adminValidator = new AdminValidator();
            $validator = validator($dataAdmin, $adminValidator->validateLogin()['rules']);
            if ($validator->fails()) {
                $error = $validator->errors()->all();
                foreach ($error as $value) {
                    $message = $value . "\n";
                    echo $message;
                }
                exit();
            }
            $admin = Admin::where('email', $email)->orWhere('username', '=', $username)->first();
            if ($admin) {
                $message = "Error: Email already exist.\n";
                echo $message;
                exit();
            }
            $dataAdmin['password'] = Hash::make($password);
            $model = new Admin();
            $model->fill($dataAdmin);
            $model->save();
        } catch (\Exception $e) {
            $message = "Error : " . $e->getMessage() . "\n";
            echo $message;
        }
    }
}
