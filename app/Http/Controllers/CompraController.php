<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $compras = Compra::all();
            $response = $compras->toArray();
            $i = 0;
            foreach ($compras as $com) {
                $response[$i]['user'] = $com->user->toArray();
                $detalle = $com->detalle_compras->toArray();
                $f = 0;
                foreach ($com->detalle_compras as $d) {
                    $detalle[$f]['producto'] = $d->producto->toArray();
                }
                $response[$i]['detalleCompras'] = $detalle;
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $errores = 0;
            //crea la instancia alquiler
            DB::beginTransaction();
            $compra = new Compra();
            $compra->user_id = $request->usuario['id'];
            $compra->pago_id = $request->pago['id'];
            $compra->descripcion = $request->descripcion;
            $compra->repartidor_id = $request->repartidor['id'];
            $compra->total = $request->total;
            if ($compra->save() <= 0) {
                $errores++;
            }
            $detalle = $request->detalleCompra;
            foreach ($detalle as $key => $det) {
                //creado un objeto
                $detalleCompra = new DetalleCompra();
                $detalleCompra->producto_id = $det['producto']['id'];
                $detalleCompra->compra_id = $compra->id;
                if ($detalleCompra->save() <= 0) {
                    $errores++;
                }
            }
            if ($errores == 0) {
                DB::commit();
                return response()->json(['status' => 'ok', 'data' => $compra], 201);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'fail', 'data' => null], 409);
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
        try{
            $compras = Compra::findOrFail($id); 
            $response = $compras->toArray(); 
            $i=0;
                $response[0]['user'] = $compras->user->toArray(); 
                $detalle = $compras->detalle_compras->toArray(); 
                $f=0; 
                foreach ($compras->detalle_compras as $d){ 
                    $detalle[$f]['producto'] = $d->producto->toArray(); 
                    $f++;
                 }
                 $response[$i]['detalleCompras'] = $detalle;
                 $i++;
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
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
            $errores = 0;
            //crea la instancia alquiler
            DB::beginTransaction();
            $compra = Compra::findOrFail($id);
            $compra->user_id = $request->usuario['id'];
            $compra->pago_id = $request->pago['id'];
            $compra->descripcion = $request->descripcion;
            $compra->repartidor_id = $request->repartidor['id'];
            $compra->total = $request->total;
            if ($compra->update() <= 0) {
                $errores++;
            }
            $detalle = $request->detalleCompra;
            foreach ($detalle as $key => $det) {
                //creado un objeto
                $detalleCompra = DetalleCompra::findOrFail($det['id']);
                $detalleCompra->producto_id = $det['producto']['id'];
                if ($detalleCompra->update() <= 0) {
                    $errores++;
                }
            }
            if ($errores == 0) {
                DB::commit();
                return response()->json(['status' => 'ok', 'data' => $compra], 201);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'fail', 'data' => null], 409);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
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
