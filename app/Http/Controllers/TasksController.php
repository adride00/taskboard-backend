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
        try {
            $tasks = Tasks::join('projects', 'tasks.project_id', '=', 'projects.id')
                ->join('users', 'tasks.user_id', '=', 'users.id')
                ->join('labels', 'tasks.label_id', '=', 'labels.id')
                ->select('tasks.*', 'projects.name as project_name', 'users.name as user_name', 'labels.name as label_name')
                ->get();

            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Tareas no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

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

    public function show(string $id)
    {
        try {
            $registro = Tasks::join('projects', 'tasks.project_id', '=', 'projects.id')
                ->join('users', 'tasks.user_id', '=', 'users.id')
                ->join('labels', 'tasks.label_id', '=', 'labels.id')
                ->select('tasks.*', 'projects.name as project_name', 'users.name as user_name', 'labels.name as label_name')
                ->findOrFail($id);

            return response()->json([
                'message' => 'Tarea encontrada',
                'data' => $registro
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Tarea no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

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

        try {
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
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error al actualizar la tarea'
            ], 500);
        }
    }

    public function updateProgress(Request $request, string $id)
    {
        $customMessages = [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
            'max' => 'El campo :attribute no debe exceder :max caracteres.',
        ];

        $validatedData = $request->validate([
            'status' => 'string|max:255',
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
        try {
            $status = Tasks::findOrFail($id);
            $status->status = "inactivo";
            $status->save();

            return response()->json([
                'message' => 'Tarea eliminada',
                'data' => $status
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se encontró la tarea'
            ], 404);
        }
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

    public function filterByLabel(string $label)
    {
        $registro = Tasks::join('projects', 'tasks.project_id', '=', 'projects.id')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->join('labels', 'tasks.label_id', '=', 'labels.id')
            ->select('tasks.*', 'projects.name as project_name', 'users.name as user_name', 'labels.name as label_name')
            ->where('labels.name', 'LIKE', '%' . $label . '%')
            ->get();

        if ($registro->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron tareas que coincidan con la búsqueda'
            ], 404);
        }

        return response()->json($registro);
    }

    // get tasks by user
    public function filterByUser(string $id)
    {
        try {
            $registro = Tasks::join('projects', 'tasks.project_id', '=', 'projects.id')
                ->join('users', 'tasks.user_id', '=', 'users.id')
                ->join('labels', 'tasks.label_id', '=', 'labels.id')
                ->select('tasks.*', 'projects.name as project_name', 'users.name as user_name', 'labels.name as label_name')
                ->where('users.id', '=', $id)
                ->get();

            if ($registro->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron tareas que coincidan con la búsqueda'
                ], 404);
            }

            return response()->json($registro);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error con la busqueda de tareas'
            ], 404);
        }
    }
}
