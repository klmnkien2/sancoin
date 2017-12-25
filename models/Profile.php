<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profiles';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'fullname',
        'id_number',
        'id_created_at',
        'id_created_by',
        'address'
    ];
}
