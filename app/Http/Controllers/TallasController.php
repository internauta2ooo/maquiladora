<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\OrdenServicios;
use Countable;
use Flugg\Responder\Responder;
use App\Http\Models\TallaModel;

class TallasController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function actualizarTallas(Request $request)
    {
        $objOrdenServicio = new OrdenServicios();
        var_dump($request->all());
        // var_dump($request->all()["tallas_actualizar"][0]);
        // var_dump($request->all()["tallas_actualizar"]);
        $tallas = $request->all()["tallas_actualizar"];
        $objTallas = new TallaModel();
        $objTallas->actualizarTallas($tallas);
        $ordenEntregada = $objOrdenServicio->insertarOrdenMaquilaEntregadas(
            $request->ordenEntregaId,
            $request->marcaId,
            $request->folioId,
            $request->modelo,
            $request->prenda,
            $request->fechaEntrega,
            $request->muestraOriginal,
            $request->referencia,
            Auth::user()->name
        );
        $filasTotalEntradas = $request->all()["tallas_actualizar"];
        foreach ($filasTotalEntradas as $item) {
            // $cantidadOrdenesId = $objOrdenServicio->insertarCantidadOrdenesEntregadas($ordenEntrega, $request->ordenEntregaId);
            // foreach ($item as $key => $subItem) {
            $coordinadoId = $item["coordinado"];
            $colorId = $item["color"];
            $cantidad = $item["cantidad"];
            $tallaId = $item["talla"];
            // if ($key != "coordinado" && $key != "color") {
            $objOrdenServicio->insertarCantidadOrdenesTallasEntregadas(
                $ordenEntregada,
                $request->ordenEntregaId,
                $coordinadoId,
                $colorId,
                $tallaId,
                $cantidad

            );
            // }
            // }
        }

        return $request->all();
    }
}
