<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class BtcWallet extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'btc_wallets';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'address',
        'private'
    ];
}
