<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'order_id',
        'type',
        'from_id',
        'from_account',
        'to_id',
        'to_account',
        'amount',
        'to_amount',
        'from_amount',
        'status'
    ];

    public function from_user()
    {
        return $this->belongsTo('App\User', 'from_id')->select(array('id', 'username', 'status'));
    }

    public function to_user()
    {
        return $this->belongsTo('App\User', 'to_id')->select(array('id', 'username', 'status'));
    }

    public function order()
    {
        return $this->belongsTo('Models\Order', 'order_id')->select(array('id', 'coin_type', 'order_type'));
    }
}
