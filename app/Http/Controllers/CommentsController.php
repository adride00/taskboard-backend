<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $comments = Comments::join('tasks', 'comments.task_id', '=', 'tasks.id')
                ->join('users', 'comments.user_id', '=', 'users.id')
                ->select('comments.*', 'tasks.title as task_name', 'users.name as user_name')
                ->get();

            return response()->json($comments);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No fue posible obtener el recurso',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $customMessages = [
                'required' => 'El campo :attribute es obligatorio.',
                'string' => 'El campo :attribute debe ser una cadena de texto.',
            ];

            $validateComment = $request->validate([
                "task_id" => "required|integer",
                "user_id" => "required|integer",
                "content" => "required|string"
            ], $customMessages);

            $comment = new Comments();
            $comment->task_id = $validateComment["task_id"];
            $comment->user_id = $validateComment["user_id"];
            $comment->content = $validateComment["content"];
            $comment->save();

            return response()->json([
                "message" => "Comentario creado",
                "comment" => $comment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el comentario',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $registro = Comments::join('tasks', 'comments.task_id', '=', 'tasks.id')
                ->join('users', 'comments.user_id', '=', 'users.id')
                ->select('comments.*', 'tasks.title as task_name', 'users.name as user_name')
                ->findOrFail($id);

            return response()->json([
                'message' => 'Comentario encontrado',
                'data' => $registro
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No fue posible obtener el recurso',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Comentario no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $customMessages = [
                'required' => 'El campo :attribute es obligatorio.',
                'string' => 'El campo :attribute debe ser una cadena de texto.',
            ];

            $validateComment = $request->validate([
                "task_id" => "required|integer",
                "user_id" => "required|integer",
                "content" => "required|string"
            ], $customMessages);

            $comment = Comments::findOrFail($id);
            $comment->task_id = $validateComment["task_id"];
            $comment->user_id = $validateComment["user_id"];
            $comment->content = $validateComment["content"];
            $comment->save();

            return response()->json([
                "message" => "Comentario actualizado",
                "comment" => $comment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el comentario',
                'data' => $e->getMessage()
            ], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'comentario no encontrado',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete(string $id)
    {
        try {
            $status = Comments::findOrFail($id);
            $status->status = "inactivo";
            $status->save();
            return response()->json([
                'message' => 'Comentario eliminado',
                'data' => $status
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se encontro el comentario',
                'data' => $e
            ], 404);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Comentario no encontrado',
                'data' => $e->getMessage()
            ], 404);
        }
    }
}
