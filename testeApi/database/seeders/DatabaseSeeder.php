<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $autorizarSolicitacao = Permission::create([
            'guard_name' => 'api',
            'name' => 'solicitacao_autorizar'
        ]);

        $relatoriosPrincipais = Permission::create([
            'guard_name' => 'api',
            'name' => 'relatorios_principais'
        ]);

        $ = Permission::create([
            'guard_name' => 'api',
            'name' => 'solicitacao_autorizar'
        ]);

        $role = new Role(['name' => 'prefeito', 'guard_name' => 'api']);
        $role->save();

        $role->givePermissionTo($autorizarSolicitacao);

        \Spatie\Permission\Models\Role::create(
            [
            'name' => 'rede_municipalista',
            'guard_name' => 'api',
            ]
        );

        \Spatie\Permission\Models\Role::create(
            [
            'name' => 'usuario_municipio',
            'guard_name' => 'api',
            ]
        );

        \Spatie\Permission\Models\Role::create(
            [
            'name' => 'administrador',
            'guard_name' => 'api',
            ]
        );

        \Spatie\Permission\Models\Role::create(
            [
            'name' => 'colaborador',
            'guard_name' => 'api',
            ]
        );

        \Spatie\Permission\Models\Role::create(
            [
            'name' => 'entidade_estadual',
            'guard_name' => 'api',
            ]
        );

        \Spatie\Permission\Models\Role::create(
            [
            'name' => 'vereador',
            'guard_name' => 'api',
            ]
        );

        \Spatie\Permission\Models\Role::create(
            [
            'name' => 'consultor',
            'guard_name' => 'api',
            ]
        );

        \Spatie\Permission\Models\Role::create(
            [
            'name' => 'secretario',
            'guard_name' => 'api',
            ]
        );

        \spatie\permission\models\role::create(
            [
            'name' => 'usuario_ibge',
            'guard_name' => 'api',
            ]
        );
    }
}
