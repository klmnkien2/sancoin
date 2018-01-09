<?php

namespace Modules\Pb\Http\Controllers;

use App\Services\LogService;
use Illuminate\Http\Request;
use Modules\Pb\Helper\Common;
use Modules\Pb\Validators\UserValidator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Models\Order;
use Auth;
use Modules\Pb\Services\UserService;
use Illuminate\Support\Facades\Mail;
use App\Mail\Registerred;
use App;

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
        $listSeller = Order::where(['status' => 'waiting', 'order_type' => 'sell'])->orderBy('created_at', 'desc')->paginate(10);
        $listBuyer = Order::where(['status' => 'waiting', 'order_type' => 'buy'])->orderBy('created_at', 'desc')->paginate(10);

        return view('pb::index')->with(compact('listSeller', 'listBuyer'));
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
                        'password' => $request->get('password'),
                        'status' => [1, 2]
                    ])) {
                        $success = true;
                    } else {
                        $error = [
                            'common' => [trans('auth.failed')]
                        ];
                    }
                } else {
                    $error = $validator->getMessageBag()->getMessages();
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
                do {
                    $userNameExisted = User::where('username', '=', $request->get('username'))->first();
                    $emailExisted = User::where('email', $request->get('email'))->first();
                    if ($userNameExisted) {
                        $error = [
                            'common' => [trans('messages.message.reg_username_existed')]
                        ];
                        break;
                    }

                    if ($emailExisted) {
                        $error = [
                            'common' => [trans('messages.message.reg_email_existed')]
                        ];
                        break;
                    }

                    $user = $this->userService->createUser($request->get('username'), $request->get('email'), $request->get('password'));
                    if ($user) {
                        //Mail::to($request->get('email'))->send(new Registerred(['id' => $user->id, 'activate_code' => $user->activate_code]));
                        $success = true;
                        break;
                    }

                    // IF NOT SUCCESS THROW AN EXCEPTION
                    throw new \Exception("Can't not create user");
                    break;
                } while (true);
            } else {
                $error = $validator->getMessageBag()->getMessages();
            }
        } catch (\Exception $e) {
            LogService::write($request, $e);
            $error = [
                'common' => [trans('messages.message.reg_common_fail')]
            ];
        }
        return json_encode([
            'redirect' => route('pb.pre_activate'),
            'success' => empty($success) ? false : $success,
            'error' => empty($error) ? []: $error
        ]);
    }

    public function preActivate()
    {
        return view('pb::pre_activate');
    }

    public function activate($id, $code)
    {
        $user = app\User::find($id);
        //dd($user);
        if (!empty($user) && $user->activate_code == $code) {
            $user->status = 1;
            $user->save();
            $message = trans('messages.message.register_completed');
        } else {
            $message = trans('messages.message.register_incompleted');
        }
        return view('pb::reg_activate', compact('message'));
    }

    protected function redirectToLogin($request, $message){
        if($message){
            Common::setMessage($request, MESSAGE_STATUS_ERROR, $message);
        }
        return redirect($this->routeLogin)->withInput();
    }

    public function getUser($username)
    {
        $user = User::where('username', $username)->first();
        $createSell = Order::where(['user_id' =>     $user->id, 'order_type' => 'sell'])->count();
        $createBuy = Order::where(['user_id' =>     $user->id, 'order_type' => 'buy'])->count();

        $doneSellVNDAsSeller = Order::where(['user_id' =>     $user->id, 'order_type' => 'sell', 'status' => 'done'])->sum('amount');
        $doneSellVNDAsBuyer = Order::where(['partner_id' =>     $user->id, 'order_type' => 'buy', 'status' => 'done'])->sum('amount');
        $doneSellVND = $doneSellVNDAsSeller + $doneSellVNDAsBuyer;

        $doneBuyVNDAsSeller = Order::where(['user_id' =>     $user->id, 'order_type' => 'buy', 'status' => 'done'])->sum('amount');
        $doneBuyVNDAsBuyer = Order::where(['partner_id' =>     $user->id, 'order_type' => 'sell', 'status' => 'done'])->sum('amount');
        $doneBuyVND = $doneBuyVNDAsSeller + $doneBuyVNDAsBuyer;

        $doneSellBTCAsSeller = Order::where(['user_id' =>     $user->id, 'order_type' => 'sell', 'coin_type' => 'btc', 'status' => 'done'])->sum('coin_amount');
        $doneSellBTCAsBuyer = Order::where(['partner_id' =>     $user->id, 'order_type' => 'buy', 'coin_type' => 'btc', 'status' => 'done'])->sum('coin_amount');
        $doneSellBTC = $doneSellBTCAsSeller + $doneSellBTCAsBuyer;

        $doneBuyBTCAsSeller = Order::where(['user_id' =>     $user->id, 'order_type' => 'buy', 'coin_type' => 'btc', 'status' => 'done'])->sum('coin_amount');
        $doneBuyBTCAsBuyer = Order::where(['partner_id' =>     $user->id, 'order_type' => 'sell', 'coin_type' => 'btc', 'status' => 'done'])->sum('coin_amount');
        $doneBuyBTC = $doneBuyBTCAsSeller + $doneBuyBTCAsBuyer;

        $doneSellETHAsSeller = Order::where(['user_id' =>     $user->id, 'order_type' => 'sell', 'coin_type' => 'eth', 'status' => 'done'])->sum('coin_amount');
        $doneSellETHAsBuyer = Order::where(['partner_id' =>     $user->id, 'order_type' => 'buy', 'coin_type' => 'eth', 'status' => 'done'])->sum('coin_amount');
        $doneSellETH = $doneSellETHAsSeller + $doneSellETHAsBuyer;

        $doneBuyETHAsSeller = Order::where(['user_id' =>     $user->id, 'order_type' => 'buy', 'coin_type' => 'eth', 'status' => 'done'])->sum('coin_amount');
        $doneBuyETHAsBuyer = Order::where(['partner_id' =>     $user->id, 'order_type' => 'sell', 'coin_type' => 'eth', 'status' => 'done'])->sum('coin_amount');
        $doneBuyETH = $doneBuyETHAsSeller + $doneBuyETHAsBuyer;
        return view('pb::get_user', compact('createSell', 'createBuy', 'doneBuyVND', 'doneSellVND', 'doneSellBTC', 'doneBuyBTC', 'doneSellETH', 'doneBuyETH'));
    }
}
