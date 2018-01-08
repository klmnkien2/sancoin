<?php

namespace Modules\Pb\Http\Controllers;

use App\Services\LogService;
use App\Helper\Image;
use Illuminate\Http\Request;
use Modules\Pb\Helper\Common;
use Modules\Pb\Validators\ProfileValidator;
use Modules\Pb\Validators\OrderValidator;
use App\User;
use Models\Attachment;
use Models\Profile;
use Auth, DB;
use Illuminate\Support\Facades\Storage;
use Modules\Pb\Services\WalletService;
use Modules\Pb\Services\EtherscanService;
use Modules\Pb\Services\BitcoinService;

class WalletController extends BaseController
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

    public function profile(Request $request)
    {
        $messages = Common::getMessage($request);
        $profile = Profile::where('user_id', Auth::id())->first();
        $attachmentUrls = [];
        if ($profile) {
            $attachments = Attachment::where(['ref_id' => $profile->id, 'type' => 'profiles'])->get();
            foreach ($attachments as $attachment) {
                $attachmentUrls[] = Storage::url($attachment->url);
            }
        }
        return view('pb::wallet.profile', compact('messages', 'profile', 'attachmentUrls'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $profileValidator = new ProfileValidator();
            $data = $request->all();
            $data['user_id'] = Auth::id();
            $validator = $this->checkValidator($data, $profileValidator->validate());
            if (! $validator->fails()) {
                do {
                    $photoArr = isset($data['images']) ? $data['images'] : [];
                    //dd($photoArr);
                    // OTHER LOGIC VALIDATE BUSINESS
                    if (count($photoArr) > 3) {
                        $error = [
                            'common' => [trans('messages.message.profile_upload_over_3')]
                        ];
                        break;
                    }
                    // INSERT OR UPDATE RECORD
                    DB::beginTransaction();
                    unset($data['images']);
                    $profile = Profile::where('user_id', Auth::id())->first();
                    if(!$profile) {
                        $profile = Profile::create($data);
                    } else {
                        $profile->fullname = $data['fullname'];
                        $profile->id_number = $data['id_number'];
                        $profile->id_created_at = $data['id_created_at'];
                        $profile->id_created_by = $data['id_created_by'];
                        $profile->address = $data['address'];
                        $profile->save();
                    }
                    if ($profile) {
                        if (count($photoArr) > 0) {
                            Attachment::where('ref_id', $profile->id)->delete();
                        }
                        foreach ($photoArr as $photo) {
                            $attachment = [];
                            $attachment['ref_id'] = $profile->id;
                            $attachment['name'] = $photo->getClientOriginalName();
                            $attachment['url'] = $photo->storePublicly('public/images');
                            $attachment['type'] = 'profiles';
                            Attachment::create($attachment);
                            //dd($res);
                        }
                    }
                    DB::commit();
                    $success = true;
                    break;
                } while (true);
            } else {
                $error = $validator->getMessageBag()->getMessages();
            }
        } catch (\Exception $e) {
            LogService::write($request, $e);
            DB::rollback();
            //dd($e);
            $error = [
                'common' => [trans('messages.message.reg_common_fail')]
            ];
        }

        if (empty($success)) {
            //dd($error);
            Common::setMessage($request, 'error', $error);
        } else {
            Common::setMessage($request, 'success', ['common' => [trans('messages.message.up_profile_successfully')]]);
        }

        return redirect(route('pb.getProfile'));
    }
	
	public function updateVND(Request $request)
    {
		$error = null;
        try {
            $orderValidator = new OrderValidator();
            $data = $request->all();
            $data['user_id'] = Auth::id();
            
			do {
				$validator = $this->checkValidator($data, $orderValidator->validateUpdateWalletVnd());
				if ($validator->fails()) {
					$error = $validator->getMessageBag()->getMessages();
					break;
				}
				// INSERT OR UPDATE RECORD
				DB::beginTransaction();
				$profile = Profile::where('user_id', Auth::id())->first();
				if(empty($profile) || $profile['fullname'] != $data['account_name']) {
					$error = [
						'common' => [trans('messages.message.not_map_profile_fullname')]
					];
					break;
				}
				$wallet = $this->walletService->getVndWallet(Auth::id());
				$wallet->account_name = $data['account_name'];
				$wallet->account_number = $data['account_number'];
				$wallet->bank_branch = $data['bank_branch'];
				$wallet->save();
				
				DB::commit();
				$success = true;
				break;
			} while (false);

        } catch (\Exception $e) {
            LogService::write($request, $e);
            DB::rollback();
            //dd($e);
            $error = [
                'common' => [trans('messages.message.update_vnd_wallet_fail')]
            ];
        }

        if (!empty($error)) {
            Common::setMessage($request, 'error', $error);
        } else {
            Common::setMessage($request, 'success', ['common' => [trans('messages.message.update_vnd_wallet_success')]]);
        }

        return redirect(route('pb.wallet.vnd'));
    }

    public function eth(Request $request)
    {
        $messages = Common::getMessage($request);
        $walletAddress = '0x0';
        $avaiableAmount = 0;
        $transactionHistory = [];
        $inOrderCoin = $this->walletService->getInOrderCoin(Auth::id(), 'eth');

        $wallet = $this->walletService->getEthWallet(Auth::id());
        //dd($wallet);

        if (!empty($wallet)) {
            $walletAddress = '0x' . $wallet->address;
            $res = $this->etherscanService->getBalance($walletAddress);
            if (!empty($res['result'])) {
                $avaiableAmount = $res['result'];
            }

            //get transaction
            $res = $this->etherscanService->getTransactions($walletAddress, 1);
            if (!empty($res['result'])) {
                $transactionHistory = $res['result'];
            }
            //dd($transactionHistory);
        }

        return view('pb::wallet.eth', compact('messages', 'walletAddress', 'avaiableAmount', 'inOrderCoin', 'transactionHistory'));
    }

    public function btc(Request $request)
    {
        $messages = Common::getMessage($request);
        $walletAddress = '0x0';
        $avaiableAmount = 0;
        $transactionHistory = [];
		$inOrderCoin = $this->walletService->getInOrderCoin(Auth::id(), 'btc');

        $wallet = $this->walletService->getBtcWallet(Auth::id());
        //dd($wallet);

        if (!empty($wallet)) {
            $walletAddress = $wallet->address;
            $fullAddress = $this->bitcoinService->getAllInfo($walletAddress);
            $avaiableAmount = $fullAddress['balance'];
            $transactionHistory = $fullAddress['txs'];
        }

        return view('pb::wallet.btc', compact('messages', 'walletAddress', 'avaiableAmount', 'transactionHistory', 'inOrderCoin'));
    }

    public function vnd(Request $request)
    {
        $messages = Common::getMessage($request);
        $transactionHistory = [];

        $wallet = $this->walletService->getVndWallet(Auth::id());

        return view('pb::wallet.vnd', compact('messages', 'wallet', 'transactionHistory'));
    }

    public function withdraw(Request $request)
    {
        $error = null;
		$data = $request->all();
		
        try {
            do {
                $orderValidator = new OrderValidator;
                $validator = $this->checkValidator($data, $orderValidator->validateWithdraw());
                if ($validator->fails()) {
                    $error = $validator->getMessageBag()->getMessages();
                    break;
                }
				
				if ($data['coin_type'] == 'eth') {
					$ethWallet = $this->walletService->getEthWallet(Auth::id());
					$ethAddress = '0x' . $ethWallet->address;
					$inOrderCoin = $this->walletService->getInOrderCoin(Auth::id(), 'eth');
					$availableETH = 0;
					$res = $this->etherscanService->getBalance($ethAddress);
					if (!empty($res['result'])) {
						$availableETH = $res['result'];
					}
					//dd($availableETH, $inOrderCoin);
					$availableETH = $availableETH - floatval($inOrderCoin) * 1000000000000000000;

					$amount = $request->get('amount', 0);

					if ($availableETH < floatval($amount) * 1000000000000000000) {
						$error = [
							'common' => [trans('messages.message.order_not_enough_money', ['money' => 'ETH'])]
						];
						break;
					}
					
					$txhash = $this->walletService->sendETH(Auth::id(), $data['to_address'], $amount);
					if (!empty($txhash)) {
						Common::setMessage($request, 'success', [
							'common' => [trans('messages.message.transfer_money_success', ['money' => 'ETH']) . ' txhash ' . $txhash]
						]);
					} else {
						throw new \Exception('Unknown exception when call ethereum service');
					}
				}
				
				if ($data['coin_type'] == 'btc') {
					$btcWallet = $this->walletService->getBtcWallet(Auth::id());
					$btcAddress = $btcWallet->address;
					$btcInRequest = $this->walletService->getInOrderCoin(Auth::id(), 'btc');
					$availableBTC = $this->bitcoinService->getBalance($btcAddress);
					$availableBTC = $availableBTC - floatval($btcInRequest) * 100000000;
					$amount = $request->get('amount', 0);

					if ($availableBTC < floatval($amount) * 100000000) {
						$error = [
							'common' => [trans('messages.message.order_not_enough_money', ['money' => 'BTC'])]
						];
						break;
					}
					
					$txhash = $this->walletService->sendBTC(Auth::id(), $data['to_address'], $amount);
					if (!empty($txhash)) {
						Common::setMessage($request, 'success', [
							'common' => [trans('messages.message.transfer_money_success', ['money' => 'BTC']) . ' txhash ' . $txhash]
						]);
					} else {
						throw new \Exception('Unknown exception when call bitcoin service');
					}
				}
            } while (false);
        } catch (\Exception $ex) {
            LogService::write($request, $ex);
            $error = [
                'common' => [trans('messages.message.transfer_money_fail', ['money' => 'Coin'])]
            ];
        }

        if (!empty($error)) {
            Common::setMessage($request, 'error', $error);
        }

		if ($data['coin_type'] == 'btc') {
			return redirect(route('pb.wallet.btc'));
		}
		
		if ($data['coin_type'] == 'eth') {
			return redirect(route('pb.wallet.eth'));
		}
    }
}
