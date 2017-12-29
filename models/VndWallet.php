<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class VndWallet extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'vnd_wallets';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'account_name',
        'account_number',
        'bank_branch',
        'amount'
    ];
}
