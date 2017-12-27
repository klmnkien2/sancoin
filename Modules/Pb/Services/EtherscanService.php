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

    protected function request($aParameters = array())
    {
        $aResult = false;

        if (empty($aParameters)) {
            return $aResult;
        }

        $aParameters['apikey'] = $this->apiKey;
        $url = $this->url . '?' . http_build_query($aParameters);
        //dd($url);

        $client = new Client();
        $response = $client->get($url);
        $json = $response->getBody();
        //$json = file_get_contents($url);
        //$json = $this->file_get_contents_curl($url);
        $aResult = json_decode($json, TRUE);

        return $aResult;
    }
    private function file_get_contents_curl($url)
    {
        $ch = curl_init ();

        curl_setopt ( $ch, CURLOPT_AUTOREFERER, TRUE );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, TRUE );

        $data = curl_exec ( $ch );
        curl_close ( $ch );

        return $data;
    }

    protected function post($url, $request)
    {
        $request = json_encode($request);
        $response = null;

        // performs the HTTP POST
        $opts = array ('http' => array (
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $request
        ));
        $context  = stream_context_create($opts);
        if ($fp = fopen($url, 'r', false, $context)) {
            $response = '';
            while($row = fgets($fp)) {
                $response.= trim($row)."\n";
            }
            $response = json_decode($response,true);
        }

        return $response;
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

    public function getTransactions($addr, $page, $offset = 15)
    {
        $params = [
            'module' => 'account',
            'action' => 'txlist',
            'address' => $addr,
            'sort' => 'asc',
            'startblock' => 0,
            'endblock' => 99999999,
            'page' => $page,
            'offset' => $offset
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
}

