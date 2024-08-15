<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{


    public function index(Request $request)
    {
        $result = User::paginate(20);
        if (isset($result[0])) {
            return response()->json(['status' => 'success', 'msg' => 'Encontrado com sucesso.', 'data' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao buscar evento.'], 404);
        }
    }

    public function show($id)
    {
        $result = User::find($id);

        if (isset($result['id'])) {
            return response()->json(['status' => 'success', 'msg' => 'Encontrado com sucesso', 'data' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao buscar cadastro.'], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if ($user === null) {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao encontrar cadastro.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'telefone' => 'required|string|max:20',
            'perfil' => 'required',
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8'
        ]);

        // Verificar se há erros de validação
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $data = $validator->validated();

        if($data['password'] === null || $data['password'] === '') {
            unset($data['password']);
            unset($data['password_confirmation']);
        }

        $result = $user->update($data);

        if ($result) {
            return response()->json(['status' => 'success', 'msg' => 'Atualizado com sucesso.', 'id' => $request['id']], 201);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao criar cadastrar.'], 404);
        }
    }
}
