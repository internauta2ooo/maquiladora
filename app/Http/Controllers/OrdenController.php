<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\OrdenServicios;
use Countable;
use Flugg\Responder\Responder;

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
        $filasOrdenadas = OrdenController::generarOrdenPorFila($request->idOrden);
        $pdf = \PDF::loadView("marcapdf", compact('filasOrdenadas'));
        return $pdf->download("mipdf.pdf");
    }


    public static function generarOrdenPorFilaExistencia($idOrden)
    {
        $orden = OrdenController::obtenerOrdenMaquilaPorId($idOrden);
        $marca = $orden[0]["marca"];
        $folio = $orden[0]["folio_id"];
        $modelo = $orden[0]["modelo_id"];
        $fechaEntrega = $orden[0]["fecha_entrega"];
        $fechaCreacion = $orden[0]["fecha_creacion"];
        $usuario = $orden[0]["usuario_id"];
        $totalPiezas = $orden[0]["total_piezas"];
        $prenda = $orden[0]["prenda_id"];
        $muestraOriginal = $orden[0]["muestra_original"] == 1 ? "Si" : "No";
        $muestraReferencia = $orden[0]["muestra_referencia"] == 1 ? "Si" : "No";

        $datosOrden = array(
            "marca" => $marca,
            "folio" => $folio,
            "modelo" => $modelo,
            "fecha_entrega" => $fechaEntrega,
            "fecha_creacion" => $fechaCreacion,
            "usuario" => $usuario,
            "total_piezas" => $totalPiezas,
            "prenda" => $prenda,
            "muestra_original" => $muestraOriginal,
            "muestra_referencia" => $muestraReferencia
        );



        $encabezado = ["Coordinado", "Color"];
        $filasOrden = array();
        foreach ($orden[0]["ordenesTallas"] as $ordenes) {
            $ordenes["talla_id"];
            array_push($encabezado, $ordenes["talla_id"]);
            $encabezado = array_unique($encabezado);
            array_push($filasOrden, array($ordenes["cantidad_ordenes_id"]));
        }
        $filasOrden = array_unique($filasOrden, SORT_REGULAR);
        $filasOrdenadas = array();
        foreach ($filasOrden as $fila) {
            $filaInsertar = array();
            $filaInsertarCantidades = array();
            foreach ($orden[0]["ordenesTallas"] as $keyT => $itemT) {
                // var_dump($itemT);
                if ($itemT["cantidad_ordenes_id"] == $fila[0]) {
                    $i = 0;
                    foreach ($encabezado as $keyE => $itemE) {
                        $i++;
                        foreach ($itemT as $keyI => $itemI) {
                            if ($itemE === $itemI) {
                                $tempCoordinado = $itemT["coordinado_id"];
                                $tempColor = $itemT["color_id"];
                                // ["cantidad_ordenes_tallas_id"]
                                // $tempCoordinado = 12;
                                // $tempColor = 12;
                                array_push($filaInsertarCantidades, $itemT["cantidad_orden"]);
                                array_push($filaInsertar, $itemT["cantidad_ordenes_tallas_id"]);
                                // array_push($filasInsertar, $filaInsertarCantidades);
                            }
                        }
                    }
                }
            }
            // var_dump($filaInsertar);
            // var_dump($filaInsertarCantidades);

            array_unshift($filaInsertar, $tempColor);
            array_unshift($filaInsertar, $tempCoordinado);
            //Las cantidades
            // array_push($filaInsertar, $filaInsertarCantidades);
            array_push($filasOrdenadas, $filaInsertar);
        }
        array_unshift($filasOrdenadas, $encabezado);



        return array("datos_orden" => $datosOrden, "lista" => $filasOrdenadas);
    }

    public static function generarOrdenPorFila($idOrden)
    {
        $orden = OrdenController::obtenerOrdenMaquilaPorId($idOrden);
        $marca = $orden[0]["marca"];
        $folio = $orden[0]["folio_id"];
        $modelo = $orden[0]["modelo_id"];
        $fechaEntrega = $orden[0]["fecha_entrega"];
        $fechaCreacion = $orden[0]["fecha_creacion"];
        $usuario = $orden[0]["usuario_id"];
        $totalPiezas = $orden[0]["total_piezas"];
        $prenda = $orden[0]["prenda_id"];
        $muestraOriginal = $orden[0]["muestra_original"] == 1 ? "Si" : "No";
        $muestraReferencia = $orden[0]["muestra_referencia"] == 1 ? "Si" : "No";

        $datosOrden = array(
            "marca" => $marca,
            "folio" => $folio,
            "modelo" => $modelo,
            "fecha_entrega" => $fechaEntrega,
            "fecha_creacion" => $fechaCreacion,
            "usuario" => $usuario,
            "total_piezas" => $totalPiezas,
            "prenda" => $prenda,
            "muestra_original" => $muestraOriginal,
            "muestra_referencia" => $muestraReferencia
        );



        $encabezado = ["Coordinado", "Color"];
        $filasOrden = array();
        foreach ($orden[0]["ordenesTallas"] as $ordenes) {
            $ordenes["talla_id"];
            array_push($encabezado, $ordenes["talla_id"]);
            $encabezado = array_unique($encabezado);
            array_push($filasOrden, array($ordenes["cantidad_ordenes_id"]));
        }
        $filasOrden = array_unique($filasOrden, SORT_REGULAR);
        $filasOrdenadas = array();
        foreach ($filasOrden as $fila) {
            $filaInsertar = array();
            foreach ($orden[0]["ordenesTallas"] as $keyT => $itemT) {
                if ($itemT["cantidad_ordenes_id"] == $fila[0]) {
                    $i = 0;
                    foreach ($encabezado as $keyE => $itemE) {
                        $i++;
                        foreach ($itemT as $keyI => $itemI) {
                            if ($itemE === $itemI) {
                                $tempCoordinado = $itemT["coordinado_id"];
                                $tempColor = $itemT["color_id"];

                                array_push($filaInsertar, $itemT["cantidad_orden"]);
                            }
                        }
                    }
                }
            }
            array_unshift($filaInsertar, $tempColor);

            array_unshift($filaInsertar, $tempCoordinado);

            array_push($filasOrdenadas, $filaInsertar);
        }
        array_unshift($filasOrdenadas, $encabezado);



        return array("datos_orden" => $datosOrden, "lista" => $filasOrdenadas);
    }
    public function obtenerNumeroTallas(Request $request)
    {
        $objOrdenes = new OrdenServicios();
        $respuesta = $objOrdenes->obtenerNumeroTallas($request->ordenTalla);
        $hayLista = count($respuesta);
        if ($hayLista > 0) {

            return responder()->success($respuesta[0])->respond(200, ["header" => 2, "Content-type" => "application/json; charset=utf-8", 'Charset' => 'utf-8']);
        } else {
            return responder()->error("400", "No hay información")->respond(400, ["Content-type" => "application/json; charset=utf-8", 'Charset' => 'utf-8']);
        }
    }
    public function guardarImagen(Request $request)
    {
        $data = $request->all();
        $file = file_get_contents($request->imagen);
        $idOrden = $request->idOrden;
        $tipoArchivo = $request->tipoArchivo;
        // var_dump($data);
        $objOrdenes = new OrdenServicios();
        $objOrdenes->guardarImagen($file, $idOrden, $tipoArchivo);
        return $data;
    }
    public function obtenerImagenes(Request $request)
    {
        $idOrden = $request->idOrden;
        $objOrdenes = new OrdenServicios();
        $respuestaImagenes = $objOrdenes->obtenerImagenes($idOrden);
        // var_dump($respuestaImagenes);
        return responder()->success($respuestaImagenes)->respond(200, ["header" => 2, "Content-type" => "application/json; charset=utf-8", 'Charset' => 'utf-8']);
    }
    public function eliminarImagen(Request $request)
    {
        $imagenOrdenId = $request->imagenOrdenId;
        $objOrdenes = new OrdenServicios();
        $respuestaImagen = $objOrdenes->eliminarImagen($imagenOrdenId);

        return responder()->success()->respond(200, ["header" => 2, "Content-type" => "application/json; charset=utf-8", 'Charset' => 'utf-8']);
    }
    public function obtenerOrdenesMaquilaTallas()
    {
        $objOrdenes = new OrdenServicios();
        return $objOrdenes->obtenerOrdenes();
    }
    //Metodo Para obtener las ordenes que podemos entregar
    public function obtenerOrdenesMaquilaParaEntregar(Request $request)
    {
        $objOrdenes = new OrdenServicios();
        $respuesta = $objOrdenes->obtenerOrdenesParaEntregar($request->idOrden);
        $haylista = count($respuesta);
        if ($haylista > 0) {
            return responder()->success($respuesta)->respond(200, ["header" => 2, "Content-type" => "application/json; charset=utf-8", 'Charset' => 'utf-8']);
        } else {
            return responder()->error("400", "No hay información")->respond(400, ["Content-type" => "application/json; charset=utf-8", 'Charset' => 'utf-8']);
        }
    }

    public static function obtenerOrdenMaquilaPorId($ordenId)
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
