<?php

namespace App\Services\Teste;

use App\Services\Service;
use App\Models\Solicitacao;
use App\Helpers\StrHelper;
use Exception;

class SolicitacaoService extends Service
{
    public function __construct()
    {
    }

    public function addSolicitacao(array $dados)
    {
        $solicitacao = new Solicitacao();
        $solicitacao->fill($dados);
        $solicitacao->save();
        return $solicitacao;
    }

    public function listSolicitacao()
    {
        $solicitacao = Solicitacao::get()->all();

        return $solicitacao;
    }

    public function getSolicitacao($id)
    {
        $solicitacao = Solicitacao::find($id);

        if (empty($solicitacao)) {
            throw new Exception('Solicitação não encontrada', 404);
        }

        return $solicitacao;
    }

    public function updSolicitacao($id, array $dados)
    {
        $solicitacao = $this->getSolicitacao($id);

        $solicitacao->fill($dados);
        $solicitacao->save();
        return $solicitacao;
    }

    public function delSolicitacao($id)
    {
        $solicitacao = $this->getSolicitacao($id);

        return $solicitacao->delete();
    }
}
