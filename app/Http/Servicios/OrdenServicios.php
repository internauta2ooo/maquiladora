<?php

namespace App\Http\Servicios;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\OrdenController;

class OrdenServicios
{
    public static function crearPdfOrden()
    {
        return "bye";
    }

    public static function insertarOrdenMaquila(
        $marcaId,
        $folio,
        $modelo,
        $prenda,
        $coordinado,
        $fechaEntrega,
        $muestraOriginal,
        $muestraReferencia,
        $usuario
    ) {
        $id = DB::table('orden_entrega')->insertGetId([
            "marca_id" => $marcaId, "folio_id" => $folio, "modelo_id" => $modelo,
            "prenda_id" => $prenda, "coordinado_id" => $coordinado, "fecha_entrega" => $fechaEntrega,
            "muestra_original" => $muestraOriginal,
            "muestra_referencia" => $muestraReferencia,
            "usuario_id" => $usuario
        ]);
        var_dump("orden entrega 3.0");
        var_dump($id);
        return $id;
    }

    public static function insertarCantidadOrdenes($ordenEntregaId)
    {
        $id = DB::table('cantidad_ordenes')->insertGetId(["orden_entrega_id" => $ordenEntregaId]);
        var_dump("cantidad ordenes");
        var_dump($id);
        return $id;
    }

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
                "oe.usuario_id",
            )
            ->orderByRaw("oe.fecha_creacion DESC")
            ->where("oe.orden_entrega_id", "=", $idOrden)
            ->get();
        // var_dump("run");
        // var_dump($ordenesEntrega);
        // die("s");
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
        // var_dump();
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
                "oe.usuario_id",
            )
            ->orderByRaw("oe.fecha_creacion DESC")
            ->get();
        // var_dump("run");
        // var_dump($ordenesEntrega);
        // die("s");
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
            $ordenOrdenada = OrdenController::generarOrdenPorFila($unaOrden["orden_entrega_id"]);
            $unaOrden["listaOrdenada"] = $ordenOrdenada["lista"];
            array_push($ordenesCompleta, $unaOrden);
        }
        // var_dump();
        return json_encode($ordenesCompleta);
    }

    public static function insertarCantidadOrdenesTallas($cantidadOrdenesId, $coordinadoId, $colorId, $tallaId, $cantidadOrden, $cantidadOrdenEntregas)
    {
        $id = DB::table('cantidad_ordenes_tallas')->insertGetId([
            "cantidad_ordenes_id" => $cantidadOrdenesId,
            "coordinado_id" => $coordinadoId,
            "color_id" => $colorId,
            "talla_id" => $tallaId,
            "cantidad_orden" => $cantidadOrden,
            "cantidad_orden_entregadas" => $cantidadOrdenEntregas
        ]);
        var_dump("cantidad ordenes tallas");
        var_dump($id);
        return $id;
    }

    public static function obtenerNumeroTallas($ordenTalla)
    {
        $talla = DB::table("cantidad_ordenes_tallas")->select("*")
            ->where("cantidad_ordenes_tallas_id", "=", $ordenTalla)
            ->get();
        // $arr = collect($talla);
        // var_dump($talla);
        return json_decode(json_encode($talla), true);;
    }

    public static function insertarMarca($nombre, $descripcion)
    {
        $id = DB::table('marca')->insertGetId(["nombre" => $nombre, "descripcion" => $descripcion]);
        var_dump("marca");
        var_dump($id);
        return $id;
    }

    public static function insertaCantidadOrdenes($ordenEntregaId)
    {
        $id = DB::table('cantidad_ordenes')->insertGetId(["orden_entrega_id" => $ordenEntregaId]);
        return $id;
    }

    public static function obtenerOrdenPorId($idOrden)
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
}
