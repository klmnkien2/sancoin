<?php

namespace Modules\Pb\Http\Controllers;

use App\Services\LogService;
use Illuminate\Http\Request;
use Modules\Pb\Helper\Common;
use Modules\Pb\Validators\UserValidator;
use Illuminate\Support\Facades\Auth;

class PbController extends BaseController
{
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
        return redirect(route('pb.login'));
    }

    public function postLogin(Request $request)
    {
        if(!$this->hasTooManyLoginAttempts($request)){
            try {
                $this->incrementLoginAttempts($request);
                $userValidator = new UserValidator();
                $validator = $this->checkValidator($request->all(), $userValidator->validateLogin());

                if(!$validator->fails()){
                    if (Auth::guard('web')->attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
                        return redirect()->intended(route('pb.index'));
                    } else {
                        $error = [trans('auth.failed')];
                    }
                }else{
                    $error = $validator->getMessageBag();
                }
            } catch (\Exception $e) {
                LogService::write($request, $e);
                $error = [trans('auth.failed')];
            }
        }else{
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );
            $message = str_replace(':seconds', $seconds, trans('auth.throttle'));
            $error = [$message];
        }
        return $this->redirectToLogin($request, $error);
    }

    protected function redirectToLogin($request, $message){
        if($message){
            Common::setMessage($request, MESSAGE_STATUS_ERROR, $message);
        }
        return redirect($this->routeLogin)->withInput();
    }
}
