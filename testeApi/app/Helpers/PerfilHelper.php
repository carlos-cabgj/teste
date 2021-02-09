<?php

namespace App\Helpers;

class PerfilHelper
{
    const PREFEITO           = 1;
    const REDE_MUNICIPALISTA = 2;
    const USUARIO_MUNICIPIO  = 3;
    const ADMINISTRADOR      = 4;
    const COLABORADOR        = 5;
    const ENTIDADE_ESTADUAL  = 6;
    const VEREADOR           = 7;
    const CONSULTOR          = 8;
    const SECRETARIO         = 9;
    const USUARIO_IBGE       = 10;

    static function getPerfilById($id)
    {
        $constants = new \ReflectionClass(self::class);

        foreach ($constants->getConstants() as $key => $value) {
            if ($id == $value) {
                return $key;
            }
        }
        return null;
    }
}
