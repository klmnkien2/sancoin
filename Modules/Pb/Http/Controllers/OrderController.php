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
use Models\Transaction;

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

        //default value
        $defaultCurrencies = ['VND' => 25000];
        $btcCurrency = get_currencies('BTC');
        $defaultCurrencies['BTC'] = $btcCurrency['lastest'];
        $ethCurrency = get_currencies('ETH');
        $defaultCurrencies['ETH'] = $ethCurrency['lastest'];

        //ETH
        $ethWallet = $this->walletService->getEthWallet(Auth::id());
        $ethAddress = '0x' . $ethWallet->address;
        $ethInRequest = $this->walletService->getInOrderCoin(Auth::id(), 'eth');
        $availableETH = 0;
        $res = $this->etherscanService->getBalance($ethAddress);
        if (!empty($res['result'])) {
            $availableETH = $res['result'];
        }
        $availableETH = $availableETH - floatval($ethInRequest) * 1000000000000000000;
        //BTC
        $btcWallet = $this->walletService->getBtcWallet(Auth::id());
        $btcAddress = $btcWallet->address;
        $btcInRequest = $this->walletService->getInOrderCoin(Auth::id(), 'btc');
        $availableBTC = $this->bitcoinService->getBalance($btcAddress);
        $availableBTC = $availableBTC - floatval($btcInRequest) * 100000000;
        //VND
        $vndWallet = $this->walletService->getVndWallet(Auth::id());
        $inOrderVND = Order::where(['status' => 'waiting', 'user_id' => Auth::id(), 'order_type' => 'buy'])->sum('amount');
        $availableVND = $vndWallet->amount - $inOrderVND;
        $messages = Common::getMessage($request);
        return view('pb::order.index', compact('messages', 'tabActive', 'defaultCurrencies', 'ethAddress', 'availableETH', 'btcAddress', 'availableBTC', 'availableVND', 'myOrders', 'pagination'));
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
        if ($request->isMethod('post')) {
            $error = null;
            try {
                do {
                    $order = Order::find($id);
                    DB::beginTransaction();

                    $vndWallet = $this->walletService->getVndWallet(Auth::id());
                    if (empty($vndWallet) || empty($vndWallet['account_number']) || empty($vndWallet['account_name'])) {
                        $error = [
                            'common' => [trans('messages.message.need_to_update_vnd_wallet')]
                        ];
                        break;
                    }

                    if ($order['user_id'] == Auth::id()) {
                        $error = [
                            'common' => [trans('messages.message.error_order_belong_you')]
                        ];
                        break;
                    }

                    //Accept a sell mean you buy coin by vnd
                    if ($order['order_type'] == 'sell') {
                        $vndWallet = $this->walletService->getVndWallet(Auth::id());
                        $inOrderVND = $this->walletService->getInOrderVND(Auth::id());
                        $availableVND = $vndWallet->amount - $inOrderVND;

                        if ($availableVND < $order['amount']) {
                            $error = [
                                'common' => [trans('messages.message.order_not_enough_money', ['money' => 'VND'])]
                            ];
                            break;
                        }

                        if ($order['coin_type'] == 'btc') {
                            $btcWallet = $this->walletService->getBtcWallet(Auth::id());
                            $btcAddress = $btcWallet->address;
                            $txhash = $this->walletService->sendBTC($order['user_id'], $btcAddress, $order['coin_amount']);
                            if (empty($txhash)) {
                                $error = [
                                    'common' => [trans('messages.message.transfer_money_fail', ['money' => 'BTC'])]
                                ];
                                break;
                            }
                            $order->hash = $txhash;
                        }

                        if ($order['coin_type'] == 'eth') {
                            $ethWallet = $this->walletService->getEthWallet(Auth::id());
                            $ethAddress = '0x' . $ethWallet->address;
                            $txhash = $this->walletService->sendETH($order['user_id'], $ethWallet, $order['coin_amount']);
                            if (empty($txhash)) {
                                $error = [
                                    'common' => [trans('messages.message.transfer_money_fail', ['money' => 'ETH'])]
                                ];
                                break;
                            }
                            $order->hash = $txhash;
                        }
                    }

                    //Accept a sell mean you buy coin by vnd
                    if ($order['order_type'] == 'buy') {
                        if ($order['coin_type'] == 'btc') {
                            $btcWallet = $this->walletService->getBtcWallet(Auth::id());
                            $btcAddress = $btcWallet->address;
                            $inOrderCoin = $this->walletService->getInOrderCoin(Auth::id(), 'btc');
                            $availableBTC = $this->bitcoinService->getBalance($btcAddress);
                            $availableBTC = $availableBTC - floatval($inOrderCoin) * 100000000;

                            if ($availableBTC < $order['coin_amount']) {
                                $error = [
                                    'common' => [trans('messages.message.order_not_enough_money', ['money' => 'BTC'])]
                                ];
                                break;
                            }
                            // transfer coin and get hash
                            $receiveBtcWallet = $this->walletService->getBtcWallet($order['user_id']);
                            $receiveBtcAddress = $receiveBtcWallet->address;
                            $txhash = $this->walletService->sendBTC(Auth::id(), $receiveBtcAddress, $order['coin_amount']);
                            if (empty($txhash)) {
                                $error = [
                                    'common' => [trans('messages.message.transfer_money_fail', ['money' => 'BTC'])]
                                ];
                                break;
                            }
                            $order->hash = $txhash;
                        }

                        if ($order['coin_type'] == 'eth') {
                            $ethWallet = $this->walletService->getEthWallet(Auth::id());
                            $ethAddress = '0x' . $ethWallet->address;
                            $inOrderCoin = $this->walletService->getInOrderCoin(Auth::id(), 'eth');
                            $availableETH = 0;
                            $res = $this->etherscanService->getBalance($ethAddress);
                            if (!empty($res['result'])) {
                                $availableETH = $res['result'];
                            }
                            $availableETH = $availableETH - floatval($inOrderCoin) * 1000000000000000000;

                            if ($availableETH < $order['coin_amount']) {
                                $error = [
                                    'common' => [trans('messages.message.order_not_enough_money', ['money' => 'ETH'])]
                                ];
                                break;
                            }

                            // transfer coin and get hash
                            $receiveEthWallet = $this->walletService->getEthWallet($order['user_id']);
                            $receiveEthAddress = '0x' . $receiveEthWallet->address;
                            $txhash = $this->walletService->sendETH(Auth::id(), $receiveEthAddress, $order['coin_amount']);
                            if (empty($txhash)) {
                                $error = [
                                    'common' => [trans('messages.message.transfer_money_fail', ['money' => 'ETH'])]
                                ];
                                break;
                            }
                            $order->hash = $txhash;
                        }
                    }

                    //OK to get transaction
                    $transaction = [
                        'order_id' => $order->id,
                        'status' => 'pending',
                        'amount' => $order->amount,
                    ];

                    $transaction['to_amount'] = floatval($order->amount) * (1 - floatval($order->fee));
                    $transaction['from_amount'] = floatval($order->amount) * (1 + floatval($order->fee));

                    if ($order['order_type'] == 'buy') {// mean you will sell coin, and receive vnd
                        $transaction['from_id'] = $order->user_id;
                        $vndWallet = $this->walletService->getVndWallet($order->user_id);
                        $transaction['from_account'] = $vndWallet->account_number;

                        $transaction['to_id'] = Auth::id();
                        $vndWallet = $this->walletService->getVndWallet(Auth::id());
                        $transaction['to_account'] = $vndWallet->account_number;
                    }
                    if ($order['order_type'] == 'sell') {//mean you will buy coin
                        $transaction['from_id'] = Auth::id();
                        $vndWallet = $this->walletService->getVndWallet(Auth::id());
                        $transaction['from_account'] = $vndWallet->account_number;

                        $transaction['to_id'] = $order->user_id;
                        $vndWallet = $this->walletService->getVndWallet($order->user_id);
                        $transaction['to_account'] = $vndWallet->account_number;
                    }
                    $tran = Transaction::create($transaction);

                    $order->parner_id = Auth::id();
                    $order->status = 'pending';
                    $order->transaction_id = $tran->id;
                    $order->save();
                    DB::commit();
                    Common::setMessage($request, 'success', ['common' => [trans('messages.message.order_done')]]);
                } while (false);

            } catch (\Exception $ex) {
                LogService::write($request, $ex);
                DB::rollback();
                $error = [
                    'common' => [trans('messages.message.order_fail')]
                ];
            }

            if (!empty($error)) {
                Common::setMessage($request, 'error', $error);
            }
        }

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
                        $vndInRequest = $this->walletService->getInOrderVND(Auth::id());
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
                        $data['coin_to_usd'] = $data['btc_to_usd'];
                    } else if ($data['coin_type'] == 'eth') {
                        $data['coin_amount'] = $data['coin_amount_eth'];
                        $data['coin_to_usd'] = $data['eth_to_usd'];
                    }
                    $data['coin_to_vnd'] = floatval($data['coin_to_usd']) * floatval($data['usd_to_vnd']);
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
