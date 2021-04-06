<?php


namespace ppil\models;


use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    protected $table = 'Groupe';
    protected $primaryKey = 'id_groupe';
    public $incrementing = true;
    public $timestamps = false;
}
