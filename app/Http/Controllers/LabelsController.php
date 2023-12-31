<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Labels;

class LabelsController extends Controller
{

    public function index()
    {
        try {
            $label = Labels::all();

            return response()->json($label);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la etiqueta',
                'data' => $e->getMessage()
            ], 500);
        }
    }

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
                'description' => 'string'
            ], $customMessages);

            $label = new Labels();
            $label->name = $validatedData['name'];
            $label->description = $validatedData['description'];
            $label->save();

            return response()->json([
                'message' => 'Nueva etiqueta creada',
                'data' => $label
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la etiqueta',
                'data' => $e->getMessage()
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $label = Labels::findOrFail($id);

            return response()->json([
                'message' => 'Etiqueta encontrada',
                'data' => $label
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se encontro el id',
                'data' => $e
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

            $label = Labels::findOrFail($id);
            $label->name = $validatedData['name'];
            $label->description = $validatedData['description'];
            $label->save();

            return response()->json([
                'message' => 'Cambio completado.',
                'data' => $label
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se encontro el id',
                'data' => $e
            ], 404);
        }
    }

    public function softDelete(string $id)
    {
        try {
            $status = Labels::findOrFail($id);
            $status->status = "inactivo";
            $status->save();
            return response()->json([
                'message' => 'Tarea eliminada',
                'data' => $status
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se encontro el id',
                'data' => $e
            ], 404);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Etiqueta no encontrada',
                'data' => $e->getMessage()
            ], 404);
        }
    }
}
