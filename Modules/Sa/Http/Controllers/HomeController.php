<?php

namespace Modules\Sa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sa\Helper\Common;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class HomeController extends BaseController
{
    use AuthenticatesUsers;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('sa::index');
    }

    public function getLogin(Request $request)
    {
        $messages = Common::getMessage($request);
        return view('sa::home.login')->with(compact('messages'));
    }

    public function getLogout()
    {
        Auth::guard('web_sa')->logout();
        return redirect(route('sa.login'));
    }

    public function postLogin(Request $request)
    {
        if(!$this->hasTooManyLoginAttempts($request)){
            try {
                $this->incrementLoginAttempts($request);
                $userValidator = new UserValidator();
                $validator = $this->checkValidator($request->all(), $userValidator->validateLogin());

                if(!$validator->fails()){
                    if (Auth::guard('web_sa')->attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
                        return redirect()->intended(route('sa.index'));
                    } else {
                        $error = [trans('labels_sa.SA_L001_M001')];
                    }
                }else{
                    $error = $validator->getMessageBag();
                }
            } catch (\Exception $e) {
                LogService::write($request, $e);
                $error = [trans('labels_sa.SA_L001_M001')];
            }
        }else{
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );
            $message = str_replace(':seconds', $seconds, trans('labels_sa.SA_L001_M002'));
            $error = [$message];
        }
        return $this->redirectToLogin($request, $error);
    }
}
