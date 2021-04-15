<?php


namespace ppil\models;


use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $table = 'mark';
    protected $primaryKey = ['mark_from', 'mark_for'];
    public $incrementing = false;
    public $timestamps = false;
}