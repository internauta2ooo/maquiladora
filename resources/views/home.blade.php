@extends('layouts.app')
<script src="{{asset('js/app.js')}}"></script>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>

            </div>
            <div id="app" class="content">
                <!--La equita id debe ser app, como hemos visto en app.js-->
                <example-componentt></example-componentt>
                <ordenes-entrega></ordenes-entrega>
                <!--Añadimos nuestro componente vuejs-->
            </div>
        </div>
    </div>
</div>
</div>
@endsection