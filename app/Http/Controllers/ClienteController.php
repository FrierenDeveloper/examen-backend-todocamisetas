<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Algoritmo real para validar RUT chileno
    private function validarRut($rut) {
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $dv  = substr($rut, -1);
        $numero = substr($rut, 0, strlen($rut)-1);
        $i = 2;
        $suma = 0;
        foreach(array_reverse(str_split($numero)) as $v) {
            if($i == 8) $i = 2;
            $suma += $v * $i;
            ++$i;
        }
        $dvr = 11 - ($suma % 11);
        if($dvr == 11) $dvr = 0;
        if($dvr == 10) $dvr = 'K';
        return strtoupper($dv) == strtoupper($dvr);
    }

    public function index()
    {
        try {
            $clientes = Cliente::all();
            return response()->json(['data' => $clientes], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al listar clientes', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }

    public function store(Request $request)
    {
        try {
            // Validamos el RUT antes de cualquier cosa
            if (!$this->validarRut($request->rut)) {
                return response()->json(['error' => 'El RUT ingresado no es válido.'], 422)->header('Content-Type', 'application/json');
            }

            $validated = $request->validate([
                'nombre_comercial' => 'required|string',
                'rut' => 'required|string|unique:clientes',
                'direccion' => 'required|string',
                'categoria' => 'required|in:Regular,Preferencial',
                'contacto_nombre' => 'required|string',
                'contacto_correo' => 'required|email',
                'porcentaje_oferta' => 'nullable|numeric'
            ]);

            $cliente = Cliente::create($validated);
            return response()->json(['message' => 'Cliente creado', 'data' => $cliente], 201)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear cliente', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }

    public function show($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            return response()->json(['data' => $cliente], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Cliente no encontrado', 'details' => $e->getMessage()], 404)->header('Content-Type', 'application/json');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $cliente = Cliente::findOrFail($id);

            // Si viene el RUT en la actualización, lo validamos
            if ($request->has('rut') && !$this->validarRut($request->rut)) {
                return response()->json(['error' => 'El RUT ingresado no es válido.'], 422)->header('Content-Type', 'application/json');
            }

            $cliente->update($request->all());
            return response()->json(['message' => 'Cliente actualizado', 'data' => $cliente], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar cliente', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }

    public function destroy($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();
            return response()->json(['message' => 'Cliente eliminado correctamente'], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar cliente', 'details' => $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }
}
