<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
