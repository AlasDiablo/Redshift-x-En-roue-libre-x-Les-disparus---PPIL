<?php


namespace ppil\models;


use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    protected $table = 'membre';
    public $incrementing = false;
    public $timestamps = false;
}