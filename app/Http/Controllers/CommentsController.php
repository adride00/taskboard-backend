<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comments;


class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $comments = Comments::join('tasks', 'comments.task_id', '=', 'tasks.id')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select('comments.*', 'tasks.name as task_name', 'users.name as user_name')
            ->where('users.id', Auth::id())
            ->get();

        return response()->join($comments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $customMessages = [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
        ];

        $validateComment = $request->validate([
            "task_id" => "required|integer",
            "user_id" => "required|integer",
            "content" => "required|string",
            "comment_create" => "required|date"
        ], $customMessages);

        $comment = new Comments();
        $comment->task_id = $validateComment["task_id"];
        $comment->user_id = $validateComment["user_id"];
        $comment->content = $validateComment["content"];
        $comment->comment_create = $validateComment["comment_create"];
        $comment->save();

        return response()->json([
            "message" => "Comentario creado",
            "comment" => $comment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
        $customMessages = [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
        ];

        $validateComment = $request->validate([
            "task_id" => "required|integer",
            "user_id" => "required|integer",
            "content" => "required|string",
            "comment_create" => "required|date"
        ], $customMessages);

        $comment = Comments::findOrFail($id);
        $comment->task_id = $validateComment["task_id"];
        $comment->user_id = $validateComment["user_id"];
        $comment->content = $validateComment["content"];
        $comment->comment_create = $validateComment["comment_create"];
        $comment->save();

        return response()->json([
            "message" => "Comentario actualizado",
            "comment" => $comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $comment = Comments::destroy($id);
        return "Comentario borrado";
    }
}

