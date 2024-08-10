<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Inscrito;

class InscritoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Inscrito::paginate(20);

        if (isset($result[0])) {
            return response()->json(['status' => 'success', 'msg' => 'Encontrado com sucesso.', 'data' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao buscar cadastro.'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->clearResquest($request->all());
        $validator = Validator::make($data, [
            'id_eventos' => 'required',
            'nome' => 'required|string|max:200',
            'sobrenome' => 'required|string|max:200',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|string|max:150',
            'cpf' => 'nullable|string|max:11',
            'sexo' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'cidade' => 'nullable|string|max:100',
            'endereco' => 'nullable|string|max:200',
            'cep' => 'nullable|string|max:9'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->messages()], 404);
        }

        $result = Inscrito::create($data);

        if (isset($result['id'])) {
            return response()->json(['status' => 'success', 'msg' => 'Cadastrado criado com sucesso.', 'id' => $result['id']], 201);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao criar cadastrar.'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = Inscrito::find($id);

        if (isset($result['id'])) {
            return response()->json(['status' => 'success', 'msg' => 'Cadastrado com sucesso.', 'data' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao buscar cadastro.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inscrito = Inscrito::find($id);

        if ($inscrito === null) {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao encontrar cadastro.'], 404);
        }

        $data = $this->clearResquest($request->all());

        $validator = Validator::make($data, [
            'id_eventos' => 'required',
            'nome' => 'required|string|max:200',
            'sobrenome' => 'required|string|max:200',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|string|max:150',
            'cpf' => 'nullable|string|max:11',
            'sexo' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'cidade' => 'nullable|string|max:100',
            'endereco' => 'nullable|string|max:200',
            'cep' => 'nullable|string|max:9'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->messages()], 404);
        }

        $result = $inscrito->update($data);

        if ($result) {
            return response()->json(['status' => 'success', 'msg' => 'Cadastrado com sucesso.', 'id' => $request['id']], 201);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao criar cadastrar.'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evento = Inscrito::find($id);

        $result = $evento->delete();

        if ($result) {
            return response()->json(['status' => 'success', 'msg' => 'Excluido com sucesso.', 'data' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao excluir evento.'], 404);
        }
    }

    private function clearResquest($request)
    {
        if (isset($request['cpf'])) {
            $request['cpf'] = preg_replace('/[^0-9]/', '', $request['cpf']);
        }
        if (isset($request['telefone'])) {
            $request['telefone'] = preg_replace('/[^0-9]/', '', $request['telefone']);
        }
        if (isset($request['cep'])) {
            $request['cep'] = preg_replace('/[^0-9]/', '', $request['cep']);
        }
        return $request;
    }
}
