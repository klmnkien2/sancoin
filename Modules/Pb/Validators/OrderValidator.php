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
}