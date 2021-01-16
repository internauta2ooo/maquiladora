<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Servicios\OrdenServicios;
use Countable;
use Flugg\Responder\Responder;
use App\Http\Models\TallaModel;

class TallasController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function actualizarTallas(Request $request)
    {
        $tallas = $request->all();
        $objTallas = new TallaModel();
        $objTallas->actualizarTallas($tallas);

        return $request->all();
    }
}
