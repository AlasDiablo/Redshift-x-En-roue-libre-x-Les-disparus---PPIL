<?php


namespace ppil\models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function mesTrajets()
    {
        return $this->hasMany(Trajet::class, "email_conducteur", "email");
    }
}