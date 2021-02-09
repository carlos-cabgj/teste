<?php

namespace App\Http\Requests\Teste;

use Illuminate\Foundation\Http\FormRequest;

class SolicitacaoAdd extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "no_titulo"    => "required",
            "ds_conteudo" => "required",
        ];
    }
}
