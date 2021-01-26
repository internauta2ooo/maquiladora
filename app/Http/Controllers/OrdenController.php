<?php

namespace App\Http\Controllers;

use App\Http\Models\OrdenModel;
use App\Http\Services\OrdenServicios;
use Flugg\Responder\Responder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $objOrdenServicio = new OrdenModel();
        if ($request->nuevaMarca) {
            $idMarcaNueva = $objOrdenServicio->insertarMarca($request->nombreMarca, $request->nombreMarca);
        } else {
            $idMarcaNueva = $request->idMarca;
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
            $cantidadOrdenesId = OrdenModel::insertarCantidadOrdenes($ordenEntrega);
            foreach ($item as $key => $subItem) {
                $coordinadoId = $item["coordinado"];
                $colorId = $item["color"];
                if ($key != "coordinado" && $key != "color") {
                    OrdenModel::insertarCantidadOrdenesTallas(
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

    public function crearOrdenEntrega(Request $request)
    {
        $idMarcaNueva = "";
        $objOrdenServicio = new OrdenModel();
        $ordenEntrega = $objOrdenServicio->insertarOrdenMaquilaEntregadas(
            $request->ordenEntregaId,
            $request->numeroOrden,
            $request->modelo,
            $request->prenda,
            $request->coordinado,
            $request->fechaEntrega,
            $request->muestraOriginal,
            $request->muestraReferencia,
            Auth::user()->name
        );
        $filasTotalEntradas = $request->filasOrdenes;
        foreach ($filasTotalEntradas as $item) {
            $cantidadOrdenesId = OrdenModel::insertarCantidadOrdenesEntregadas($ordenEntrega);
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
                        "0"
                    );
                }
            }
        }
    }

    public function crearPdfOrdenTrabajo(Request $request)
    {

        $filasOrdenadas = OrdenServicios::generarOrdenPorFila($request->idOrden);
        $pdf = \PDF::loadView("marcapdf", compact('filasOrdenadas'));
        return $pdf->download("mipdf.pdf");
    }
    //Ver e imprimir ordenes de maquila
    public function crearPdfOrdenTrabajoEntregada(Request $request)
    {
        $filasOrdenadas = OrdenServicios::generarOrdenPorFilaEntregadaa($request->idOrden);
        // var_dump($filasOrdenadas);

        $pdf = \PDF::loadView("marcapdfentregada", compact('filasOrdenadas'));
        return $pdf->download("mipdfentregado.pdf");
    }

    public static function generarOrdenPorFilaExistencia($idOrden)
    {
        $orden = OrdenServicios::obtenerOrdenMaquilaPorId($idOrden);
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
            "muestra_referencia" => $muestraReferencia,
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

    public static function obtenerOrdenesEntregaMaterial($idOrden)
    {
        $orden = OrdenModel::obtenerListaEntregaMaterial($idOrden);
    }

    public static function generarOrdenPorFilaEntregada($idOrden)
    {
        $orden = OrdenController::obtenerOrdenMaquilaPorIdEntregada($idOrden);
        var_dump($orden);
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
            "muestra_referencia" => $muestraReferencia,
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
        $objOrdenes = new OrdenModel();
        $objOrdenes->guardarImagen($file, $idOrden, $tipoArchivo);
        return $data;
    }

    public function obtenerImagenes(Request $request)
    {
        $idOrden = $request->idOrden;
        $objOrdenes = new OrdenModel();
        $respuestaImagenes = $objOrdenes->obtenerImagenes($idOrden);
        return responder()->success($respuestaImagenes)->respond(200, ["header" => 2, "Content-type" => "application/json; charset=utf-8", 'Charset' => 'utf-8']);
    }

    public function eliminarImagen(Request $request)
    {
        $imagenOrdenId = $request->imagenOrdenId;
        $objOrdenes = new OrdenModel();
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

    public function obtenerOrdenesMaquila()
    {
        return view('ordenesmaquila');
    }
}
