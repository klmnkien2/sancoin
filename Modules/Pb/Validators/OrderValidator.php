<?php

namespace Modules\Pb\Validators;

class OrderValidator
{

    public function validate() {
        $res['rules'] = [
            'amount' => 'numeric|required',
            'coin_type' => 'required|min:1',
            'coin_amount_btc' => 'required_if:coin_type,btc',
            'coin_amount_eth' => 'required_if:coin_type,eth'
        ];

        $res['messages'] = [];
        $res['attributes'] = [
            'amount' => 'VND ' . trans('messages.label.you_spend'),
            'coin_type' => trans('messages.label.transfer_coin_type'),
            'coin_amount_btc' => 'BTC ' . trans('messages.label.you_receive'),
            'coin_amount_eth' => 'ETH ' . trans('messages.label.you_receive')
        ];

        return $res;
    }

    public function validateWithdraw() {
        $res['rules'] = [
            'amount' => 'numeric|required|min:0',
            'coin_type' => 'required|min:1',
            'to_address' => 'required|min:1'
        ];

        $res['messages'] = [];
        $res['attributes'] = [
            'amount' => trans('messages.label.amount'),
            'coin_type' => trans('messages.label.transfer_coin_type'),
            'to_address' => trans('messages.label.address'),
        ];

        return $res;
    }
}