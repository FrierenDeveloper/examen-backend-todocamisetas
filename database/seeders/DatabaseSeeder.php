<?php

namespace Database\Seeders;


use App\Models\Cliente;
use App\Models\Camiseta;
use App\Models\Talla;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        $tallaM = Talla::create(['nombre' => 'M']);

        Cliente::create([
            'nombre_comercial' => '90minutos',
            'rut' => '76123456-K',
            'direccion' => 'Providencia, Santiago',
            'categoria' => 'Preferencial',
            'contacto_nombre' => 'Matías López',
            'contacto_correo' => 'compras@90min.cl',
            'porcentaje_oferta' => 20.00
        ]);

        $camiseta = Camiseta::create([
            'titulo' => 'Camiseta Local 2025',
            'club' => 'Selección Chilena',
            'pais' => 'Chile',
            'tipo' => 'Local',
            'color' => 'Rojo',
            'precio' => 45000,
            'precio_oferta' => 35000,
            'cantidad' => 10,
            'codigo_producto' => 'SCL2025'
        ]);

        // Asociar la talla creada
        $camiseta->tallas()->attach($tallaM->id);
    }
}
