<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            return $users;
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $users = new User();
            $users->name = $request->nombre;
            $users->email = $request->email;
            $users->password = $request->pass;
            $users->direccion = $request->direccion;
            $users->telefono = $request->telefono;
            $users->documento = $request->documento;
            if ($users->save() >= 1) {
                return response()->json(['status' => 'ok', 'data' => $users, 201]);
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
            $users = User::findOrFail($id);
            return $users;
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
            $users =  User::findOrFail($id);
            $users->name = $request->nombre;
            $users->email = $request->email;
            $users->password = $request->pass;
            $users->direccion = $request->direccion;
            $users->telefono = $request->telefono;
            $users->documento = $request->documento;
            if ($users->update() >= 1) {
                return response()->json(['status' => 'ok', 'data' => $users, 201]);
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
            $users = User::findOrFail($id);
            if ($users->delete() >= 1) {
                return response()->json(['status' => 'ok', 'data' => null, 200]);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
}
