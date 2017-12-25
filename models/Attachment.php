<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'attachments';

    public $timestamps = true;

    protected $fillable = [
        'ref_id',
        'type',
        'url',
        'name'
    ];
}
