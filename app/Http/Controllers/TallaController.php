<?php

namespace App\Http\Controllers;

use App\Models\Talla;
use Illuminate\Http\Request;

class TallaController extends Controller
{
    public function index()
    {
        try {
            return response()->json(['data' => Talla::all()], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al listar tallas', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|unique:tallas'
            ]);
            $talla = Talla::create($validated);
            return response()->json(['message' => 'Talla creada', 'data' => $talla], 201)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la talla', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }

    public function show($id)
    {
        try {
            return response()->json(['data' => Talla::findOrFail($id)], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Talla no encontrada', 'details' => $e->getMessage()], 404)->header('Content-Type', 'application/json');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $talla = Talla::findOrFail($id);
            $talla->update($request->all());
            return response()->json(['message' => 'Talla actualizada', 'data' => $talla], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la talla', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }

    public function destroy($id)
    {
        try {
            $talla = Talla::findOrFail($id);
            $talla->delete();
            return response()->json(['message' => 'Talla eliminada'], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la talla', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }
}
