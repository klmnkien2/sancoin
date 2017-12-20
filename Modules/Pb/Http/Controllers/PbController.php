<?php

namespace Modules\Pb\Http\Controllers;

use App\Services\LogService;
use Illuminate\Http\Request;
use Modules\Pb\Helper\Common;
use Modules\Pb\Validators\UserValidator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Auth;
use Modules\Pb\Services\UserService;

class PbController extends BaseController
{
    use AuthenticatesUsers;

    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('pb::index');
    }

    public function getLogin(Request $request)
    {
        $messages = Common::getMessage($request);
        return view('pb::login')->with(compact('messages'));
    }

    public function getLogout()
    {
        Auth::guard('web')->logout();
        return redirect(route('pb.index'));
    }

    public function postLogin(Request $request)
    {
        if (! $this->hasTooManyLoginAttempts($request)) {
            try {
                $this->incrementLoginAttempts($request);
                $userValidator = new UserValidator();
                $validator = $this->checkValidator($request->all(), $userValidator->validateLogin());
                if (! $validator->fails()) {
                    if (Auth::guard('web')->attempt([
                        'username' => $request->get('username'),
                        'password' => $request->get('password')
                    ])) {
                        $success = true;
                    } else {
                        $error = [
                            trans('auth.failed')
                        ];
                    }
                } else {
                    $error = $validator->getMessageBag();
                }
            } catch (\Exception $e) {
                LogService::write($request, $e);
                $error = [
                    'common' => [trans('auth.failed')]
                ];
            }
        } else {
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn($this->throttleKey($request));
            $message = str_replace(':seconds', $seconds, trans('auth.throttle'));
            $error = [
                'common' => [$message]
            ];
        }
        return json_encode([
            'redirect' => route('pb.index'),
            'success' => empty($success) ? false : $success,
            'error' => empty($error) ? []: $error
        ]);
    }

    public function postRegister(Request $request)
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
                    $this->userService->createUser($request->get('username'), $request->get('email'), $request->get('password'));
                    $success = true;
                }
            } else {
                $error = $validator->getMessageBag();
            }
        } catch (\Exception $e) {
            LogService::write($request, $e);
            $error = [
                'common' => [trans('messages.message.reg_common_fail')]
            ];
        }
        return json_encode([
            'success' => empty($success) ? false : $success,
            'error' => empty($error) ? []: $error
        ]);
    }

    protected function redirectToLogin($request, $message){
        if($message){
            Common::setMessage($request, MESSAGE_STATUS_ERROR, $message);
        }
        return redirect($this->routeLogin)->withInput();
    }
}
