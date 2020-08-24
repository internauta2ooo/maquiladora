<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function crearOrden()
    {
        return "Se creo una orden";
    }
}
