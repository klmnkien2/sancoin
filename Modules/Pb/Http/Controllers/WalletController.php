<?php

namespace Modules\Pb\Http\Controllers;

use App\Services\LogService;
use Illuminate\Http\Request;
use Modules\Pb\Helper\Common;
use Modules\Pb\Validators\UserValidator;
use App\User;
use Auth;
use Modules\Pb\Services\UserService;
use Illuminate\Support\Facades\Mail;
use App\Mail\Registerred;

class WalletController extends BaseController
{

    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function profile()
    {
        return view('pb::wallet.profile');
    }

    public function updateProfile()
    {
        try {
            $userValidator = new UserValidator();
            $validator = $this->checkValidator($request->all(), $userValidator->validateRegister());
            if (! $validator->fails()) {
                $userNameExisted = User::where('username', '=', $request->get('username'))->first();
                $emailExisted = User::where('email', $request->get('email'))->first();
                if ($userNameExisted) {
                    $error = [
                        'common' => [trans('messages.message.reg_username_existed')]
                    ];
                } else if ($emailExisted) {
                    $error = [
                        'common' => [trans('messages.message.reg_email_existed')]
                    ];
                } else {
                    //$this->userService->createUser($request->get('username'), $request->get('email'), $request->get('password'));
                    Mail::to($request->get('email'))->send(new Registerred(['id' => 1, 'activate_code' => 'codemetounlock']));
                    $success = true;
                }
            } else {
                $error = $validator->getMessageBag();
            }
        } catch (\Exception $e) {
            LogService::write($request, $e);
            dd($e);
            $error = [
                'common' => [trans('messages.message.reg_common_fail')]
            ];
        }
        return json_encode([
            'success' => empty($success) ? false : $success,
            'error' => empty($error) ? []: $error
        ]);
    }
}
