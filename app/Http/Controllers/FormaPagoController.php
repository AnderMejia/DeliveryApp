<?php

namespace App\Http\Controllers;

use App\Models\FormaPago;
use Illuminate\Http\Request;

class FormaPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $formaPago = FormaPago::all();
            //convirtiendo a arreglo
            $response = $formaPago->toArray();
            $i=0;
            foreach($formaPago as $forma){
                $response[$i]['user'] = $forma->user->toArray();
                $i++;
            }
            //$response[0]['user'] = $formaPago['usuario_id'];
            return $response;
            //dd($response);
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
            $formaPago = new FormaPago();
            $formaPago->num_tajeta = $request->numeroTarjeta;
            $formaPago->nombre_tajeta = $request->nombreTarjeta;
            $formaPago->codigo = $request->codigo;
            $formaPago->fecha_vencimiento = $request->fechaVencimiento;
            $formaPago->tipo_pago = $request->tipoPago;
            $formaPago->user_id = $request->usuario['id'];
            if ($formaPago->save() >= 1) {
                return response()->json(['status' => 'ok', 'data' => $formaPago, 201]);
            } else {
                return response()->json(['status' => 'fail', 'data' => null, 409]);
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
            $formaPago = FormaPago::findOrFail($id);
            $response = $formaPago->toArray();
            // $response[0]['usuario'] = $formaPago->usuario->toArray();
            $response[0]['user'] = $formaPago->user->toArray();
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
            $formaPago =  FormaPago::findOrFail($id);
            $formaPago->num_tajeta = $request->numeroTarjeta;
            $formaPago->nombre_tajeta = $request->nombreTarjeta;
            $formaPago->codigo = $request->codigo;
            $formaPago->fecha_vencimiento = $request->fechaVencimiento;
            $formaPago->tipo_pago = $request->tipoPago;
            $formaPago->usuario_id = $request->usuario['id'];
            if ($formaPago->update() >= 1) {
                return response()->json(['status' => 'ok', 'data' => $formaPago, 201]);
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
            $formaPago =  FormaPago::findOrFail($id);
            if ($formaPago->delete() >= 1) {
                return response()->json(['status' => 'ok', 'data' => null, 201]);
            } else {
                return 'no se pudo eliminar';
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
}
