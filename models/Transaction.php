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
        'from_id',
        'from_account',
        'to_id',
        'to_account',
        'amount',
        'status'
    ];
}
