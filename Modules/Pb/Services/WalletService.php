<?php

namespace Modules\Pb\Services;

use Illuminate\Support\Facades\Hash;
use App\User;
use Models\EthWallet;
use Models\BtcWallet;
use Models\VndWallet;
use Models\Profile;
use Models\Order;
use Models\Transaction;

class WalletService
{

    protected $bitcoinService;
    protected $etherscanService;

    public function __construct(BitcoinService $bitcoinService, EtherscanService $etherscanService)
    {
        $this->bitcoinService = $bitcoinService;
        $this->etherscanService = $etherscanService;
    }

    public function getEthWallet($userId)
    {
        $wallet = EthWallet::where('user_id', $userId)->first();
        if (empty($wallet)) {
            $this->selectNodejsFolder();
            $nodeRunPath = $this->nodeRunPath();
            $cmd = exec("\"$nodeRunPath\" script.js generateAccount 12345678a@ 2>&1", $out, $err);
            //dd($err, $out);
            $cmdResult = null;
            foreach ($out as $line) {
                if(0 === strpos($line, '<SANCOIN>')) {
                    $ethereum = get_string_between($line, '<SANCOIN>', '</SANCOIN>');
                    break;
                }
            }
            if (empty($ethereum)) {
                throw new \Exception("Can't create ethereum address via nodejs.");
            }
            $data = json_decode($ethereum, true);
            $data['user_id'] = $userId;

            $wallet = EthWallet::create($data);
        }

        return $wallet;
    }

    private function selectNodejsFolder()
    {
        $nodejs_folder = resource_path('assets/sancoin_nodejs/');
        chdir($nodejs_folder);
    }

    private function nodeRunPath()
    {
        return storage_path('app/nodewin/node');
    }

    public function getBtcWallet($userId)
    {
        $wallet = BtcWallet::where('user_id', $userId)->first();
        if (empty($wallet)) {
            $bitcoin = $this->bitcoinService->generateAddress();
            if (empty($bitcoin)) {
                throw new \Exception("Can't create bitcoin address blockcypher.");
            }

            $data = $bitcoin;
            $data['user_id'] = $userId;

            $wallet = BtcWallet::create($data);
        }

        return $wallet;
    }

    public function getVndWallet($userId)
    {
        $wallet = VndWallet::where('user_id', $userId)->first();
        if (empty($wallet)) {

            $data = [
                'user_id' => $userId
            ];

            $profile = Profile::where('user_id', $userId)->first();
            if (!empty($profile)) {
                $data['name'] = $profile['fullname'];
            }
            $wallet = VndWallet::create($data);
        }

        return $wallet;
    }

    public function getInOrderVND($userId)
    {
        $inOrderVNDAsBuyer = Order::where(['status' => 'waiting', 'user_id' => $userId, 'order_type' => 'buy'])->sum('amount');
        $inOrderVNDAsSeller = Order::where(['status' => 'pending', 'partner_id' => $userId, 'order_type' => 'sell'])->sum('amount');
        return $inOrderVNDAsBuyer + $inOrderVNDAsSeller;
    }

    public function getTransactions($userId)
    {
        $tranList = Transaction::where(['from_id' => $userId])->orWhere(['to_id' => $userId])->get();
        return $tranList;
    }

    public function getInOrderCoin($userId, $coin_type)
    {
        $inOrderAsBuyer = Order::where(['status' => 'pending', 'partner_id' => $userId, 'order_type' => 'buy', 'coin_type' => $coin_type])->sum('coin_amount');
        $inOrderAsSeller = Order::where(['status' => 'waiting', 'user_id' => $userId, 'order_type' => 'sell', 'coin_type' => $coin_type])->sum('coin_amount');
        return $inOrderAsBuyer + $inOrderAsSeller;
    }

    public function sendETH($userId, $toAddress, $amount)
    {
        $wallet = $this->getEthWallet($userId);
        if (empty($wallet)) {
            return null;
        }
        $fromAddress = $wallet->address;
        $privateKey = $wallet->private_key;
        $response = $this->etherscanService->getTransactionCount($fromAddress);
        $ethereum_nonce = $response['result'];

        $this->selectNodejsFolder();
        $nodeRunPath = $this->nodeRunPath();
        $cmd = exec("\"$nodeRunPath\" script.js sendETH $fromAddress $toAddress $amount $privateKey $ethereum_nonce 2>&1", $out, $err);

        foreach ($out as $line) {
            if(0 === strpos($line, '<SANCOIN>')) {
                $signed_data = get_string_between($line, '<SANCOIN>', '</SANCOIN>');

                $res = $this->etherscanService->send($signed_data);
                if ($res && empty($res['error']) && !empty($res['result'])) {
                    return $res['result'];
                }
                break;
            }
        }

        return null;
    }
	
	public function sendBTC($userId, $toAddress, $amount)
    {
        $wallet = $this->getBtcWallet($userId);
        if (empty($wallet)) {
            return null;
        }
        $fromAddress = $wallet->address;
        $privateKey = $wallet['private'];
		//dd($privateKey);
        return $this->bitcoinService->send($fromAddress, $privateKey, $toAddress, $amount * 100000000);
    }
}

