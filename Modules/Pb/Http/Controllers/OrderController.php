<?php

namespace Modules\Pb\Http\Controllers;

use App\Services\LogService;
use App\Helper\Image;
use Illuminate\Http\Request;
use Modules\Pb\Helper\Common;
use Modules\Pb\Validators\OrderValidator;
use App\User;
use Models\Attachment;
use Models\Profile;
use Auth, DB;
use Illuminate\Support\Facades\Storage;
use Modules\Pb\Services\WalletService;
use Modules\Pb\Services\EtherscanService;
use Modules\Pb\Services\BitcoinService;
use Models\Order;

class OrderController extends BaseController
{

    protected $walletService;
    protected $etherscanService;
    protected $bitcoinService;

    public function __construct(WalletService $walletService, EtherscanService $etherscanService, BitcoinService $bitcoinService)
    {
        $this->walletService = $walletService;
        $this->etherscanService = $etherscanService;
        $this->bitcoinService = $bitcoinService;
    }

    public function index(Request $request)
    {
        $tabActive = $request->get('tab', 'buy');//default

        //my orders
        $myOrders = Order::where(['user_id' => Auth::id()])->orderBy('created_at', 'desc')->paginate(10);
        $total = Order::where(['user_id' => Auth::id()])->count();
        $pagination = [
            'total' => $total,
            'page' => $request->get('page', 1),
            'per' => 10,
            'condition' => $request->all()
        ];

        //ETH
        $ethWallet = $this->walletService->getEthWallet(Auth::id());
        $ethAddress = '0x' . $ethWallet->address;
        $ethInRequest = Order::where(['status' => 'waiting', 'user_id' => Auth::id(), 'coin_type' => 'eth', 'order_type' => 'sell'])->sum('coin_amount');
        $availableETH = 0;
        $res = $this->etherscanService->getBalance($ethAddress);
        if (!empty($res['result'])) {
            $availableETH = $res['result'];
        }
        $availableETH = $availableETH - floatval($ethInRequest) * 1000000000000000000;
        //BTC
        $btcWallet = $this->walletService->getBtcWallet(Auth::id());
        $btcAddress = $btcWallet->address;
        $btcInRequest = Order::where(['status' => 'waiting', 'user_id' => Auth::id(), 'coin_type' => 'btc', 'order_type' => 'sell'])->sum('coin_amount');
        $availableBTC = $this->bitcoinService->getBalance($btcAddress);
        $availableBTC = $availableBTC - floatval($btcInRequest) * 100000000;
        //VND
        $vndWallet = $this->walletService->getVndWallet(Auth::id());
        $inOrderVND = Order::where(['status' => 'waiting', 'user_id' => Auth::id(), 'order_type' => 'buy'])->sum('amount');
        $availableVND = $vndWallet->amount - $inOrderVND;
        $messages = Common::getMessage($request);
        return view('pb::order.index', compact('messages', 'tabActive', 'ethAddress', 'availableETH', 'btcAddress', 'availableBTC', 'availableVND', 'myOrders', 'pagination'));
    }

    public function all(Request $request, $type)
    {
        if ($type == 'sell') {
            $listSeller = Order::where('order_type', 'sell')->orderBy('created_at', 'desc')->paginate(10);
            $total = Order::where('order_type', 'sell')->count();
            $pagination = [
                'total' => $total,
                'page' => $request->get('page', 1),
                'per' => 10,
                'condition' => $request->all()
            ];
            return view('pb::order.all_sell', compact('listSeller', 'pagination'));
        } else if ($type == 'buy') {
            $listBuyer = Order::where('order_type', 'buy')->orderBy('created_at', 'desc')->paginate(10);
            $total = Order::where('order_type', 'buy')->count();
            $pagination = [
                'total' => $total,
                'page' => $request->get('page', 1),
                'per' => 10,
                'condition' => $request->all()
            ];
            return view('pb::order.all_buy', compact('listBuyer', 'pagination'));
        }
    }

    public function detail(Request $request, $id)
    {
        $order = Order::find($id);
        if (empty($order)) {
            $error = [
                'common' => [trans('messages.message.no_order_found')]
            ];
            Common::setMessage($request, 'error', $error);
        }
        $messages = Common::getMessage($request);
        return view('pb::order.detail', compact('order', 'messages'));
    }

    public function cancel(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            Order::find($id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            LogService::write($request, $e);
            DB::rollback();
            $error = [
                'common' => [trans('messages.message.cancel_order_fail')]
            ];
            Common::setMessage($request, 'error', $error);
        }
        Common::setMessage($request, 'success', ['common' => [trans('messages.message.cancel_order_successfully')]]);
        return redirect(route('pb.order.index') . '?tab=myorder');
    }

    public function create(Request $request)
    {
        try {
            $orderValidator = new OrderValidator();
            $data = $request->all();
            $validator = $this->checkValidator($data, $orderValidator->validate());
            if (! $validator->fails()) {
                do {
                    if ($data['order_type'] == 'buy') {
                        $vndInRequest = Order::where(['status' => 'waiting', 'user_id' => Auth::id(), 'order_type' => 'buy'])->sum('amount');
                        $vndWallet = $this->walletService->getVndWallet(Auth::id());
                        $availableVND = $vndWallet->amount;
                        if ($vndInRequest + $data['amount'] > $availableVND) {
                            $error = [
                                'common' => [trans('messages.message.order_not_enough_money', ['money' => 'VND'])]
                            ];
                            break;
                        }
                    }
                    if ($data['order_type'] == 'sell') {
                        if ($data['coin_type'] == 'btc') {
                            $btcInRequest = Order::where(['status' => 'waiting', 'user_id' => Auth::id(), 'coin_type' => 'btc', 'order_type' => 'sell'])->sum('coin_amount');
                            $btcWallet = $this->walletService->getBtcWallet(Auth::id());
                            $availableBTC = $this->bitcoinService->getBalance($btcWallet->address);
                            if ($btcInRequest + $data['coin_amount_btc'] > floatval($availableBTC)/100000000) {
                                $error = [
                                    'common' => [trans('messages.message.order_not_enough_money', ['money' => 'BTC'])]
                                ];
                                break;
                            }
                        } else if ($data['coin_type'] == 'eth') {
                            $ethInRequest = Order::where(['status' => 'waiting', 'user_id' => Auth::id(), 'coin_type' => 'eth', 'order_type' => 'sell'])->sum('coin_amount');
                            $ethWallet = $this->walletService->getEthWallet(Auth::id());
                            $ethAddress = '0x' . $ethWallet->address;
                            $availableETH = 0;
                            $res = $this->etherscanService->getBalance($ethAddress);
                            if (!empty($res['result'])) {
                                $availableETH = $res['result'];
                            }
                            if ($ethInRequest + $data['coin_amount_eth'] > floatval($availableETH)/1000000000000000000) {
                                $error = [
                                    'common' => [trans('messages.message.order_not_enough_money', ['money' => 'ETH'])]
                                ];
                                break;
                            }
                        }
                    }
                    if ($data['coin_type'] == 'btc') {
                        $data['coin_amount'] = $data['coin_amount_btc'];
                    } else if ($data['coin_type'] == 'eth') {
                        $data['coin_amount'] = $data['coin_amount_eth'];
                    }
                    $data['user_id'] = Auth::id();
                    $data['status'] = 'waiting';
                    // INSERT OR UPDATE RECORD
                    DB::beginTransaction();
                    $order = Order::create($data);
                    DB::commit();
                    $success = true;
                    break;
                } while (false);
            } else {
                $error = $validator->getMessageBag()->getMessages();
            }
        } catch (\Exception $e) {
            LogService::write($request, $e);
            DB::rollback();
            dd($e);
            $error = [
                'common' => [trans('messages.message.order_fail')]
            ];
        }

        if (empty($success)) {
            //dd($error);
            Common::setMessage($request, 'error', $error);
            return redirect(route('pb.order.index') . '?tab=' . $data['order_type']);
        } else {
            Common::setMessage($request, 'success', ['common' => [trans('messages.message.order_successfully')]]);
            return redirect(route('pb.order.index') . '?tab=myorder');
        }

    }
}
