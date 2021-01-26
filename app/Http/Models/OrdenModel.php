<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;

class OrdenModel
{
    public static function insertarCantidadOrdenes($ordenEntregaId)
    {
        $id = DB::table('cantidad_ordenes')->insertGetId(["orden_entrega_id" => $ordenEntregaId]);
        return $id;
    }

    public static function insertarCantidadOrdenesEntregadas($ordenEntregadaId)
    {
        $id = DB::table('cantidad_ordenes_entregadas')->insertGetId(["orden_entregada_id" => $ordenEntregadaId, "orden_entrega_id" => 0]);
        return $id;
    }

    // public static function insertarCantidadOrdenesEntregadas($ordenEntregadaId, $ordenEntregaId)
    // {
    //     $id = DB::table('cantidad_ordenes_entregadas')->insertGetId(["orden_entregada_id" => $ordenEntregadaId, "orden_entrega_id" => $ordenEntregaId]);
    //     return $id;
    // }

    public static function insertarMarca($nombre, $descripcion)
    {
        $id = DB::table('marca')->insertGetId(["nombre" => $nombre, "descripcion" => $descripcion]);

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
            "cantidad_orden_entregadas" => $cantidadOrdenEntregas,
        ]);

        return $id;
    }
    public static function insertarCantidadOrdenesTallasEntregadas($ordenEntregada, $coordinadoId, $colorId, $tallaId, $cantidadOrden)
    {
        $id = DB::table('cantidad_ordenes_tallas_entregadas')->insertGetId([
            // "orden_entrega_id" => $ordenEntregaId,
            "orden_entregada_id" => $ordenEntregada,
            "coordinado_id" => $coordinadoId,
            "color_id" => $colorId,
            "talla_id" => $tallaId,
            "cantidad_orden_entregadas" => $cantidadOrden,
            "cantidad_ordenes_entregadas_id" => $ordenEntregada,

        ]);

        return $id;
    }

    public static function guardarImagen(
        $dataUrl,
        $idOrden,
        $tipoArchivo
    ) {
        $id = DB::table('imagenes_orden')->insertGetId([
            "imagen" => $dataUrl, "orden_entrega_id" => $idOrden, "tipo_archivo" => $tipoArchivo,
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
    public static function obtenerListaEntregaMaterial($idOrden)
    {
        $ordenesEntrega = DB::table("orden_entregada as oe")->select("*")->where("orden_entrega_id", "=", $idOrden)->get();

        return $ordenesEntrega;
    }

    public static function obtenerOrdenPorIdEntregada($idOrden)
    {
        //
        //Aqui sin el get y en modo debug deboprobar para ver si hay vulnerabilidad o exposicion de data
        //
        $ordenesEntrega = DB::table("orden_entregada as oe")
            ->leftJoin("marca as ma", "ma.marca_id", "=", "oe.marca_id")
            ->leftJoin("cantidad_ordenes_entregadas as co", "oe.orden_entregada_id", "=", "co.orden_entregada_id")
            ->leftJoin("cantidad_ordenes_tallas_entregadas as coe2", "coe2.cantidad_ordenes_entregadas_id", "=", "co.cantidad_ordenes_entregadas_id")
            ->select(
                "oe.imagen",
                "oe.tipo_archivo",
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
                "coe2.orden_entregada_id",
                "coe2.coordinado_id",
                "coe2.color_id",
                "coe2.talla_id",
                "coe2.cantidad_orden",
                "coe2.cantidad_orden_entregadas",
                "co.cantidad_ordenes_entregadas_id",
                "oe.usuario_id",
            )
            ->orderByRaw("oe.fecha_creacion DESC")
            ->where("oe.orden_entregada_id", $idOrden)
            ->get();

        return $ordenesEntrega;
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
            "usuario_id" => $usuario,
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
        $usuario,
        $tipoArchivo,
        $firma
    ) {
        var_dump("tipo archvi");
        var_dump($tipoArchivo);
        $id = DB::table('orden_entregada')->insertGetId([
            "orden_entrega_id" => $ordenEntregaId,
            "marca_id" => $marcaId, "folio_id" => $folio, "modelo_id" => $modelo,
            "prenda_id" => $prenda, "fecha_entrega" => $fechaEntrega,
            "muestra_original" => $muestraOriginal,
            "muestra_referencia" => $muestraReferencia,
            "usuario_id" => $usuario,
            "tipo_archivo" => $tipoArchivo,
            "imagen" => $firma,
        ]);
        var_dump("inserto orden entregada");
        var_dump($id);
        return $id;
    }
    public static function obtenerOrdenesParaEntregar()
    {
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
    }
}
