<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    ['middleware' => []],
    function () {

        Route::post(
            'login',
            'Teste\UsuarioController@login'
        );

        Route::get(
            'solicitacao',
            'Teste\SolicitacaoController@listSolicitacao'
        );

        Route::get(
            'solicitacao/{id}',
            'Teste\SolicitacaoController@getSolicitacao'
        );

        Route::post(
            'solicitacao',
            'Teste\SolicitacaoController@addSolicitacao'
        );

        Route::put(
            'solicitacao/{id}',
            'Teste\SolicitacaoController@updSolicitacao'
        );

        Route::delete(
            'solicitacao/{id}',
            'Teste\SolicitacaoController@delSolicitacao'
        );

        Route::group(
            ['middleware' => ['permission:admin']],
            function () {
                Route::prefix('usuario/solicitacao')->group(
                    function () {
                        Route::post(
                            '/aceitar',
                            'Teste\UsuarioController@aceitarSolicitacao'
                        );
                    }
                );
            }
        );
    }
);
