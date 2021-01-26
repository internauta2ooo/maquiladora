<?php

namespace App\Http\Controllers;

use App\Http\Models\OrdenModel;
use App\Http\Models\TallaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TallasController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function actualizarTallas(Request $request)
    {
        $objOrdenServicio = new OrdenModel();
        var_dump($request->all());
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
            Auth::user()->name,
            $request->tipoArchivo,
            $request->firma
        );
        // $filasTotalEntradas = $request->all()["tallas_actualizar"];
        // foreach ($filasTotalEntradas as $item) {
        //     // $cantidadOrdenesId = $objOrdenServicio->insertarCantidadOrdenesEntregadas($ordenEntrega, $request->ordenEntregaId);
        //     // foreach ($item as $key => $subItem) {
        //     $coordinadoId = $item["coordinado"];
        //     $colorId = $item["color"];
        //     $cantidad = $item["cantidad"];
        //     $tallaId = $item["talla"];
        //     // if ($key != "coordinado" && $key != "color") {
        //     $objOrdenServicio->insertarCantidadOrdenesTallasEntregadas(
        //         $ordenEntregada,
        //         $request->ordenEntregaId,
        //         $coordinadoId,
        //         $colorId,
        //         $tallaId,
        //         $cantidad

        //     );
        //     // }
        //     // }
        $filasTotalEntradas = $request->filasOrdenes;

        foreach ($filasTotalEntradas as $item) {

            var_dump("insertando cantidades");
            $cantidadOrdenesId = OrdenModel::insertarCantidadOrdenesEntregadas($ordenEntregada);
            foreach ($item as $key => $subItem) {
                $coordinadoId = $item["coordinado"];
                $colorId = $item["color"];
                if ($key != "coordinado" && $key != "color") {
                    $objOrdenServicio->insertarCantidadOrdenesTallasEntregadas(
                        $cantidadOrdenesId,
                        $coordinadoId,
                        $colorId,
                        $key,
                        $subItem,
                    );
                    var_dump("insertando fila");
                }
            }
        }
        // }

        return $request->all();
    }
}
