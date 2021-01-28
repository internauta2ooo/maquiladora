<?php

namespace App\Http\Services;

use App\Http\Controllers\OrdenController;
use App\Http\Models\OrdenModel;
use Illuminate\Support\Facades\DB;

class OrdenServicios
{

    public static function obtenerOrdenesParaEntregar($idOrden)
    {
        //
        //Aqui sin el get y en modo debug deboprobar para ver si hay vulnerabilidad o exposicion de data
        //
        $ordenesEntrega = DB::table("orden_entrega as oe")
            ->leftJoin("marca as ma", "ma.marca_id", "=", "oe.marca_id")
            ->leftJoin("cantidad_ordenes as co", "oe.orden_entrega_id", "=", "co.orden_entrega_id")
            ->leftJoin("cantidad_ordenes_tallas as coe2", "coe2.cantidad_ordenes_id", "=", "co.cantidad_ordenes_id")
            ->select(
                "oe.orden_entrega_id",
                "ma.descripcion as marca",
                "ma.marca_id as marca_id",
                "oe.folio_id",
                "oe.modelo_id",
                "oe.fecha_entrega",
                "oe.prenda_id",
                "oe.muestra_original",
                "oe.muestra_referencia",
                "oe.fecha_creacion",
                "coe2.cantidad_ordenes_tallas_id",
                "coe2.cantidad_ordenes_id",
                "coe2.coordinado_id",
                "coe2.color_id",
                "coe2.talla_id",
                "coe2.cantidad_orden",
                "coe2.cantidad_orden_entregadas",
                "coe2.cantidad_orden_restantes",
                "oe.usuario_id",
            )
            ->orderByRaw("oe.fecha_creacion DESC")
            ->where("oe.orden_entrega_id", "=", $idOrden)
            ->get();

        $arregloOrdenes = [];

        foreach ($ordenesEntrega as $key => $item) {
            $orden["orden_entrega_id"] = $item->orden_entrega_id;
            $orden["marca"] = $item->marca;
            $orden["marca_id"] = $item->marca_id;
            $orden["folio_id"] = $item->folio_id;
            $orden["modelo_id"] = $item->modelo_id;
            $orden["fecha_entrega"] = $item->fecha_entrega;
            $orden["prenda_id"] = $item->prenda_id;
            $orden["muestra_original"] = $item->muestra_original;
            $orden["muestra_referencia"] = $item->muestra_referencia;
            $orden["fecha_creacion"] = $item->fecha_creacion;
            $orden["usuario_id"] = $item->usuario_id;
            array_push($arregloOrdenes, $orden);
        }
        $arregloOrdenes = array_unique($arregloOrdenes, SORT_REGULAR);
        $ordenes = [];

        foreach ($arregloOrdenes as $key => $item) {
            $orden = [];
            $cantidad_total = 0;
            foreach ($ordenesEntrega as $item2) {
                $cantidad_total += $item2->cantidad_orden;
                if ($item2->orden_entrega_id == $item["orden_entrega_id"]) {
                    $ordenArmada = [
                        "cantidad_ordenes_id" => $item2->cantidad_ordenes_id,
                        "cantidad_ordenes_tallas_id" => $item2->cantidad_ordenes_tallas_id,
                        "coordinado_id" => $item2->coordinado_id,
                        "talla_id" => $item2->talla_id,
                        "marca_id" => $item2->marca_id,
                        "color_id" => $item2->color_id,
                        "cantidad_orden" => $item2->cantidad_orden,
                        "cantidad_orden_entregadas" => $item2->cantidad_orden_entregadas,
                        "cantidad_orden_restantes" => $item2->cantidad_orden_restantes,

                    ];
                    array_push($orden, $ordenArmada);
                }
            }
            $item["total_piezas"] = $cantidad_total;
            $item["ordenesTallas"] = $orden;
            array_push($ordenes, $item);
        }
        $ordenesCompleta = array();
        foreach ($ordenes as $unaOrden) {
            $ordenOrdenada = OrdenController::generarOrdenPorFilaExistencia($unaOrden["orden_entrega_id"]);
            $unaOrden["listaOrdenada"] = $ordenOrdenada["lista"];
            array_push($ordenesCompleta, $unaOrden);
        }
        return $ordenesCompleta;
    }

    public static function obtenerOrdenes()
    {
        //
        //
        //Aqui sin el get y en modo debug deboprobar para ver si hay vulnerabilidad o exposicion de data
        //
        //
        $ordenesEntrega = DB::table("orden_entrega as oe")
            ->leftJoin("marca as ma", "ma.marca_id", "=", "oe.marca_id")
            ->leftJoin("cantidad_ordenes as co", "oe.orden_entrega_id", "=", "co.orden_entrega_id")
            ->leftJoin("cantidad_ordenes_tallas as coe2", "coe2.cantidad_ordenes_id", "=", "co.cantidad_ordenes_id")
            ->select(
                "oe.orden_entrega_id",
                "ma.marca_id as marca_id",
                "ma.descripcion as marca",
                "oe.folio_id",
                "oe.modelo_id",
                "oe.fecha_entrega",
                "oe.prenda_id",
                "oe.muestra_original",
                "oe.muestra_referencia",
                "oe.fecha_creacion",
                "coe2.cantidad_ordenes_tallas_id",
                "coe2.cantidad_ordenes_id",
                "coe2.coordinado_id",
                "coe2.color_id",
                "coe2.talla_id",
                "coe2.cantidad_orden",
                "coe2.cantidad_orden_entregadas",
                "coe2.cantidad_orden_restantes",
                "oe.usuario_id",
            )
            ->orderByRaw("oe.fecha_creacion DESC")
            ->get();

        $arregloOrdenes = [];

        foreach ($ordenesEntrega as $key => $item) {
            $orden["marca_id"] = $item->marca_id;
            $orden["orden_entrega_id"] = $item->orden_entrega_id;
            $orden["marca"] = $item->marca;
            $orden["folio_id"] = $item->folio_id;
            $orden["modelo_id"] = $item->modelo_id;
            $orden["fecha_entrega"] = $item->fecha_entrega;
            $orden["prenda_id"] = $item->prenda_id;
            $orden["muestra_original"] = $item->muestra_original;
            $orden["muestra_referencia"] = $item->muestra_referencia;
            $orden["fecha_creacion"] = $item->fecha_creacion;
            $orden["usuario_id"] = $item->usuario_id;
            array_push($arregloOrdenes, $orden);
        }
        $arregloOrdenes = array_unique($arregloOrdenes, SORT_REGULAR);
        $ordenes = [];

        foreach ($arregloOrdenes as $key => $item) {
            $orden = [];
            $cantidad_total = 0;
            foreach ($ordenesEntrega as $item2) {
                if ($item2->orden_entrega_id == $item["orden_entrega_id"]) {
                    $cantidad_total += $item2->cantidad_orden;
                    $ordenArmada = [
                        "cantidad_ordenes_id" => $item2->cantidad_ordenes_id,
                        "cantidad_ordenes_tallas_id" => $item2->cantidad_ordenes_tallas_id,
                        "coordinado_id" => $item2->coordinado_id,
                        "talla_id" => $item2->talla_id,
                        "color_id" => $item2->color_id,
                        "marca_id" => $item2->marca_id,
                        "cantidad_orden" => $item2->cantidad_orden,
                        "cantidad_orden_entregadas" => $item2->cantidad_orden_entregadas,
                        "cantidad_orden_restantes" => $item2->cantidad_orden_restantes,
                    ];
                    array_push($orden, $ordenArmada);
                }
            }
            $item["total_piezas"] = $cantidad_total;
            $item["ordenesTallas"] = $orden;
            array_push($ordenes, $item);
        }
        $ordenesCompleta = array();
        foreach ($ordenes as $unaOrden) {
            // var_dump($unaOrden["orden_entrega_id"]);
            $ordenesEntregadas = OrdenModel::obtenerListaEntregaMaterial($unaOrden["orden_entrega_id"]);

            $unaOrden["ordenesEntregadas"] = $ordenesEntregadas;

            $ordenOrdenada = OrdenServicios::generarOrdenPorFila($unaOrden["orden_entrega_id"]);
            $ordenOrdenadaEntregada = OrdenServicios::generarOrdenEntregadaPorFila($unaOrden["orden_entrega_id"]);
            $unaOrden["listaOrdenada"] = $ordenOrdenada["lista"];
            $unaOrden["listaOrdenadaEntregada"] = $ordenOrdenadaEntregada["lista"];
            array_push($ordenesCompleta, $unaOrden);
        }
        return json_encode($ordenesCompleta);
    }

    public static function obtenerOrdenMaquilaPorId($ordenId)
    {
        $objOrdenes = new OrdenServicios();
        return $objOrdenes->obtenerOrdenPorId($ordenId);
    }
    public static function generarOrdenPorFila($idOrden)
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
        // var_dump("encabezado");
        // var_dump($encabezado);

        // var_dump($filasOrden);
        // var_dump($orden[0]["ordenesTallas"]);
        foreach ($filasOrden as $fila) {
            $filaInsertar = array();
            foreach ($orden[0]["ordenesTallas"] as $keyT => $itemT) {

                if ($itemT["cantidad_ordenes_id"] == $fila[0]) {
                    foreach ($encabezado as $keyE => $itemE) {
                        foreach ($itemT as $keyI => $itemI) {

                            if ($keyI != "color_id") {
                                if ($itemE === $itemI) {
                                    $tempCoordinado = $itemT["coordinado_id"];
                                    $tempColor = $itemT["color_id"];

                                    array_push($filaInsertar, $itemT["cantidad_orden"]);
                                }
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
        // var_dump("las filas ordenadas testenado");
        // var_dump($filasOrdenadas);
        return array("datos_orden" => $datosOrden, "lista" => $filasOrdenadas);
    }
    public static function generarOrdenEntregadaPorFila($idOrden)
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
            foreach ($orden[0]["ordenesTallas"] as $keyT => $itemT) {
                if ($itemT["cantidad_ordenes_id"] == $fila[0]) {
                    $i = 0;
                    foreach ($encabezado as $keyE => $itemE) {
                        $i++;
                        foreach ($itemT as $keyI => $itemI) {
                            if ($keyI != "color_id") {
                                if ($itemE === $itemI) {
                                    $tempCoordinado = $itemT["coordinado_id"];
                                    $tempColor = $itemT["color_id"];
                                    array_push($filaInsertar, $itemT["cantidad_orden_restantes"]);
                                }
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

    public static function obtenerNumeroTallas($ordenTalla)
    {
        $talla = DB::table("cantidad_ordenes_tallas")->select("*")
            ->where("cantidad_ordenes_tallas_id", "=", $ordenTalla)
            ->get();

        return json_decode(json_encode($talla), true);
    }

    public static function insertaCantidadOrdenes($ordenEntregaId)
    {
        $id = DB::table('cantidad_ordenes')->insertGetId(["orden_entrega_id" => $ordenEntregaId]);
        return $id;
    }

    public static function obtenerOrdenPorId($idOrden)
    {
        //
        //Aqui sin el get y en modo debug deboprobar para ver si hay vulnerabilidad o exposicion de data
        //
        $ordenesEntrega = DB::table("orden_entrega as oe")
            ->leftJoin("marca as ma", "ma.marca_id", "=", "oe.marca_id")
            ->leftJoin("cantidad_ordenes as co", "oe.orden_entrega_id", "=", "co.orden_entrega_id")
            ->leftJoin("cantidad_ordenes_tallas as coe2", "coe2.cantidad_ordenes_id", "=", "co.cantidad_ordenes_id")
            ->select(
                "oe.orden_entrega_id",
                "ma.descripcion as marca",
                "oe.folio_id",
                "oe.modelo_id",
                "oe.fecha_entrega",
                "oe.prenda_id",
                "oe.muestra_original",
                "oe.muestra_referencia",
                "oe.fecha_creacion",
                "coe2.cantidad_ordenes_tallas_id",
                "coe2.cantidad_ordenes_id",
                "coe2.coordinado_id",
                "coe2.color_id",
                "coe2.talla_id",
                "coe2.cantidad_orden",
                "coe2.cantidad_orden_entregadas",
                "coe2.cantidad_orden_restantes",
                "oe.usuario_id",
            )
            ->orderByRaw("oe.fecha_creacion DESC")
            ->where("oe.orden_entrega_id", $idOrden)
            ->get();
        $arregloOrdenes = [];

        foreach ($ordenesEntrega as $key => $item) {
            $orden["orden_entrega_id"] = $item->orden_entrega_id;
            $orden["marca"] = $item->marca;
            $orden["folio_id"] = $item->folio_id;
            $orden["modelo_id"] = $item->modelo_id;
            $orden["fecha_entrega"] = $item->fecha_entrega;
            $orden["prenda_id"] = $item->prenda_id;
            $orden["muestra_original"] = $item->muestra_original;
            $orden["muestra_referencia"] = $item->muestra_referencia;
            $orden["fecha_creacion"] = $item->fecha_creacion;
            $orden["usuario_id"] = $item->usuario_id;
            array_push($arregloOrdenes, $orden);
        }
        $arregloOrdenes = array_unique($arregloOrdenes, SORT_REGULAR);
        $ordenes = [];

        foreach ($arregloOrdenes as $key => $item) {
            $orden = [];
            $cantidad_total = 0;
            foreach ($ordenesEntrega as $item2) {
                $cantidad_total += $item2->cantidad_orden;
                if ($item2->orden_entrega_id == $item["orden_entrega_id"]) {
                    $ordenArmada = [
                        "cantidad_ordenes_id" => $item2->cantidad_ordenes_id,
                        "cantidad_ordenes_tallas_id" => $item2->cantidad_ordenes_tallas_id,
                        "coordinado_id" => $item2->coordinado_id,
                        "talla_id" => $item2->talla_id,
                        "color_id" => $item2->color_id,
                        "cantidad_orden" => $item2->cantidad_orden,
                        "cantidad_orden_entregadas" => $item2->cantidad_orden_entregadas,
                        "cantidad_orden_restantes" => $item2->cantidad_orden_restantes,
                    ];
                    array_push($orden, $ordenArmada);
                }
            }
            $item["total_piezas"] = $cantidad_total;
            $item["ordenesTallas"] = $orden;
            array_push($ordenes, $item);
        }
        return $ordenes;
    }
    public static function generarOrdenPorFilaEntregadaa($idOrden)
    {
        $orden = OrdenModel::obtenerOrdenPorIdEntregada($idOrden);

        $marca = $orden[0]->marca;
        $imagen = $orden[0]->imagen;
        $tipo_archivo = $orden[0]->tipo_archivo;
        $folio = $orden[0]->folio_id;
        $modelo = $orden[0]->modelo_id;
        $orden_entregada_id = $orden[0]->orden_entregada_id;
        $fechaCreacion = $orden[0]->fecha_creacion;
        $usuario = $orden[0]->usuario_id;
        $cantidad_ordenes_tallas_entregadas_id = $orden[0]->cantidad_ordenes_tallas_entregadas_id;
        $cantidad_ordenes_entregadas_id = $orden[0]->cantidad_ordenes_entregadas_id;
        // $totalPiezas = $orden[0]["total_piezas"];
        $prenda = $orden[0]->prenda_id;
        $muestraOriginal = $orden[0]->muestra_original == 1 ? "Si" : "No";
        $muestraReferencia = $orden[0]->muestra_referencia == 1 ? "Si" : "No";

        $datosOrden = array(
            "marca" => $marca,
            "folio" => $folio,
            "modelo" => $modelo,
            "orden_entregada_id" => $orden_entregada_id,
            "fecha_creacion" => $fechaCreacion,
            "tipo_archivo" => $tipo_archivo,
            "imagen" => $imagen,
            "usuario" => $usuario,
            // "total_piezas" => $totalPiezas,
            "prenda" => $prenda,
            "muestra_original" => $muestraOriginal,
            "muestra_referencia" => $muestraReferencia,
            // "listaOrdenada" => []
        );

        $encabezado = ["Coordinado", "Color"];
        $filasOrden = array();
        foreach ($orden as $ordenes) {
            $ordenes->talla_id;
            array_push($encabezado, $ordenes->talla_id);
            $encabezado = array_unique($encabezado);
            array_push($filasOrden, array($ordenes->cantidad_ordenes_entregadas_id));
        }

        $filasOrden = array_unique($filasOrden, SORT_REGULAR);
        $filasOrdenadas = array();

        foreach ($filasOrden as $fila) {
            $filaInsertar = array();
            foreach ($orden as $keyT => $itemT) {
                if ($itemT->cantidad_ordenes_entregadas_id == $fila[0]) {
                    $i = 0;
                    foreach ($encabezado as $keyE => $itemE) {
                        $i++;
                        foreach ($itemT as $keyI => $itemI) {
                            if ($itemE === $itemI) {
                                $tempCoordinado = $itemT->coordinado_id;
                                $tempColor = $itemT->color_id;

                                array_push($filaInsertar, $itemT->cantidad_orden_entregadas);
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
        // var_dump($datosOrden);
        return array("datos_orden" => $datosOrden, "lista" => $filasOrdenadas);
    }
}
