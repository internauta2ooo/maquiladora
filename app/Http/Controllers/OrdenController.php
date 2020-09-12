<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Servicios\OrdenServicios;

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

    public function crearPdfOrdenTrabajo()
    {

        OrdenServicios::crearPdfOrden();
        $pdf = app("dompdf.wrapper");
        $pdf->loadHTML('<h1>Yeah.com</h1>');
        return $pdf->download("mipdf.pdf");
    }
}
