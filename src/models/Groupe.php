<?php


namespace ppil\models;


use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    protected $table = 'groupe';
    protected $primaryKey = 'id_groupe';
    public $incrementing = true;
    public $timestamps = false;

    public function trajets()
    {
        return $this->hasMany(Trajet::class, 'id_groupe', 'id_groupe');
    }
}
