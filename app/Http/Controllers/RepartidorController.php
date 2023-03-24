<?php

namespace App\Http\Controllers;

use App\Models\Repartidor;
use Illuminate\Http\Request;

class RepartidorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $repartidores = Repartidor::all();
            return $repartidores;
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
        return view('admin.repartidores');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $repartidor = new Repartidor();
            $repartidor->nombre = $request->nombre;
            $repartidor->telefono = $request->telefono;
            if ($repartidor->save() >= 1) {
                return response()->json(['status' => 'ok', 'data' => $repartidor, 201]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $repartidor = Repartidor::findOrFail($id);
            return $repartidor;
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
            $repartidor = Repartidor::findOrFail($id);
            $repartidor->nombre = $request->nombre;
            $repartidor->telefono = $request->telefono;
            if ($repartidor->update() >= 1) {
                return response()->json(['status' => 'ok', 'data' => $repartidor, 201]);
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
            $repartidor = Repartidor::findOrFail($id);
            if ($repartidor->delete() >= 1) {
                return response()->json(['status' => 'ok', 'data' => null, 200]);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
}
