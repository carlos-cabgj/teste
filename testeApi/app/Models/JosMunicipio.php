<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JosMunicipio extends Model
{
    use HasFactory;

    protected $table      = 'd_municipios';
    protected $primaryKey = 'id';

    protected $fillable = [
        '*',
    ];
}
