<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa;
use App\Models\Pais;
use App\Http\Requests\PessoaRequest;
use Session;
use DB;

class PessoaController extends Controller
{
    public function index()
    {
        $pessoas = Pessoa::all();
        return view('pessoas.index', compact('pessoas'));
    }

    public function indexAjax(Request $request)
    {
        $pessoas = Pessoa::with('pais')->where('deleted_at', '=', null)->orderBy('id', 'ASC')->get();
        return $pessoas;
    }

    public function create()
    {   
        $paises = Pais::all();
        return $paises; 
    }

    public function store(PessoaRequest $request)
    {  
        $sexo = $request->genero;

        if ($sexo != "1" && $sexo != "2") {
            $sexo = null;
        }

        try{ 
            $sql = DB::select('SELECT NEXT VALUE FOR seq_pessoa_object as seq_gen FROM pessoa');

            if(count($sql) > 0) {
                $seq_key = $sql[0]->seq_gen;
            }
            else {
                $seq_key = 1;
            }

            $pessoa = new Pessoa();
            $pessoa->id = $seq_key;
            $pessoa->pais_id = $request->pais_id;
            $pessoa->nome = $request->nome;
            $pessoa->nascimento =  \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $request->nascimento)->format("Y-m-d");
            $pessoa->genero = $sexo;
            $pessoa->deleted_at = null;
            $pessoa->save();
            return response()->json(['success' => 'Cadastro inserido com sucesso!']);
           
        }catch(\Exception $e) {
            return response()->json(['error' => 'Não foi possível realizar o cadastro.']);
        }
    }

    public function edit(Request $request, $id)
    {
        $pessoa = Pessoa::with('pais')->findOrFail($id);
        $paises = Pais::all();
        return response()->json(['pessoa' => $pessoa, 'paises' => $paises]);
    }

    public function update(PessoaRequest $request, $id)
    {
        $pessoa = Pessoa::findOrFail($id);

        try {
            $pessoa->pais_id = isset($request->pais_id) ? $request->input('pais_id') : $pessoa->pais_id;
            $pessoa->nome = isset($request->nome) ? $request->input('nome') : $pessoa->nome;
            $pessoa->nascimento =  isset($request->nascimento) ? \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $request->input('nascimento'))->format("Y-m-d") : $pessoa->nascimento;
            $pessoa->genero =  isset($request->genero) ? $request->input('genero') : $pessoa->genero;
            $pessoa->deleted_at = null;
            $pessoa->save();
            return response()->json(['success' => 'Cadastro atualizado com sucesso!']);

        }catch(\Exception $e) { 
            return response()->json(['error' => 'Não foi possível atualizar o cadastro.']);
        }
    }

    public function destroy($id)
    {
        try {
            Pessoa::where('id', $id)->update(['deleted_at' => \Carbon\Carbon::now()->format('Y-m-d')]);
            return response()->json(['success' => 'Cadastro excluído com sucesso!']);

        }catch (\Exception $e) {
            return response()->json(['success' => 'Não foi possível excluir o cadastro.']);
        }
    }
}
