<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
            $request->coordinado,
            $request->fechaEntrega,
            $request->muestraOriginal,
            $request->muestraReferencia,
            Auth::user()->name
        );
        $filasTotalEntradas = $request->totalEntradas;
        foreach ($filasTotalEntradas as $item) {
            $cantidadOrdenesId = $objOrdenServicio->insertarCantidadOrdenes($ordenEntrega);
            foreach ($item as $key => $subItem) {
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

    public function crearPdfOrdenTrabajo(Request $request)
    {
        $orden = OrdenController::obtenerOrdenMaquilaPorId($request->idOrden);
        var_dump("la orden");
        var_dump($orden[0]["ordenesTallas"]);


        $encabezado = ["Coordinado", "Color"];
        foreach ($orden[0]["ordenesTallas"] as $ordenes) {
            $ordenes["talla_id"];
            array_push($encabezado, $ordenes["talla_id"]);
            $encabezado = array_unique($encabezado);
        }
        var_dump("El encabezado--------------------------");
        var_dump($encabezado);
        var_dump("************************************************");

        foreach ($orden[0]["ordenesTallas"] as $keyT => $itemT) {


            var_dump($keyT);
            var_dump("el itemmmmm -------------------");
            var_dump($itemT);


            foreach ($encabezado as $key => $item) {
                foreach ($itemT as $llaves => $valores) {
                    var_dump("macheando con el encabezado -------------------");
                    var_dump($key);
                    var_dump($item);
                    var_dump($llaves);
                    var_dump($valores);

                    if ($key == 0 && $llaves == "coordinado_id") {
                        var_dump("el coordinado");
                        var_dump($valores);
                        die("s");
                    }
                }
            }
        }





        die("sd");
        OrdenServicios::crearPdfOrden();
        $pdf = app("dompdf.wrapper");
        $pdf->loadHTML('<h1>Yeah.com</h1>');
        return $pdf->download("mipdf.pdf");
    }

    public function obtenerOrdenesMaquilaTallas()
    {
        $objOrdenes = new OrdenServicios();
        return $objOrdenes->obtenerOrdenes();
    }

    static public function obtenerOrdenMaquilaPorId($ordenId)
    {
        $objOrdenes = new OrdenServicios();
        return $objOrdenes->obtenerOrdenPorId($ordenId);
    }

    public function obtenerOrdenesMaquila()
    {
        return view('ordenesmaquila');
    }

    public function crearOrdenEntrega()
    {
        return view('ordenesmaquila');
    }
}
