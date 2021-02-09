<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;
    use HasRoles;

    protected $table      = 's_usuario';
    protected $primaryKey = 'id_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_nome',
        'uuid_pessoa',
        'uuid_usuario',
        'id_usuario_cadastro',
        'st_permissao_pesquisa',
        'st_concordo_pesquisa',
        'st_acesso_financeiro',
        'nr_acessos',
        'st_ativo',
        'ds_token',
        'dt_acesso',
        'dt_criacao',
        'dt_atualizacao',
        'dt_exclusao',
    ];

    const CREATED_AT = 'dt_criacao';

    const UPDATED_AT = 'dt_atualizacao';

    const DELETED_AT = 'dt_exclusao';
}
