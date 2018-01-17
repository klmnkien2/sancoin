<?php
namespace Modules\Sa\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Modules\Sa\Helper\Common;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Sa\Validators\AdminValidator;

class AdminLoginController extends Controller
{

    use AuthenticatesUsers;

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
        $error = trans('auth.failed');
        do {
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                $seconds = $this->limiter()->availableIn(
                    $this->throttleKey($request)
                );

                $error = trans('auth.throttle', ['seconds' => $seconds]);
                break;
            }

            $validator = new AdminValidator();
            $validate = $this->checkValidator($request->all(), $validator->validateLogin());
            if ($validate->fails()) {
                $error = array_values($validate->getMessageBag()->getMessages())[0];
                break;
            }

            if (Auth::guard('admin')->attempt([
                'username' => $request->get('username'),
                'password' => $request->get('password')
            ])) {
                return redirect(route('admin.index'));
            }
        } while (false);
        //dd($error);
        Common::setMessage($request, MESSAGE_STATUS_ERROR, $error);
        return redirect(route('admin.login'));
    }

    public function checkValidator($data, $validators) {
        $rules = isset($validators['rules']) ? $validators['rules'] : [];
        $messages = isset($validators['messages']) ? $validators['messages'] : [];
        $attributes = isset($validators['attributes']) ? $validators['attributes'] : [];

        return validator($data, $rules, $messages, $attributes);
    }
}