<?php

namespace App\Http\Controllers;

use App\Models\Camiseta;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importación obligatoria para las transacciones

class CamisetaController extends Controller
{
    public function index()
    {
        try {
            // Se retorna con la relación de tallas cargada
            $camisetas = Camiseta::with('tallas')->get();
            return response()->json(['data' => $camisetas], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al listar camisetas', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titulo' => 'required|string',
                'club' => 'required|string',
                'pais' => 'required|string',
                'tipo' => 'required|string',
                'color' => 'required|string',
                'precio' => 'required|integer',
                'precio_oferta' => 'nullable|integer',
                'cantidad' => 'required|integer',
                'codigo_producto' => 'required|string|unique:camisetas',
                'tallas' => 'array'
            ]);

           // Transacción para asegurar la inserción conjunta de camiseta y tallas
            $camiseta = DB::transaction(function () use ($validated, $request) {
                $nuevaCamiseta = Camiseta::create($validated);
                if ($request->has('tallas')) {
                    $nuevaCamiseta->tallas()->sync($request->tallas);
                }
                return $nuevaCamiseta;
            });

            return response()->json(['message' => 'Camiseta creada', 'data' => $camiseta->load('tallas')], 201)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear camiseta', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }

    public function show(Request $request, $id)
    {
        try {
          //  Obtener tallas asociadas mediante JOIN
            $camiseta = Camiseta::select('camisetas.*')->where('camisetas.id', $id)->firstOrFail();

            $tallas = DB::table('tallas')
                ->join('camiseta_talla', 'tallas.id', '=', 'camiseta_talla.talla_id')
                ->where('camiseta_talla.camiseta_id', $id)
                ->select('tallas.id', 'tallas.nombre')
                ->get();


            $precio_final = $camiseta->precio; // Por defecto es el precio base

            if ($request->has('cliente_id')) {
                $cliente = Cliente::find($request->cliente_id);

                // Si el cliente existe, es 'Preferencial' y la camiseta TIENE precio_oferta
                if ($cliente && $cliente->categoria === 'Preferencial' && !is_null($camiseta->precio_oferta)) {
                    $precio_final = $camiseta->precio_oferta;
                }
            }

            // Armamos la respuesta final como un array
            $resultado = $camiseta->toArray();
            $resultado['precio_final'] = $precio_final;
            $resultado['tallas'] = $tallas;

            return response()->json(['data' => $resultado], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Camiseta no encontrada', 'details' => $e->getMessage()], 404)->header('Content-Type', 'application/json');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $camiseta = Camiseta::findOrFail($id);

            // Transacciones en la actualización
            DB::transaction(function () use ($request, $camiseta) {
                $camiseta->update($request->except('tallas'));
                if ($request->has('tallas')) {
                    $camiseta->tallas()->sync($request->tallas);
                }
            });

            return response()->json(['message' => 'Camiseta actualizada', 'data' => $camiseta->load('tallas')], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar camiseta', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }

    public function destroy($id)
    {
        try {
            $camiseta = Camiseta::findOrFail($id);
            $camiseta->delete();
            return response()->json(['message' => 'Camiseta eliminada correctamente'], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar camiseta', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }
}
