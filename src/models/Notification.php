<?php


namespace ppil\models;


use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $primaryKey = 'id_notif';
    public $incrementing = true;
    public $timestamps = false;
}
