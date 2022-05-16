<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PessoaRequest extends FormRequest
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
        if ($this->method() == 'PUT') {
            return [
                'nome'       => 'required|min:3',
                'nascimento' => 'date_format:"Y-m-d H:i:s"|before_or_equal:atual',
                'genero'     => 'required',
                'atual'      => \Carbon\Carbon::now()
            ];
        }
        else {
            return [
                'pais_id'    => 'required|exists:pais,id',
                'nome'       => 'required|min:3',
                'nascimento' => 'required|date_format:"Y-m-d H:i:s"|before_or_equal:atual',
                'genero'     => 'required',
                'atual'      => \Carbon\Carbon::now()
            ];
        }    
    }

    public function messages()
    {
        return [
            'pais_id.required'    => 'O campo País é obrigatório.',
            'pais_id.exists'      => 'O país informado não existe.',
            'nome.required'       => 'O campo Nome é obrigatório.',
            'nome.min'            => 'O campo Nome deve conter no mínimo 3 caracteres.',
            'nascimento.required' => 'Informe uma Data válida.',
            'nascimento.date_format' => 'Informe uma Data válida.',
            'nascimento.before_or_equal' => 'A data de nascimento deve ser inferior à data atual.',
            'genero.required'     => 'O campo Sexo é obrigatório.'
        ];
    }
}
