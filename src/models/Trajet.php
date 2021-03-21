<?php


namespace ppil\models;

use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    protected $table = 'trajet';
    protected $primaryKey = 'id_trajet';
    public $incrementing = true;
    public $timestamps = false;

    public function conducteur()
    {
        return $this->hasOne(Utilisateur::class, "email", "email_conducteur");
    }

    public function passager()
    {
        return $this->belongsToMany(Trajet::class, 'passager','id_trajet', 'email_passager', 'id_trajet', 'email');
    }
}