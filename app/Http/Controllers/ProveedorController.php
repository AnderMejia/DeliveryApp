<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $proveedor = Proveedor::all();
            return $proveedor;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.proveedores');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $proveedor = new Proveedor();
            $proveedor->nombre = $request->nombre;
            $proveedor->contacto = $request->contacto;
            $proveedor->direccion = $request->direccion;
            if ($proveedor->save() >= 1) {
                return response()->json(['status' => 'ok', 'data' => $proveedor, 201]);
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
            $proveedor = Proveedor::findOrFail($id);
            return $proveedor;
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
            $proveedor =  Proveedor::findOrFail($id);
            $proveedor->nombre = $request->nombre;
            $proveedor->contacto = $request->contacto;
            $proveedor->direccion = $request->direccion;
            if ($proveedor->update() >= 1) {
                return response()->json(['status' => 'ok', 'data' => $proveedor, 201]);
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
            $proveedor =  Proveedor::findOrFail($id);
            if ($proveedor->delete() >= 1) {
                return response()->json(['status' => 'ok', 'data' => null, 201]);
            } else {
                return 'no se pudo eliminar';
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
}
