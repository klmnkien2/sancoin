<?php

namespace Modules\Pb\Services;

use Illuminate\Support\Facades\Hash;
use App\User;
use Models\EthWallet;
use Models\BtcWallet;
use Models\VndWallet;
use Models\Profile;
use Models\Order;

class WalletService
{

    protected $bitcoinService;
    public function __construct(BitcoinService $bitcoinService)
    {
        $this->bitcoinService = $bitcoinService;
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

    public function getInOrderCoin($userId, $coin_type)
    {
        $inOrderAsBuyer = Order::where(['status' => 'pending', 'partner_id' => $userId, 'order_type' => 'buy', 'coin_type' => $coin_type])->sum('coin_amount');
        $inOrderAsSeller = Order::where(['status' => 'waiting', 'user_id' => $userId, 'order_type' => 'sell', 'coin_type' => $coin_type])->sum('coin_amount');
        return $inOrderAsBuyer + $inOrderAsSeller;
    }
}

