<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    use SoftDeletes;

    protected $table = "pessoa";
    protected $primaryKey = 'id';
    protected $fillable   = ['nome', 'nascimento', 'genero', 'pais_id'];
    public $timestamps = false;
    protected $casts = [
        'deleted_at' => 'datetime:Y-m-d H:00',
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class, "pais_id", "id");
    }
}
