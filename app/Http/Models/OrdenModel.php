<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers;
use App\Http\Services\OrdenServicios;

class OrdenModel
{
    public static function obtenerListaEntregaMaterial($idOrden)
    {
        $ordenesEntrega = DB::table("orden_entregada as oe")->select("*")->where("orden_entrega_id", "=", $idOrden)->get();

        return $ordenesEntrega;
    }

    public static function generarOrdenPorFilaEntregadaa($idOrden)
    {
        $orden = OrdenServicios::obtenerOrdenMaquilaPorIdEntregada($idOrden);
        var_dump("lol2222");
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
            "muestra_referencia" => $muestraReferencia
        );


        $encabezado = ["Coordinado", "Color"];
        $filasOrden = array();
        foreach ($orden[0]["ordenesTallas"] as $ordenes) {
            $ordenes["talla_id"];
            array_push($encabezado, $ordenes["talla_id"]);
            $encabezado = array_unique($encabezado);
            // array_push($filasOrden, array($ordenes["cantidad_ordenes_id"]));
        }

        var_dump("lol00x");
        var_dump($encabezado);
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

                                array_push($filaInsertar, $itemT["cantidad_orden_entregadas"]);
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
        var_dump("lista");
        var_dump($filasOrdenadas);
        var_dump($datosOrden);

        return array("datos_orden" => $datosOrden, "lista" => $filasOrdenadas);
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
