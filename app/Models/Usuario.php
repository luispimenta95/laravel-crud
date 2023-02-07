<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class Usuario extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'selfie','cpf_cnpj'];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
