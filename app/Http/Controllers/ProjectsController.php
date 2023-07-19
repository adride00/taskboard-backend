<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $projects = Projects::all();

            return response()->json($projects);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los proyectos',
                'data' => $e->getMessage()
            ], 500);
        }
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
        try {
            $customMessages = [
                'required' => 'El campo :attribute es obligatorio.',
                'string' => 'El campo :attribute debe ser una cadena de texto.',
                'max' => 'El campo :attribute no debe exceder :max caracteres.',
            ];

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
            ], $customMessages);

            $project = new Projects();
            $project->name = $validatedData['name'];
            $project->description = $validatedData['description'];
            $project->save();

            return response()->json([
                'message' => 'Nuevo proyecto creado',
                'data' => $project
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el proyecto',
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
            $project = Projects::findOrFail($id);

            if (!$project) {
                return response()->json([
                    'message' => 'Proyecto no encontrado',
                    'data' => $project
                ], 404);
            }

            return response()->json([
                'message' => 'Proyecto encontrado',
                'data' => $project
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el proyecto',
                'data' => $e->getMessage()
            ], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Proyecto no encontrado',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $customMessages = [
                'required' => 'El campo :attribute es obligatorio.',
                'string' => 'El campo :attribute debe ser una cadena de texto.',
                'max' => 'El campo :attribute no debe exceder :max caracteres.',
            ];

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'string'
            ], $customMessages);

            $project = Projects::findOrFail($id);
            $project->name = $validatedData['name'];
            $project->description = $validatedData['description'];
            $project->save();

            return response()->json([
                'message' => 'Cambio completado.',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el proyecto',
                'data' => $e->getMessage()
            ], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Proyecto no encontrado',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    public function softDelete(string $id)
    {
        try {
            $status = Projects::findOrFail($id);
            $status->status = "inactivo";
            $status->save();

            return response()->json([
                'message' => 'Proyecto eliminado',
                'data' => $status
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el proyecto',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
