<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitacao extends Model
{
    use SoftDeletes;

    protected $table      = 's_solicitacao';
    protected $primaryKey = 'id';

    protected $fillable = [
        'no_titulo',
        'ds_conteudo',
    ];
}
