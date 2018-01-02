<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'currencies';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'symbol',
        'to_usd'
    ];
}
