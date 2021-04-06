<?php


namespace ppil\models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $table = 'Utilisateur';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function mesTrajets()
    {
        return $this->hasMany(Trajet::class, 'email_conducteur', 'email');
    }

    public function mesParticipation()
    {
        return $this->belongsToMany(Trajet::class, 'passager', 'email_passager', 'id_trajet', 'email', 'id_trajet');
    }

    public function memberDe()
    {
        return $this->belongsToMany(Groupe::class, 'membre', 'id_groupe', 'email_membre', 'email', 'id_groupe');
    }

    public function notificationRecu()
    {
        return $this->hasMany(Notification::class, 'utilisateur', 'email');
    }

    public function notificationEnvoyer()
    {
        return $this->hasMany(Notification::class, 'emeteur', 'email');
    }
}
