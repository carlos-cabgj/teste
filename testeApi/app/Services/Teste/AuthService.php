<?php

namespace App\Services\Exclusivo;

use App\Helpers\PerfilHelper as Perfil;
use CNM\ApiContatos\Core as ApiContatos;
use App\Models\Usuario;
use App\Models\UsuarioCadastro;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Exception;
use JWTAuthException;

class AuthService extends \App\Services\Service
{
    public function __construct(
        \App\Services\Geral\LoginAntigoService $loginAntigo
    ) {
        $this->loginAntigo = $loginAntigo;
    }

    /**
     * Description
     * login: Método responsável por logar e criar o usuário caso necessário
     * Primeiro ele verifica se existe usuário na api com permissão
     * Caso não, ele procura nos dados do C.E antigo
     * Caso exista é feito a atualização.
     *
     * @param array $credenciais Validação dos dados de entrada
     *
     * @return Usuario Usuário logado
     */
    public function login($credenciais)
    {
        $dados = [];

        try {
            // $checkUser = ApiContatos::usuario()->listUsuarios([
            //     'search' => $credenciais['login']
            // ]);

            $auth = ApiContatos::usuario()->auth(
                $credenciais['login'],
                $credenciais['password']
            );

        } catch (\CNM\ApiContatos\Models\ModelException $e) {
            if (!in_array($e->getCode(), [401,404])) {
                throw new Exception($e->getMessage(), $e->getCode());
            }

            $loginAntigo = $this->checkLoginAntigo($credenciais);

            if (!empty($loginAntigo)) {
                $this->importaDoConteudoExclusivo($loginAntigo, $credenciais['password']);
            }

            x($loginAntigo->toArray());
        }


        // $perfil = null;
        // foreach ($auth['user']['sistemas'] as $sistema) {
        //     if ($sistema['uuid'] == env('UUID_SISTEMA')) {
        //         $perfil = current($sistema['perfis']);
        //         break;
        //     }
        // }
        //
        // if (empty($perfil)) {
        //     throw new Exception('Usuário não possui acesso no sistema');
        // }

        $usuario = $this->getOrAddUser($auth);

        return $usuario;
    }

    public function importaDoConteudoExclusivo($loginAntigo, $senha)
    {
        //     /*
        //     $loginAntigo['uf_entidade'],
        //     $loginAntigo['sigla_entidade'],
        //     $loginAntigo['empresa'],
        //      */
        // $cargo = [[
        //     //'id' => 163, //natureza id
        //     'pivot' => [
        //         'dt_fim'       => null,
        //         'dt_inicio'    => null,
        //         'municipio_id' => $loginAntigo['id_municipio'],
        //         //$loginAntigo['cargo'],
        //         'natureza_id'  => 163, //TODO COLOCAR O CARGO AQUI
        //     ]
        // ]];
        //
        // $dados = [];
        //
        //
        // $dados['naturezas']        = $cargo;
        //
        // $dados['email']            = [['valor' => $loginAntigo['email'], 'autorizar_contato' => false]];
        //
        // $dados['telefone']         = [['valor' => $loginAntigo['fone_fixo'], 'autorizar_contato' => false]];
        // $dados['celular']          = [['valor' => $loginAntigo['celular'], 'autorizar_contato' => false]];
        //
        // $dados['nome_razao']       = $loginAntigo['nome'];
        // $dados['apelido_fantasia'] = $loginAntigo['apelido'];
        //
        // $dados['municipio_id']     = $loginAntigo['id_municipio'];
        //
        // $dados['status']           = $loginAntigo['ativo'] ? 1 : 0;
        //
        // $dados['tipo_pessoa_id']   = "1";
        // $dados['grupos']           = [];
        // $dados['relacionadas']     = [];
        // $dados['status']           = "1";
        // $dados['cpf_cnpj']         = $loginAntigo['cpf'];
        //
        // $dados['user']             = [
        //     'name'                  => $loginAntigo['nome'],
        //     'email'                 => $loginAntigo['email'],
        //     'password'              => $senha,
        //     'password_confirmation' => $senha,
        // ];

        //$apiPessoa = ApiContatos::pessoa()->addPessoa($dados);

        $apiPessoa = array ( 'pessoa' => array ( 'telefone' => array ( 0 => array ( 'valor' => '', 'autorizar_contato' => false, ), ), 'celular' => array ( 0 => array ( 'valor' => '(61) 98187-0192', 'autorizar_contato' => false, ), ), 'nome_razao' => 'Carlos Alberto Brasil Guimarães Junior', 'apelido_fantasia' => 'Carlos Alberto Brasil Guimarães Junior', 'municipio_id' => 5569, 'status' => '1', 'tipo_pessoa_id' => '1', 'cpf_cnpj' => '889.947.140-14', 'url' => NULL, 'uuid' => '960661e5-ae27-48e1-b4de-76dd2dbfe12c', 'updated_at' => '2021-02-03 10:17:42', 'created_at' => '2021-02-03 10:17:42', 'id' => 28396, 'user' => array ( 'name' => 'Carlos Alberto Brasil Guimarães Junior', 'email' => NULL, 'status' => 1, 'pessoa_id' => 28396, 'uuid' => '95fa0802-728c-4c95-96bd-fe7c5ab91eea', 'updated_at' => '2021-02-03 10:17:43', 'created_at' => '2021-02-03 10:17:43', 'id' => 16998, ), 'email' => array ( 0 => array ( 'valor' => '', 'autorizar_contato' => false, ), ), ), 'message' => 'Novo registro salvo com sucesso!', );

        $pessoa = $apiPessoa['pessoa'];

        $dadosExclusivo = [];

        $dadosExclusivo['no_nome']               = $loginAntigo['nome'];
        $dadosExclusivo['uuid_pessoa']           = $apiPessoa['pessoa']['uuid'];
        $dadosExclusivo['uuid_usuario']          = $apiPessoa['pessoa']['user']['uuid'];
        $dadosExclusivo['id_usuario_cadastro']   = $loginAntigo['id'];
        $dadosExclusivo['ds_token']              = '';
        $dadosExclusivo['st_permissao_pesquisa'] = $loginAntigo['permissao_pesquisa'] ? 's' :  'n';
        $dadosExclusivo['st_concordo_pesquisa']  = $loginAntigo['concordo_pesquisa'] ? 's' :  'n';
        $dadosExclusivo['st_acesso_financeiro']  = $loginAntigo['acesso_financeiro'] ? 's' :  'n';
        $dadosExclusivo['nr_acessos']            = $loginAntigo['conta_acesso'] ?? 0;
        $dadosExclusivo['dt_acesso']             = date('Y-m-d H:i:s');
        $dadosExclusivo['st_ativo']              = substr(strtolower($loginAntigo['ativo'] ?? 'n'), 0, 1);

        $usuario = new Usuario();
        $usuario->fill($dadosExclusivo);
        $usuario->save();

        foreach ($usuario->getRoleNames()->all() as $roleName) {
            $usuario->removeRole($roleName);
        }

        // x(strtolower(Perfil::getPerfilById($loginAntigo['id_perfil'])));
        $usuario->assignRole(
            strtolower(Perfil::getPerfilById($loginAntigo['id_perfil']))
        );

        return $dadosExclusivo;
    }

    public function getOrAddUser($auth)
    {
        $usuario =  Usuario::where('uuid_usuario', $auth['user']['uuid'])->first();

        if (empty($usuario)) {
            $usuario               = new Usuario();
            $usuario->no_nome      = $auth['user']['name'];
            $usuario->uuid_usuario = $auth['user']['uuid'];
            $usuario->uuid_pessoa  = $auth['user']['pessoa']['uuid'];
            $usuario->save();
        }

        foreach ($usuario->getRoleNames()->all() as $roleName) {
            $usuario->removeRole($roleName);
        }

        $usuario->assignRole(strtolower($perfil['descricao']));
        $usuario->save();

        return $usuario;
    }

    public function checkLoginAntigo($credenciais)
    {
        $usuarioAntigo = $this->loginAntigo->login(
            $credenciais['login'],
            $credenciais['password']
        );

        return $usuarioAntigo;
    }

    public function get_ultimos_evento()
    {
        $where = "e.state = 1 AND enddate >= NOW()";

        return DB::connection('portal')->select(
            'e.id as ev_id,
            e.catid,
            c.title as categoria,
            e.title as titulo,
            e.place as local,
            e.website as link,
            e.startdate as inicio'
        )
        ->from('siteCNM_icagenda_events e')
        ->join('siteCNM_icagenda_category c', 'e.catid', '=', 'c.id')
        ->whereRaw($where)
        ->order('startdate ASC')
        ->get()->all();
    }

    public function participa_pesquisa($id_municipio)
    {
        return DB::connection('portal')->select('*')
                        ->from('contribuinte_municipios_pesquisa')
                        ->where(array('id' => $id_municipio))
                        ->get();
    }

    public function registraPrimeiroAcesso($user)
    {
        if ($user->primeiro_acesso == 'SIM') {
            $user = array(
                'email' => $email,
                'nome' => $nome,
                'apelido' => $apelido,
                'cpf' => $cpf,
                'usuario_id' => $id,
                'id_municipio' => $id_municipio,
                'id_municipio_escolhido' => $id_municipio_escolhido,
                'municipio' => $municipio,
                'uf' => $uf,
                'perfil' => $perfil,
                'foto' => $foto,
                'eventos_ativos' => $eventos_ativos,
                'permissao_pesquisa' => $permissao_pesquisa,
                'concordo_pesquisa' => $concordo_pesquisa,
                'acesso_financeiro' => $acesso_financeiro,
                'participa_pesquisa' => $participa_pesquisa
            );

            $this->session->set_userdata($user);

            if ($pagina != '') {
                return redirect(base64url_decode($pagina), 'refresh');
            }
            return redirect('exclusivo/logar/altera_senha', 'refresh');
        }
    }

    public function registerLogin($user)
    {
        x($user->toArray());
        // $eventos_ativos = $this->Comunicacao_eventos_model->get_ultimos_evento()->num_rows();
        $eventos_ativos = count($this->get_ultimos_evento());

        $dadosSessao = [
            'email'                  => $user->email,
            'nome'                   => $user->nome,
            'apelido'                => $user->apelido,
            'id'                     => $user->id,
            'cpf'                    => $user->cpf,
            'foto'                   => $user->foto,
            'logado'                 => true,
            'municipio'              => $user->no_municipio, // colocar nome do município,
            'id_municipio'           => $user->id_municipio,
            'id_municipio_escolhido' => $user->id_municipio,
            'perfil'                 => $user->id_perfil,
            'uf_entidade'            => $user->uf_entidade,
            'permissao_pesquisa'     => $user->permissao_pesquisa,
            'concordo_pesquisa'      => $user->concordo_pesquisa,
            'acesso_financeiro'      => $user->acesso_financeiro,
            // $participa_pesquisa     = $this->logar_model->participa_pesquisa($id_municipio)->num_rows();
            'participa_pesquisa'     => count($this->participa_pesquisa($id_municipio))
        ];

                $session_token = array('user_email' => $user->email);
                $this->session->set_userdata($session_token);

                $dadosMunicipio = $this->SituacaoMunicipio_model->getSituacaoMunicipio($id_municipio);

        if (
                    !in_array(
                        $perfil,
                        [
                        Perfil::ADMINISTRADOR,
                        Perfil::COLABORADOR,
                        Perfil::ENTIDADE_ESTADUAL,
                        Perfil::CONSULTOR
                        ]
                    )
        ) {
            // estava setado fixo
            // if (!permitirAcessoMunicipio($dadosMunicipio)) {
            //     $this->session->set_flashdata('login_error', 'O município do cadastro atualmente n&atilde;o possui permiss&atilde;o de acesso a esta &aacute;rea. Em caso de dúvidas: (61) 2101-6060');
            //     return redirect('exclusivo/logar/login', 'refresh');
            // }
            $municipio = $dadosMunicipio->nome_municipio;
            $contribuinte = ($municipio == "Adamantina") ? "SIM" : $dadosMunicipio->contribuicao;
            $uf = $dadosMunicipio->uf;
        } elseif (@in_array($perfil, Perfil::ENTIDADE_ESTADUAL)) {
            $municipio = 'Todos';
            $uf = $uf_entidade;
            $id_municipio = 0;
        } else {
            $municipio = 'Todos';
            $uf = 'Todos';
            $id_municipio = 0;
        }

                $this->registraPrimeiroAcesso($user);

        if (
                    in_array(
                        $perfil,
                        [Perfil::ADMINISTRADOR,
                        Perfil::COLABORADOR,
                        Perfil::CONSULTOR,
                        Perfil::USUARIO_IBGE]
                    )
        ) {
            if ($perfil == Perfil::ADMINISTRADOR) {
                $dadosSessao['permissao_pesquisa'] = true;
                $dadosSessao['concordo_pesquisa'] = true;
                $dadosSessao['participa_pesquisa'] = true;
                $dadosSessao['acesso_financeiro'] = true;
            } elseif ($perfil == Perfil::COLABORADOR) {
                $dadosSessao['permissao_pesquisa'] = $permissao_pesquisa;
                $dadosSessao['concordo_pesquisa'] = true;
                $dadosSessao['participa_pesquisa'] = true;
                $dadosSessao['acesso_financeiro'] = true;
            }

            // $this->session->set_userdata($user);
            // if ($pagina) {
            //     return redirect(base64url_decode($pagina), 'refresh');
            // }
            // return redirect('exclusivo/conteudo/exclusivo', 'refresh');
            //
        } elseif ($perfil == Perfil::ENTIDADE_ESTADUAL) {
            // 'usuario_id' => $id,
            //'municipio' => $municipio,
            $dadosSessao['eventos_ativos'] = $eventos_ativos;
            $dadosSessao['acesso_financeiro'] = $acesso_financeiro;

            // if ($pagina != '') {
            //     redirect(base64url_decode($pagina), 'refresh');
            // }
            // redirect('exclusivo/conteudo/', 'refresh');
        } elseif ($contribuinte == 'SIM') {
            $bloqueado = $this->logar_model->verifica_bloqueio($id_municipio)->num_rows();

            $user = array('email' => $email,
                'nome' => $nome,
                'apelido' => $apelido,
                'cpf' => $cpf,
                'usuario_id' => $id,
                'logado' => $logado,
                'id_municipio' => $id_municipio,
                'id_municipio_escolhido' => $id_municipio_escolhido,
                'municipio' => $municipio,
                'uf' => $uf,
                'perfil' => $perfil,
                'foto' => $foto,
                'eventos_ativos' => $eventos_ativos,
                'permissao_pesquisa' => $permissao_pesquisa,
                'concordo_pesquisa' => $concordo_pesquisa,
                'participa_pesquisa' => $participa_pesquisa,
                'acesso_financeiro' => $acesso_financeiro
            );

            $this->session->set_userdata($user);

            date_default_timezone_set('America/Sao_Paulo');
            if ($pagina != '') {
                redirect(base64url_decode($pagina), 'refresh');
            }
            redirect('exclusivo/conteudo/', 'refresh');
        } else {
            $this->session->set_flashdata('login_error', 'O município do cadastro atualmente n&atilde;o possui permiss&atilde;o de acesso a esta &aacute;rea!');
            redirect('exclusivo/logar/login', 'refresh');
        }
    }
}
