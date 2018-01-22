<?php

namespace Modules\Pb\Services;

use GuzzleHttp\Client;

class EtherscanService
{
    /*
    #TEST
    #dailycoin.token_contract_addr: "0x2079E8451d4bB4C1fCAc5B12F541326bd95e6635"
    #dailycoin.token_owner_addr: "0x8b2797679a371212cF81C2aDB77FB2BB1294816c"
    #dailycoin.token_owner_key: "492dc684051d29e248d86eee9faae9b435c862e1d68f528475f4933d2cf53129"
    #dailycoin.etherscan_api_url: "https://ropsten.etherscan.io/api"
    #REAL
    dailycoin.token_contract_addr: "0xaa33983acfc48be1d76e0f8fe377ffe956ad84ad"
    dailycoin.token_owner_addr: "0x17Cb4341eF4d9132f9c86b335f6Dd6010F6AeA9a"
    dailycoin.token_owner_key: "1611cf75695b61c530852e68c0d9bb6dfdb18a9ea32bd7e6297b33fd0f8d4162"
    dailycoin.etherscan_api_url: "https://api.etherscan.io/api"
    dailycoin.etherscan_api_key: "P4V3KFV9VYDB76YD6V5YEGYBAYC8W5VIIE"

     */
    protected $url = 'https://ropsten.etherscan.io/api';

    protected $apiKey = 'P4V3KFV9VYDB76YD6V5YEGYBAYC8W5VIIE';

    public function __construct()
    {
//         $this->url = $url;
//         $this->apiKey = $apiKey;
    }

    protected function request($aParameters = array(), $useApiKey = true)
    {
        $aResult = false;

        if (empty($aParameters)) {
            return $aResult;
        }

        if ($useApiKey) {
            $aParameters['apikey'] = $this->apiKey;
        }
        $url = $this->url . '?' . http_build_query($aParameters);
        //dd($url);

        $client = new Client();
        $response = $client->get($url);
        $json = $response->getBody();

        $aResult = json_decode($json, TRUE);

        return $aResult;
    }

    public function getBalance($addr)
    {
        $params = [
            'module' => 'account',
            'action' => 'balance',
            'address' => $addr,
            'tag' => 'latest'
        ];

        return $this->request($params);
    }

    public function getGasPrice()
    {
        $params = [
            'module' => 'proxy',
            'action' => 'eth_gasPrice',
            'apikey' => 'noneedtouse'
        ];

        $response = $this->request($params, false);
        if (!empty($response['result'])) {
            $gasPrice = $response['result'];
            $gasPrice = hexdec($gasPrice);
            //dd($response['result'], $gasPrice);
            return $gasPrice;
        }
        return null;
    }

    public function getTransactions($addr, $page, $offset = 15)
    {
        $params = [
            'module' => 'account',
            'action' => 'txlist',
            'address' => $addr,
            'sort' => 'desc',
            'startblock' => 0,
            'endblock' => 99999999,
            'page' => $page,
            'offset' => $offset
        ];

        return $this->request($params);
    }

    public function send($rawTx)
    {
        $params = [
            'module' => 'proxy',
            'action' => 'eth_sendRawTransaction',
            'hex' => $rawTx
        ];
        return $this->request($params);
    }

    public function getTransactionCount($addr)
    {
        $params = [
            'module' => 'proxy',
            'action' => 'eth_getTransactionCount',
            'address' => $addr,
            'tag' => 'latest'
        ];

        return $this->request($params);
    }

    public function getTransactionStatus($txhash)
    {
        $params = [
            'module' => 'proxy',
            'action' => 'eth_getTransactionByHash',
            'txhash' => $txhash,
            'apikey' => 'YourApiKeyToken'
        ];

        return $this->request($params, false);
    }
}

