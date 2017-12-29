<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'partner_id',
        'order_type',
        'coin_type',
        'coin_amount',
        'amount',
        'hash',
        'hash_status',
        'transaction_id',
        'transaction_status',
        'status'
    ];
}
