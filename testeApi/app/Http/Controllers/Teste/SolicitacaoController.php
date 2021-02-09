<?php

/**
 * Pacote responsável por SolicitacaoController
 *
 * @category Teste
 * @package  App\Http\Controllers\Teste
 * @author   Carlos <carlos.cabgj@gmail.com>
 * @license  proprietary carlos.cabgj@gmail.com
 * @link     https://github.com/carlos-cabgj
 */

namespace App\Http\Controllers\Teste;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper as Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
// use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Exception;

/**
 * Classe responsável pelo controle de solicitações
 *
 * @category Teste
 * @package  App\Http\Controllers\Teste
 * @author   Carlos <carlos.cabgj@gmail.com>
 * @license  proprietary carlos.cabgj@gmail.com
 * @link     https://github.com/carlos-cabgj
 */
class SolicitacaoController extends Controller
{
    /**
     * Description
     * addSolicitacaoUsuario: Método responsável por chamar o método responsável por
     * adicicionar uma agenda de inscrição e seus lotes
     *
     * @param UsuarioSolicitaAcesso  $request Validação dos dados de entrada
     * @param AddSolicitacaoCadastro $service Serviço de agenda
     *
     * @return json Resposta da API com os dados validados ou mensagem de erro
     */
    public function addSolicitacao(
        \App\Http\Requests\Teste\SolicitacaoAdd $request,
        \App\Services\Teste\SolicitacaoService $service
    ) {
        try {
            $validated = $request->validated();

            return Response::send(
                'Solicitação criada com sucesso',
                $service->addSolicitacao($validated)
            );
        } catch (\Exception $e) {
            return Response::send($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * Description
     * updSolicitacao: Método responsável por chamar o método responsável por
     * adicicionar solicitacação
     *
     * @param SolicitacaoAdd  $request Validação dos dados de entrada
     * @param SolicitacaoService $service Serviço de solicitações
     *
     * @return json Resposta da API com os dados validados ou mensagem de erro
     */
    public function updSolicitacao(
        \App\Http\Requests\Teste\SolicitacaoUpd $request,
        \App\Services\Teste\SolicitacaoService $service,
        $id_solicitacao
    ) {
        try {
            $validated = $request->validated();
            return Response::send(
                'Solicitação atualizada',
                $service->updSolicitacao($id_solicitacao, $validated)
            );
        } catch (\Exception $e) {
            return Response::send($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * Description
     * delSolicitacao: Método responsável por chamar o método responsável por
     * adicicionar solicitacação
     *
     * @param SolicitacaoAdd  $request Validação dos dados de entrada
     * @param SolicitacaoService $service Serviço de solicitações
     *
     * @return json Resposta da API com os dados validados ou mensagem de erro
     */
    public function delSolicitacao(
        $id_solicitacao,
        \App\Services\Teste\SolicitacaoService $service
    ) {
        try {
            return Response::send(
                'Solicitação excluída',
                $service->delSolicitacao($id_solicitacao)
            );
        } catch (\Exception $e) {
            return Response::send($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * Description
     * listSolicitacao: Método responsável por chamar o método responsável por
     * adicicionar solicitacação
     *
     * @param SolicitacaoAdd  $request Validação dos dados de entrada
     * @param SolicitacaoService $service Serviço de solicitações
     *
     * @return json Resposta da API com os dados validados ou mensagem de erro
     */
    public function listSolicitacao(
        \App\Services\Teste\SolicitacaoService $service
    ) {
        try {
            return Response::send(
                '',
                $service->listSolicitacao()
            );
        } catch (\Exception $e) {
            return Response::send($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * Description
     * getSolicitacao: Método responsável por chamar o método responsável por
     * adicicionar solicitacação
     *
     * @param SolicitacaoAdd  $request Validação dos dados de entrada
     * @param SolicitacaoService $service Serviço de solicitações
     *
     * @return json Resposta da API com os dados validados ou mensagem de erro
     */
    public function getSolicitacao(
        $id_solicitacao,
        \App\Services\Teste\SolicitacaoService $service
    ) {
        try {
            return Response::send(
                '',
                $service->getSolicitacao($id_solicitacao)
            );
        } catch (\Exception $e) {
            return Response::send($e->getMessage(), [], $e->getCode());
        }
    }
}
