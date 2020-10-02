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
