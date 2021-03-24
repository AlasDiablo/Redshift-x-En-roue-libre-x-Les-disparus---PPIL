<?php


namespace ppil\models;


use Illuminate\Database\Eloquent\Model;

class VilleIntermediaire extends Model
{
    protected $table = 'ville_intermediaire';
    protected $primaryKey = 'id_trajet';
    public $incrementing = false;
    public $timestamps = false;
}
