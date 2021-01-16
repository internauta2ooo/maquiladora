<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers;

class TallaModel
{

    public static function actualizarTallas($tallas)
    {
        DB::beginTransaction();
        foreach ($tallas as $key => $talla) {
            // var_dump($talla);
            $tallaParaActualizar = DB::table("cantidad_ordenes_tallas")->select("*")->where("cantidad_ordenes_tallas_id", "=", $talla["tallaActualizar"])->get();
            // var_dump($tallaParaActualizar[0]->cantidad_orden_entregadas);
            $sumaEntregadas = ($tallaParaActualizar[0]->cantidad_orden_entregadas + $talla["cantidad"]);
            if ($sumaEntregadas > $tallaParaActualizar[0]->cantidad_orden) {
                var_dump("no puedo roolback a todo");
                var_dump($talla);
                var_dump("La cantidad que envio" . $talla["cantidad"]);
                var_dump("La cantidad sumada" . $sumaEntregadas);
                var_dump("La cantidad entregadas" . $tallaParaActualizar[0]->cantidad_orden_entregadas);
                DB::rollBack();
                return false;
            }
            var_dump("pero hago los update?");
            var_dump($talla);
            $update = DB::table("cantidad_ordenes_tallas")->where("cantidad_ordenes_tallas_id", $talla["tallaActualizar"])->update(["cantidad_orden_entregadas" => $sumaEntregadas]);
            $restantes = $tallaParaActualizar[0]->cantidad_orden - $sumaEntregadas;
            $update = DB::table("cantidad_ordenes_tallas")->where("cantidad_ordenes_tallas_id", $talla["tallaActualizar"])->update(["cantidad_orden_restantes" => $restantes]);
        }
        DB::commit();
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
