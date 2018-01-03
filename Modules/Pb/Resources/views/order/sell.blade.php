<div class="offer-form">
    <form class="pg-order-form" method="POST" action="{{route('pb.order.create')}}">
        <div class="clearfix form-inner">
<!--             <div class="clearix offer-form-top"> -->
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">{{ trans('messages.label.available') }} VND</div>
                        <input class="form-control" type="text" name="current_vnd" value="{{number_format($availableVND , 0 , '.' , ',' )}}" readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">{{ trans('messages.label.available') }} BTC</div>
                        <input class="form-control" type="text" name="current_btc" value="{{ number_format($availableBTC/100000000, 8) }}" readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">{{ trans('messages.label.available') }} ETH</div>
                        <input class="form-control" type="text" name="current_eth" value="{{ number_format($availableETH/1000000000000000000, 18) }}" readonly="true">
                    </div>
                </div>
                <div class="service-fee-text">{{ trans('messages.message.available_money_notice') }}</div>
<!--             </div> -->

            <div class="hr-text"><span>Sell Order</span><hr></div>

            <div class="form-group">
                <label class="sr-only">{{ trans('messages.label.transfer_coin_type') }}</label>
                <div class="input-group">
                    <div class="input-group-addon">{{ trans('messages.label.transfer_coin_type') }}</div>
                    <select name="coin_type" class="form-control">
                        <option value="">{{ trans('messages.label.please_select') }}</option>
                        <option value="btc">BTC</option>
                        <option value="eth">ETH</option>
                    </select>
                </div>
            </div>
            <div class="form-group pg-for-btc display-none">
                <label class="sr-only">{{ trans('messages.label.btc_wallet') }}</label>
                <div class="input-group">
                    <div class="input-group-addon">{{ trans('messages.label.btc_wallet') }}</div>
                    <input type="text" class="form-control" value="{{$btcAddress}}" readonly="true">
                </div>
            </div>
            <div class="form-group pg-for-btc display-none">
                <label class="sr-only">BTC {{ trans('messages.label.you_spend') }}</label>
                <div class="input-group">
                    <div class="input-group-addon">BTC {{ trans('messages.label.you_spend') }}</div>
                    <input type="number" step="0.01" class="form-control pg-param-money" name="coin_amount_btc" min="0" placeholder="BTC">
                </div>
            </div>
            <div class="form-group pg-for-btc display-none">
                <label class="sr-only">{{ trans('messages.label.btc_to_usd') }}</label>
                <div class="input-group">
                    <div class="input-group-addon">{{ trans('messages.label.btc_to_usd') }}</div>
                    <input type="number" step="0.01" class="form-control" name="btc_to_usd" min="0" placeholder="USD" value="{{$defaultCurrencies['BTC']}}" readonly="true">
                </div>
            </div>

            <div class="form-group pg-for-eth display-none">
                <label class="sr-only">{{ trans('messages.label.eth_wallet') }}</label>
                <div class="input-group">
                    <div class="input-group-addon">{{ trans('messages.label.eth_wallet') }}</div>
                    <input type="text" class="form-control" value="{{$ethAddress}}" readonly="true">
                </div>
            </div>
            <div class="form-group pg-for-eth display-none">
                <label class="sr-only">ETH {{ trans('messages.label.you_spend') }}</label>
                <div class="input-group">
                    <div class="input-group-addon">ETH {{ trans('messages.label.you_spend') }}</div>
                    <input type="number" step="0.01" class="form-control pg-param-money" name="coin_amount_eth" min="0" placeholder="ETH">
                </div>
            </div>

            <div class="form-group pg-for-eth display-none">
                <label class="sr-only">{{ trans('messages.label.eth_to_usd') }}</label>
                <div class="input-group">
                    <div class="input-group-addon">{{ trans('messages.label.eth_to_usd') }}</div>
                    <input type="number" step="0.01" class="form-control" name="eth_to_usd" min="0" placeholder="USD" value="{{$defaultCurrencies['ETH']}}" readonly="true">
                </div>
            </div>

            <div class="form-group">
                <label class="sr-only">{{ trans('messages.label.usd_to_vnd') }}</label>
                <div class="input-group">
                    <div class="input-group-addon">{{ trans('messages.label.usd_to_vnd') }}</div>
                    <input type="number" required="required"  step="0.01" class="form-control pg-param-money" name="usd_to_vnd" min="0" placeholder="VND" value="{{$defaultCurrencies['VND']}}">
                </div>
            </div>
            <div class="form-group">
                <label class="sr-only">VND {{ trans('messages.label.you_spend') }}</label>
                <div class="input-group">
                    <div class="input-group-addon">VND {{ trans('messages.label.you_receive') }}</div>
                    <input type="number" class="form-control" name="amount" min="0" placeholder="VND" readonly="true">
                </div>
            </div>

            <div class="button-group">
                {{csrf_field()}}
                <input type="hidden" name="order_type" value="sell">
                <button type="submit" class="btn btn-flat-green"><span class="btn-inner">Sell Order</span></button>
<!--                 <button type="reset" class="btn"><span class="btn-inner">Reset</span></button> -->
            </div>
            <div class="service-fee-text">{{ trans('messages.label.service_fee') }} 0.5/0.5%</div>
        </div>
    </form>
</div>
