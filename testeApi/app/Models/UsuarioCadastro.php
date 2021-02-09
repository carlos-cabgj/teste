<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioCadastro extends Model
{
    use HasFactory;

    protected $table      = 's_usuario_cadastro';
    protected $primaryKey = 'id_usuario_cadastro';

    protected $fillable = [
        'no_nome',
        'ds_email',
        'co_cpf',
        'uuid_uf_entidade',
        'uuid_cargo',
        'id_area_atuacao',
        'nr_telefone',
        'nr_celular',
        'uuid_uf',
        'uuid_municipio',
        'ds_arquivo_filiacao',
    ];

    const CREATED_AT = 'dt_criacao';

    const UPDATED_AT = 'dt_atualizacao';

    const DELETED_AT = 'dt_exclusao';

    public $timestamps    = true;
}
