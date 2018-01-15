<?php
namespace Modules\Sa\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Modules\Sa\Helper\Common;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    public function getLogin(Request $request)
    {
        $messages = Common::getMessage($request);
        return view('sa::home.login')->with(compact('messages'));
    }

    public function postLogin(Request $request)
    {
        if(!$this->hasTooManyLoginAttempts($request)){
            try {
                $this->incrementLoginAttempts($request);
                $validator = new AdminValidator();
                $validate = $this->checkValidator($request->all(), $validator->validateLogin());

                if(!$validate->fails()){
                    if (Auth::guard('web_sa')->attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
                        return redirect()->intended(route('admin.index'));
                    } else {
                        $error = [trans('labels_sa.SA_L001_M001')];
                    }
                }else{
                    $error = $validate->getMessageBag();
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