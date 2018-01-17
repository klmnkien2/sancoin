<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class EthWallet extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'eth_wallets';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'address',
        'private_key',
        'balance'
    ];
}
