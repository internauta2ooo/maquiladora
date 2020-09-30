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
        $id = DB::table('orden_entrega')->insertGetId(["marca_id" => $marcaId, "folio_id" => $folio, "modelo_id" => $modelo, "prenda_id" => $prenda, "coordinado_id" => $coordinado]);
        var_dump("orden entrega");
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
