@extends('layouts.appOrdenes')
<script src="{{ asset('js/app.js') }}" defer></script>
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div id="app" class="content">
                    <!--Añadimos nuestro componente vuejs-->
                    <todas-las-ordenes-maquila></-ordenes-maquila>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
