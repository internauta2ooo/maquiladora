<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\OrdenController;
use App\Http\Models\OrdenModel;

class OrdenServicios
{

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
        return $id;
    }

    public static function insertarOrdenMaquilaEntregadas(
        $ordenEntregaId,
        $marcaId,
        $folio,
        $modelo,
        $prenda,
        $fechaEntrega,
        $muestraOriginal,
        $muestraReferencia,
        $usuario
    ) {
        $id = DB::table('orden_entregada')->insertGetId([
            "orden_entrega_id" => $ordenEntregaId,
            "marca_id" => $marcaId, "folio_id" => $folio, "modelo_id" => $modelo,
            "prenda_id" => $prenda, "fecha_entrega" => $fechaEntrega,
            "muestra_original" => $muestraOriginal,
            "muestra_referencia" => $muestraReferencia,
            "usuario_id" => $usuario
        ]);
        return $id;
    }
    public static function guardarImagen(
        $dataUrl,
        $idOrden,
        $tipoArchivo
    ) {
        $id = DB::table('imagenes_orden')->insertGetId([
            "imagen" => $dataUrl, "orden_entrega_id" => $idOrden, "tipo_archivo" => $tipoArchivo
        ]);
        return $id;
    }

    public static function obtenerImagenes(
        $idOrden
    ) {
        $listaImagenes = DB::table('imagenes_orden')->select('*')->where("orden_entrega_id", "=", $idOrden)->get();
        foreach ($listaImagenes as $key => $imagenes) {
            $listaImagenes[$key]->imagen = base64_encode($imagenes->imagen);
        }
        return json_decode(json_encode($listaImagenes), true);
    }
    public static function eliminarImagen(
        $imagenOrdenId
    ) {
        $estatusEliminado = DB::table('imagenes_orden')->where("imagen_orden_id", "=", $imagenOrdenId)->delete();
        return $estatusEliminado;
    }

    public static function insertarCantidadOrdenes($ordenEntregaId)
    {
        $id = DB::table('cantidad_ordenes')->insertGetId(["orden_entrega_id" => $ordenEntregaId]);
        return $id;
    }

    public static function insertarCantidadOrdenesEntregadas($ordenEntregadaId, $ordenEntregaId)
    {
        $id = DB::table('cantidad_ordenes_entregadas')->insertGetId(["orden_entregada_id" => $ordenEntregadaId, "orden_entrega_id" => $ordenEntregaId]);
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

            $ordenOrdenada = OrdenController::generarOrdenPorFila($unaOrden["orden_entrega_id"]);
            $ordenOrdenadaEntregada = OrdenController::generarOrdenEntregadaPorFila($unaOrden["orden_entrega_id"]);
            $unaOrden["listaOrdenada"] = $ordenOrdenada["lista"];
            $unaOrden["listaOrdenadaEntregada"] = $ordenOrdenadaEntregada["lista"];
            array_push($ordenesCompleta, $unaOrden);
        }
        return json_encode($ordenesCompleta);
    }

    public static function insertarCantidadOrdenesTallasEntregadas($ordenEntregada, $ordenEntregaId, $coordinadoId, $colorId, $tallaId, $cantidadOrden)
    {
        $id = DB::table('cantidad_ordenes_tallas_entregadas')->insertGetId([
            "orden_entrega_id" => $ordenEntregaId,
            "orden_entregada_id" => $ordenEntregada,
            "coordinado_id" => $coordinadoId,
            "color_id" => $colorId,
            "talla_id" => $tallaId,
            "cantidad_orden_entregadas" => $cantidadOrden,

        ]);

        return $id;
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

        return $id;
    }

    public static function obtenerNumeroTallas($ordenTalla)
    {
        $talla = DB::table("cantidad_ordenes_tallas")->select("*")
            ->where("cantidad_ordenes_tallas_id", "=", $ordenTalla)
            ->get();

        return json_decode(json_encode($talla), true);
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

    public static function obtenerOrdenMaquilaPorIdEntregada($ordenId)
    {
        $objOrdenes = new OrdenServicios();
        return $objOrdenes->obtenerOrdenPorIdEntregada($ordenId);
    }

    public static function obtenerOrdenPorIdEntregada($idOrden)
    {
        //
        //Aqui sin el get y en modo debug deboprobar para ver si hay vulnerabilidad o exposicion de data
        //        
        $ordenesEntrega = DB::table("orden_entregada as oe")
            ->leftJoin("marca as ma", "ma.marca_id", "=", "oe.marca_id")
            // ->leftJoin("cantidad_ordenes as co", "oe.orden_entrega_id", "=", "co.orden_entrega_id")
            ->leftJoin("cantidad_ordenes_tallas_entregadas as coe2", "coe2.orden_entrega_id", "=", "oe.orden_entrega_id")
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
                "coe2.cantidad_ordenes_tallas_entregadas_id",
                // "coe2.cantidad_ordenes_id",
                "coe2.coordinado_id",
                "coe2.color_id",
                "coe2.talla_id",
                "coe2.cantidad_orden",
                "coe2.cantidad_orden_entregadas",
                // "coe2.cantidad_orden_restantes",
                "oe.usuario_id",
            )
            ->orderByRaw("oe.fecha_creacion DESC")
            ->where("oe.orden_entregada_id", $idOrden)
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
            $orden["cantidad_ordenes_tallas_entregadas_id"] = $item->cantidad_ordenes_tallas_entregadas_id;
            $orden["muestra_referencia"] = $item->muestra_referencia;
            $orden["fecha_creacion"] = $item->fecha_creacion;
            $orden["usuario_id"] = $item->usuario_id;
            array_push($arregloOrdenes, $orden);
        }
        $arregloOrdenes = array_unique($arregloOrdenes, SORT_REGULAR);
        $ordenes = [];
        var_dump("ssdkk");
        var_dump($ordenesEntrega);
        var_dump($arregloOrdenes);
        foreach ($arregloOrdenes as $key => $item) {
            $orden = [];
            $cantidad_total = 0;
            foreach ($ordenesEntrega as $item2) {
                $cantidad_total += $item2->cantidad_orden;
                if ($item2->cantidad_ordenes_tallas_entregadas_id == $item["cantidad_ordenes_tallas_entregadas_id"]) {
                    $ordenArmada = [
                        "cantidad_ordenes_tallas_entregadas_id" => $item2->cantidad_ordenes_tallas_entregadas_id,
                        // "cantidad_ordenes_tallas_id" => $item2->cantidad_ordenes_tallas_id,
                        "coordinado_id" => $item2->coordinado_id,
                        "talla_id" => $item2->talla_id,
                        "color_id" => $item2->color_id,
                        "cantidad_orden_entregadas" => $item2->cantidad_orden_entregadas,
                        // "cantidad_orden" => $item2->cantidad_orden,
                        // "cantidad_orden_entregadas" => $item2->cantidad_orden_entregadas,
                        // "cantidad_orden_restantes" => $item2->cantidad_orden_restantes,
                    ];
                    array_push($orden, $ordenArmada);
                }
            }
            $item["total_piezas"] = $cantidad_total;
            $item["ordenesTallas"] = $orden;
            array_push($ordenes, $item);
        }
        var_dump("ssdkk ordenes");
        var_dump($ordenes);
        return $ordenes;
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
}
