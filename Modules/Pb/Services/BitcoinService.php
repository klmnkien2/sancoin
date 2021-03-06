<?php

namespace Modules\Pb\Services;

use BlockCypher\Auth\SimpleTokenCredential;
use BlockCypher\Core\BlockCypherCoinSymbolConstants;
use BlockCypher\Rest\ApiContext;
use BlockCypher\Validation\TokenValidator;
use App\Services\LogService;
use GuzzleHttp\Client;

class BitcoinService
{

    /*
    dailycoin.blockcypher_token: "93008b2de1ac4b3cba7455ac17baa930"
    #TEST
    #dailycoin.owner_btc_addr: "mfnxyZ3oicHxj26RzKCdPsayN6BiH4vnRp"
    #REAL
    dailycoin.owner_btc_addr: "1JfQDuT5Qoht5VNebHi2ZLo8SLXHJ9VP5m"
     */
    protected $token = "93008b2de1ac4b3cba7455ac17baa930";

    public function __construct()
    {
        //$this->token = $token;
    }

    public function initApiContext() {
        $apiContextSdkConfigFile = $this->getApiContextUsingConfigIni();
        $apiContexts = $this->createApiContextForAllChains($this->token);
        $apiContexts['sdk_config'] = $apiContextSdkConfigFile;

        return $apiContexts;
    }

    public function getBalance($addr)
    {
        $apiContexts = $this->initApiContext();
        //$addressClient = new \BlockCypher\Client\AddressClient($apiContexts['BTC.main']);
        $addressClient = new \BlockCypher\Client\AddressClient($apiContexts['BTC.test3']);
        try {
            $addressBalance = $addressClient->getBalance($addr);
            //var_dump($addressBalance);die();
            return $addressBalance->getBalance();
        } catch (\Exception $ex) {
            LogService::write(null, $ex);
            return 0;
        }
    }

    public function getFeeAmount()
    {
        try {
            $client = new Client();
            $response = $client->get("https://bitcoinfees.earn.com/api/v1/fees/recommended");
            $json = $response->getBody();

            $aResult = json_decode($json, TRUE);
            return $aResult['fastestFee'];
        } catch (\Exception $ex) {
            LogService::write(null, $ex);
            return null;
        }
    }

    public function getTransactionStatus($txhash)
    {
        $apiContexts = $this->initApiContext();
        //$addressClient = new \BlockCypher\Client\AddressClient($apiContexts['BTC.main']);
        $txClient = new \BlockCypher\Client\TXClient($apiContexts['BTC.test3']);
        try {
            //$txhash = 'debe69475832b8a093b8d4cc749afa261a7c62f2ed7ec4b84460e83f02e020db';
            $transaction = $txClient->get($txhash);
            //dd($transaction);
            return !empty($transaction->getConfirmed()) && !empty($transaction->getReceived());
        } catch (\Exception $ex) {
            LogService::write(null, $ex);
            return false;
        }
    }

    public function getAllInfo($addr)
    {
        $returnResult = [];
        $apiContexts = $this->initApiContext();
        //$addressClient = new \BlockCypher\Client\AddressClient($apiContexts['BTC.main']);
        $addressClient = new \BlockCypher\Client\AddressClient($apiContexts['BTC.test3']);
        try {
            $fullAddress = $addressClient->getFullAddress($addr);
            //dd($fullAddress->getTxs());
            $returnResult['balance'] = $fullAddress->getBalance();
            $returnResult['txs'] = [];
            foreach ($fullAddress->getTxs() as $tx) {
                $transaction = [];
                $transaction['hash'] = $tx->getHash();
                //$addresses = $tx->getAddresses();

                $isSent = false;
                foreach ($tx->getInputs() as $input) {
                    if ($isSent) break;
                    foreach ($input->getAddresses() as $aAddress) {
                        if ($addr == $aAddress) {
                            $isSent = true;
                            break;
                        }
                    }
                }
                //var_dump($isSent);
                if ($isSent) {
                    $transaction['from'] = $addr;
                    $transaction['to'] = null;
                } else {
                    $transaction['from'] = null;
                    $transaction['to'] = $addr;
                }

                $transaction['value'] = null;
                foreach ($tx->getOutputs() as $output) {
                    foreach ($output->getAddresses() as $aAddress) {
                        if ($addr == $aAddress && !$isSent) {
                            $transaction['to'] = $aAddress;
                            $transaction['value'] = $output->getValue();
                            break;
                        } else if ($isSent && $addr != $aAddress) {
                            $transaction['to'] = $aAddress;
                            $transaction['value'] = $output->getValue();
                            break;
                        }
                    }
                    if (!empty($transaction['value'])) {
                        break;
                    }
                }

//                $transaction['to'] = $tx->getOutputs()[0]->getAddresses()[0];
//                $transaction['from'] = $tx->getInputs()[0]->getAddresses()[0];
//                if ($transaction['to'] == $addr) {
//                    $transaction['value'] = $tx->getOutputs()[0]->getValue();
//                }
//                if ($transaction['from'] == $addr) {
//                    $transaction['value'] = $tx->getInputs()[0]->getOutputValue();
//                }

                $transaction['received'] = $tx->getReceived();
                try {
                    $transaction['received'] = \Carbon\Carbon::createFromFormat("Y-m-d\TH:i:s\Z", $transaction['received']);
                } catch(\Exception $exxx) {
                    //dd($transaction['received'],$exxx);
                }
                try {
                    $transaction['received'] = \Carbon\Carbon::createFromFormat("Y-m-d\TH:i:s.u\Z", $transaction['received']);
                } catch(\Exception $exxx) {
                    //dd($transaction['received'],$exxx);
                }
                $returnResult['txs'][] = $transaction;
            }
        } catch (\Exception $ex) {
            LogService::write(null, $ex);
        }

        //dd($returnResult);
        return $returnResult;
    }

    public function send($fromAddress, $private, $toAddress, $amount)
    {
        $apiContexts = $this->initApiContext();
        /// Tx inputs
        $input = new \BlockCypher\Api\TXInput();
        $input->addAddress($fromAddress);

        /// Tx outputs
        $output = new \BlockCypher\Api\TXOutput();
        $output->addAddress($toAddress);
        $output->setValue(intval($amount)); // Satoshis

        /// Tx
        $tx = new \BlockCypher\Api\TX();
        $tx->addInput($input);
        $tx->addOutput($output);

        $txClient = new \BlockCypher\Client\TXClient($apiContexts['BTC.test3']);
        //$txClient = new \BlockCypher\Client\TXClient($apiContexts['BTC.main']);

        try {
            $txSkeleton = $txClient->create($tx);

            $privateKeys = array(
                $private
            );

            /// Sign TXSkeleton
            $txSkeleton = $txClient->sign($txSkeleton, $privateKeys);

            $txSkeleton = $txClient->send($txSkeleton);

            return $txSkeleton->getTx()->getHash();
        } catch (\Exception $ex) {
            LogService::write(null, $ex);
            return false;
        }
    }

    public function generateAddress()
    {
        $apiContexts = $this->initApiContext();
        $addressClient = new \BlockCypher\Client\AddressClient($apiContexts['BTC.test3']);
        //$addressClient = new \BlockCypher\Client\AddressClient($apiContexts['BTC.main']);
        try {

            $addressKeyChain = $addressClient->generateAddress();
            return [
                'address' => $addressKeyChain->getAddress(),
                'private' => $addressKeyChain->getPrivate(),
                'public' => $addressKeyChain->getPublic(),
                'wif' => $addressKeyChain->getWif(),
            ];
        } catch (\Exception $ex) {
            LogService::write(null, $ex);
            return null;
        }
    }

    function createApiContextForAllChains($token)
    {
        $version = 'v1';

        $chainNames = BlockCypherCoinSymbolConstants::CHAIN_NAMES();

        $apiContexts = array();
        foreach ($chainNames as $chainName) {

            list($coin, $chain) = explode(".", $chainName);
            $coin = strtolower($coin);

            $apiContexts[$chainName] = $this->getApiContextUsingConfigArray($token, $chain, $coin, $version);
        }

        return $apiContexts;
    }

    /**
     * Helper method for getting an APIContext for all calls (getting config from ini file)
     * @return \BlockCypher\Rest\ApiContext
     */
    function getApiContextUsingConfigIni()
    {
        // #### SDK configuration
        // Register the sdk_config.ini file in current directory
        // as the configuration source.
        if(!defined("BC_CONFIG_PATH")) {
            define("BC_CONFIG_PATH", __DIR__);
        }

        $apiContext = ApiContext::create('main', 'btc', 'v1');

        return $apiContext;
    }

    /**
     * Helper method for getting an APIContext for all calls (getting config from array)
     * @param string $token
     * @param string $version v1
     * @param string $coin btc|doge|ltc|uro|bcy
     * @param string $chain main|test3|test
     * @return ApiContext
     */
    function getApiContextUsingConfigArray($token, $chain = 'main', $coin = 'btc', $version = 'v1')
    {
        $credentials = new SimpleTokenCredential($token);

        $config = array(
            'mode' => 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/sancoin_blockcypher.log'),
            'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'validation.level' => 'log',
            // 'http.CURLOPT_CONNECTTIMEOUT' => 30
        );

        $apiContext = ApiContext::create($chain, $coin, $version, $credentials, $config);

        ApiContext::setDefault($apiContext);

        return $apiContext;
    }
}
