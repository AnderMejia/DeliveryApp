<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productos = Producto::all();
            $response = $productos->toArray();
            $i = 0;
            foreach ($productos as $producto) {
                $response[$i]['proveedor'] = $producto->proveedor->toArray();
                $i++;
            }
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.productos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio = $request->precio;
            $producto->proveedor_id = $request->proveedor['id'];
            if ($producto->save() >= 1) {
                return response()->json(['status' => 'ok', 'data' => $producto, 201]);
            } else {
                return response()->json(['status' => 'fail', 'data' => null, 409]);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $response = $producto->toArray();
            $response[0]['proveedor'] = $producto->proveedor->toArray();
            return $response;
        } catch (\Exception $e) {
            $e->getMessage();
        }
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
        try {
            $producto =  Producto::findOrFail($id);
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio = $request->precio;
            $producto->proveedor_id = $request->proveedor['id'];
            if ($producto->update() >= 1) {
                return response()->json(['status' => 'ok', 'data' => $producto, 201]);
            } else {
                return response()->json(['status' => 'fail', 'data' => null, 409]);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $producto =  Producto::findOrFail($id);
            if ($producto->delete() >= 1) {
                return response()->json(['status' => 'ok', 'data' => null, 201]);
            } else {
                return 'no se pudo eliminar';
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
}
