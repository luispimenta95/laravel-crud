<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'usuario_id',
    ];


    public function usuarios()
    {
        return $this->belongsTo(Usuario::class);
    }
}
