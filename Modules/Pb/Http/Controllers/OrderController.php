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
        return view('pb::order.index', compact('messages', 'tabActive', 'ethAddress', 'availableETH', 'btcAddress', 'availableBTC', 'availableVND'));
    }

    public function all(Request $request, $type)
    {
        $messages = Common::getMessage($request);
        return view('pb::order.all', compact('messages'));
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
                    } else if ($date['coin_type'] == 'eth') {
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
