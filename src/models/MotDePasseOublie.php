<?php

namespace ppil\models;

use Illuminate\Database\Eloquent\Model;

class MotDePasseOublie extends Model
{
    protected $table = 'forgoten_password';
    protected $primaryKey = 'email';
    public $incrementing = false;
    public $timestamps = false;
}