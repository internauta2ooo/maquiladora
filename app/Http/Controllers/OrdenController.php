<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Servicios\OrdenServicios;

class OrdenController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function obtenerMarcasAutocomplete()
    {
        return DB::table('marca')->select('marca_id as value', 'nombre as label')->get();
    }
    public function crearOrdenMaquila(Request $request)
    {
        $objOrdenServicio = new OrdenServicios();
        if ($request->nuevaMarca) {
            $idMarcaNueva = $objOrdenServicio->insertarMarca($request->nombreMarca, $request->nombreMarca);
        }
        $ordenEntrega = $objOrdenServicio->insertarOrdenMaquila(
            $idMarcaNueva,
            $request->numeroOrden,
            $request->modelo,
            $request->prenda,
            $request->coordinado
        );
        $filasTotalEntradas = $request->totalEntradas;
        foreach ($filasTotalEntradas as $item) {
            $cantidadOrdenesId = $objOrdenServicio->insertarCantidadOrdenes($ordenEntrega);
            var_dump("insertando un arreglo...");
            var_dump($item);
            $itemKeys = array_keys($item);
            var_dump(array_keys($item));
            foreach ($item as $key => $subItem) {
                var_dump("insertando las tallas...");
                var_dump($subItem);
                var_dump("y la key..." . $key);
                $coordinadoId = $item["coordinado"];
                $colorId = $item["color"];
                if ($key != "coordinado" && $key != "color") {
                    $objOrdenServicio->insertarCantidadOrdenesTallas(
                        $cantidadOrdenesId,
                        $coordinadoId,
                        $colorId,
                        $key,
                        $subItem,
                        "0"
                    );
                }
            }
        }
        return $request;
    }

    public function crearPdfOrdenTrabajo()
    {
        OrdenServicios::crearPdfOrden();
        $pdf = app("dompdf.wrapper");
        $pdf->loadHTML('<h1>Yeah.com</h1>');
        return $pdf->download("mipdf.pdf");
    }

    public function obtenerOrdenesMaquila()
    {
        $objOrdenes = new OrdenServicios();
        $listaOrdenes = $objOrdenes->obtenerOrdenes();
        return "sss";
    }
}
