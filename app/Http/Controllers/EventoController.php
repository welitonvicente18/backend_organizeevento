<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use \App\Models\Evento;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Evento::paginate(20);
        foreach ($result as $evento) {
            $evento->logo_evento = Storage::disk('public')->url($evento->logo_evento);
        }

        if (isset($result[0])) {
            return response()->json(['status' => 'success', 'msg' => 'Encontrado com sucesso.', 'data' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao buscar evento.'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);

        $validator = Validator::make($request->all(), [
            'nome_evento' => 'required',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
            'data_prazo_inscricao' => 'required|date',
            'responsavel' => 'required|string|max:100',
            'telefone_responsavel' => 'required|string|max:150',
            'email_responsavel' => 'required|email',
            'cidade' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:2',
            'local' => 'nullable|string|max:100',
            'descricao' => 'nullable|string|max:500',
            'limite_inscritos' => 'required|integer',
            'logo_evento' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'campo_extra' => 'required'
        ]);

        // Verificar se há erros de validação
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $validateData = $validator->validated();

        // Salvar logo
        if ($request->hasFile('logo_evento')) {
            $file = $request->file('logo_evento');
            $file = $file->store('logo_evento', 'public');
            $validateData['logo_evento'] = $file;
        }

        $validateData['user_id'] = auth()->user()->id;

        $result = Evento::create($validateData);

        if (isset($result['id'])) {
            return response()->json(['status' => 'success', 'msg' => 'Evento criado com sucesso.', 'id' => $result['id']], 201);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao criar evento.'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = Evento::find($id);

        if ($result['logo_evento'] != null) {
            $result['logo_evento'] = Storage::disk('public')->url($result['logo_evento']);
        }

        if (isset($result['id'])) {
            return response()->json(['status' => 'success', 'msg' => 'Encontrado com sucesso.', 'data' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao buscar evento.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $evento = Evento::find($id);
        if ($evento === null) {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao encontrar evento.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nome_evento' => 'required',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
            'data_prazo_inscricao' => 'required|date',
            'responsavel' => 'required|string|max:100',
            'telefone_responsavel' => 'required|string|max:150',
            'email_responsavel' => 'required|email',
            'cidade' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:2',
            'local' => 'nullable|string|max:100',
            'descricao' => 'nullable|string|max:500',
            'limite_inscritos' => 'required|integer',
            'logo_evento' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'campo_extra' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->messages()], 404);
        }

        // Salvar logo
        if ($request->hasFile('logo_evento')) {
            $file = $request->file('logo_evento');
            $file = $file->store('logo_evento', 'public');
            $request->merge(['logo_evento' => $file]);
        }

        $request->merge(['user_id' => auth()->user()->id]);

        $result = $evento->update($request->all());

        if ($result) {
            return response()->json(['status' => 'success', 'msg' => 'Evento atualizado com sucesso.', 'id' => $request['id']], 201);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao atualizar evento.'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evento = Evento::find($id);

        $result = $evento->delete();

        if ($result) {
            return response()->json(['status' => 'success', 'msg' => 'Excluido com sucesso.', 'data' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Erro ao excluir evento.'], 404);
        }
    }
}
