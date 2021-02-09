<?php

/**
 * Pacote responsável pelos helpers do sistema
 *
 * @category Helpers
 * @package  App\Helpers
 * @author   CNM <devbsb@cnm.org.br>
 * @license  proprietary cnm.org.br
 * @link     cnm.org.br
 */

namespace App\Helpers;

/**
 * Classe responsável pelo tratamento de resposta do sistema para view
 *
 * @category Helpers
 * @package  App\Helpers
 * @author   CNM <devbsb@cnm.org.br>
 * @license  proprietary cnm.org.br
 * @version  1.0
 * @link     cnm.org.br
 */
class ResponseHelper
{
    /**
     * Description
     * validStatusCode: Método responsável por verificar se o status code existe
     *
     * @param $status identificador do código de status
     *
     * @return bool Resultado do código
     */
    static function validStatusCode($status)
    {
        if (
            ($status >= 400 and $status <= 417) ||
            ($status == 100 or $status == 101)  ||
            ($status >= 200 and $status <= 206) ||
            ($status >= 300 and $status <= 307) ||
            ($status >= 500 and $status <= 505)
        ) {
            return true;
        }
        return false;
    }

    /**
     * Description
     * send: Método responsável tratar e enviar a resposta do sistema
     *
     * @param $msgOrObj Mensagem ou Objeto Exception para tratamento e resposta do sistema
     * @param $data Dados complementares do retorno
     * @param $status identificador do código de status
     *
     * @return bool Resultado do código
     */
    static function send($msgOrObj = '', $data = array(), $status = true)
    {
        if (is_string($msgOrObj)) {
            $msg = $msgOrObj;
        } else {
            $msg = $msgOrObj->getMessage();
            if (method_exists($msgOrObj, 'getError') && !empty($msgOrObj->getError())) {
                $messageApi = json_decode($msgOrObj->getError()['messageAPI'], true);

                if (!empty($messageApi['error'])) {
                    $msg .= ', ' . $messageApi['error'];
                }

                if (isset($messageApi['errors'])) {
                    $data = $messageApi['errors'];
                }

                if (isset($messageApi['exception'])) {
                    $msg .= is_array($messageApi['exception']) ?
                                implode(', ', $messageApi['exception']) :
                                ', ' . $messageApi['exception'];
                }
            }
        }

        if ($status === false && is_string($msgOrObj)) {
            $statusCode = 422;
        } elseif ($status === false) {
            $statusCode = $msgOrObj->getCode();
        } elseif ($status === true) {
            $statusCode = 200;
        } elseif (getType($status) === 'integer') {
            $statusCode = $status;
        } else {
            $statusCode = 200;
        }

        if (!self::validStatusCode($statusCode)) {
            $statusCode = 500;
        }

        return response()->json(
            [
                'resposta' => [
                    'status'   => $status === true || $status == 200 ? true : false,
                    'mensagem' => mb_detect_encoding($msg, 'UTF-8', true) ? $msg : utf8_encode($msg),
                    'conteudo' => $data
                ]
            ],
            $statusCode
        );
    }
}
