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
        'coin_to_usd',
        'usd_to_vnd',
        'coin_to_vnd',
        'amount',
        'hash',
        'hash_status',
        'transaction_id',
        'transaction_status',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->select(array('id', 'username', 'status'));
    }

    public function partner()
    {
        return $this->belongsTo('App\User', 'partner_id')->select(array('id', 'username', 'status'));
    }
}
