<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Labels;

class LabelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $label = Labels::all();

        return response()->json($label);
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
        ]);
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete(string $id)
    {
        $status = Labels::findOrFail($id);
        $status->status = "inactivo";
        $status->save();

        return response()->json([
            'message' => 'Tarea eliminada',
            'data' => $status
        ]);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
