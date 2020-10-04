<?php

namespace App\Http\Servicios;

use Illuminate\Support\Facades\DB;

class OrdenServicios
{

    static public function crearPdfOrden()
    {
        return "bye";
    }

    static public function insertarOrdenMaquila($marcaId, $folio, $modelo, $prenda, $coordinado)
    {
        $id = DB::table('orden_entrega')->insertGetId([
            "marca_id" => $marcaId, "folio_id" => $folio, "modelo_id" => $modelo,
            "prenda_id" => $prenda, "coordinado_id" => $coordinado
        ]);
        var_dump("orden entrega 3.0");
        var_dump($id);
        return $id;
    }

    static public function insertarCantidadOrdenes($ordenEntregaId)
    {
        $id = DB::table('cantidad_ordenes')->insertGetId(["orden_entrega_id" => $ordenEntregaId]);
        var_dump("cantidad ordenes");
        var_dump($id);
        return $id;
    }

    static public function obtenerOrdenes()
    {
        //
        //
        //Aqui sin el get y en modo debug deboprobar para ver si hay vulnerabilidad o exposicion de data
        //
        //
        var_dump("las ordenes s");
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
                "oe.fecha_creacion",
                "coe2.cantidad_ordenes_tallas_id",
                "coe2.cantidad_ordenes_id",
                "coe2.coordinado_id",
                "coe2.color_id",
                "coe2.talla_id",
                "coe2.cantidad_orden",
                "coe2.cantidad_orden_entregadas",
            )->get();

        var_dump("las ordenes");

        $arregloOrdenes = [];


        foreach ($ordenesEntrega as $key => $item) {
            $orden["orden_entrega_id"] = $item->orden_entrega_id;
            $orden["marca"] = $item->marca;
            $orden["folio_id"] = $item->folio_id;
            $orden["modelo_id"] = $item->modelo_id;
            $orden["fecha_entrega"] = $item->fecha_entrega;
            $orden["prenda_id"] = $item->prenda_id;
            $orden["muestra_original"] = $item->muestra_original;
            array_push($arregloOrdenes, $orden);
        }
        var_dump("quitando duplicados");
        $arregloOrdenes = array_unique($arregloOrdenes, SORT_REGULAR);

        $ordenes = [];
        foreach ($arregloOrdenes as $key => $item) {

            $orden = [];
            foreach ($ordenesEntrega as $item2) {
                if ($item2->orden_entrega_id == $item["orden_entrega_id"]) {
                    $ordenArmada = [
                        "cantidad_ordenes_id" => $item2->cantidad_ordenes_id,
                        "coordinado_id" => $item2->coordinado_id,
                        "talla_id" => $item2->color_id,
                        "color_id" => $item2->talla_id,
                        "cantidad_orden" => $item2->cantidad_orden,
                        "cantidad_orden_entregadas" => $item2->cantidad_orden_entregadas,
                    ];
                    array_push($orden, $ordenArmada);
                }
            }
            // var_dump("la orden");
            // var_dump($orden);
            $item["ordenesTallas"] = $orden;
            array_push($ordenes, $item);
        }
        // var_dump("la ordennn armada");
        // var_dump($orden);

        var_dump("ya final");
        var_dump($ordenes);
        var_dump("en json");
        $json = json_encode($ordenes);
        var_dump($json);


        return $ordenesEntrega;
    }

    static public function insertarCantidadOrdenesTallas($cantidadOrdenesId, $coordinadoId, $colorId, $tallaId, $cantidadOrden, $cantidadOrdenEntregas)
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

    static public function insertarMarca($nombre, $descripcion)
    {
        $id = DB::table('marca')->insertGetId(["nombre" => $nombre, "descripcion" => $descripcion]);
        var_dump("marca");
        var_dump($id);
        return $id;
    }

    static public function insertaCantidadOrdenes($ordenEntregaId)
    {
        $id = DB::table('cantidad_ordenes')->insertGetId(["orden_entrega_id" => $ordenEntregaId]);
        return $id;
    }
}
