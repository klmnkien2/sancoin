<?php

namespace Modules\Pb\Services;

use Illuminate\Support\Facades\Hash;
use App\User;
use Models\EthWallet;

class WalletService
{

    public function __construct()
    {
        //TODO
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

}

