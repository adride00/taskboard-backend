<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Tasks::join('projects', 'tasks.project_id', '=', 'projects.id')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->join('labels', 'tasks.label_id', '=', 'labels.id')
            ->select('tasks.*', 'projects.name as project_name', 'users.name as user_name', 'labels.name as label_name')
            ->get();

        return response()->json($tasks);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customMessages = [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
            'max' => 'El campo :attribute no debe exceder :max caracteres.',
        ];

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'priority' => 'required|string',
            'project_id' => 'required|integer',
            'user_id' => 'required|integer',
            'label_id' => 'integer'
        ], $customMessages);

        $task = new Tasks();
        $task->title = $validatedData['title'];
        $task->description = $validatedData['description'];
        $task->deadline = $validatedData['deadline'];
        $task->priority = $validatedData['priority'];
        $task->project_id = $validatedData['project_id'];
        $task->user_id = $validatedData['user_id'];
        $task->label_id = $validatedData['label_id'];
        $task->save();

        return response()->json([
            'message' => 'Nueva tarea creada',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $registro = Tasks::join('projects', 'tasks.project_id', '=', 'projects.id')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->join('labels', 'tasks.label_id', '=', 'labels.id')
            ->select('tasks.*', 'projects.name as project_name', 'users.name as user_name', 'labels.name as label_name')
            ->findOrFail($id);

        return response()->json($registro);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customMessages = [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
            'max' => 'El campo :attribute no debe exceder :max caracteres.',
        ];

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'priority' => 'required|string',
            'project_id' => 'required|integer',
            'user_id' => 'required|integer',
            'label_id' => 'integer'
        ], $customMessages);

        $task = Tasks::findOrFail($id);
        $task->title = $validatedData['title'];
        $task->description = $validatedData['description'];
        $task->deadline = $validatedData['deadline'];
        $task->priority = $validatedData['priority'];
        $task->project_id = $validatedData['project_id'];
        $task->user_id = $validatedData['user_id'];
        $task->label_id = $validatedData['label_id'];
        $task->save();

        return response()->json([
            'message' => 'Tarea actualizada',
            'data' => $task
        ]);
    }

    public function updateProgress(Request $request, string $id)
    {
        $customMessages = [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
            'max' => 'El campo :attribute no debe exceder :max caracteres.',
        ];

        $validatedData = $request->validate([
            'progress' => 'required|string|max:255',
        ], $customMessages);

        $progress = Tasks::findOrFail($id);
        $progress->progress = $validatedData['progress'];
        $progress->save();

        return response()->json([
            'message' => 'Tarea actualizada',
            'data' => $progress
        ]);
    }

    public function softDelete(string $id)
    {

        $status = Tasks::findOrFail($id);
        $status->status = "inactivo";
        $status->save();

        return response()->json([
            'message' => 'Tarea eliminada',
            'data' => $status
        ]);
    }

    public function searchTask(string $search)
    {
        $registro = Tasks::join('projects', 'tasks.project_id', '=', 'projects.id')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->join('labels', 'tasks.label_id', '=', 'labels.id')
            ->select('tasks.*', 'projects.name as project_name', 'users.name as user_name', 'labels.name as label_name')
            ->where('tasks.title', 'LIKE', '%' . $search . '%')
            ->orWhere('tasks.description', 'LIKE', '%' . $search . '%')
            ->get();

        if ($registro->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron tareas que coincidan con la búsqueda'
            ], 404);
        }

        return response()->json($registro);
    }
}
