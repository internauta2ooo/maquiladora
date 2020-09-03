@extends('layouts.app')
<script src="{{asset('js/jquery.js')}}"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ asset('js/typeahead.js') }}"></script>
<!-- <script type="text/javascript" src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script> -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" /> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"> -->
<!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" /> -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/jqueryuitheme.css')}}" />
<link rel="stylesheet" href="{{asset('css/estilosmarca.css')}}" />
<script src="{{asset('controladores/firma.js')}}"></script>
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
<!-- <script src="{{asset('js/bootstrap.min.js')}}"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script> -->
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery.appendgrid@2/dist/AppendGrid.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/table-to-json@1.0.0/lib/jquery.tabletojson.min.js" integrity="sha256-H8xrCe0tZFi/C2CgxkmiGksqVaxhW0PFcUKZJZo1yNU=" crossorigin="anonymous"></script> -->
<!-- <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script> -->
<!-- <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.jquery.min.js"></script> -->
<!-- <script src="{{asset('controladores/marca.js')}}"></script>
<script src="{{asset('controladores/firma.js')}}"></script> -->
<!-- <script src="{{asset('js/bootstrap-tagsinput.js')}}"></script> -->
<!-- <link rel="stylesheet" href="{{asset('css/bootstrap-tagsinput.css')}}" /> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script> -->

@section('content')
<div class="container">
  <h1>Modulo Marca</h1>
  <!-- Empezamos la primera vista -->
  <div id="the-basics">
    <input  class="typeahead form-control" type="text" placeholder="Selecciona una marca" />
  </div>
  


  <div id="filaprincipal">
    <div id="the-basics" class="input-group input-group-sm mb-3 botones ">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Marca</span>
      </div>
     
        <input  class="typeahead form-control" type="text" placeholder="Selecciona una marca" />
      
      {{-- <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm"> --}}
    </div>
    <script src="{{asset('controladores/marca.js')}}"></script>
    <div class="input-group input-group-sm mb-3 botones">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Folio</span>
      </div>
      <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
    </div>
    <div class="input-group input-group-sm mb-3 botones">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Modelo</span>
      </div>
      <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
    </div>
    <div class="input-group input-group-sm mb-3 botones">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Fecha</span>
      </div>
      <input type="text" class="form-control" id="datepicker">
    </div>
  </div>

  <div id="segundafila">
    <div class="input-group input-group-sm mb-3 botones">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Fecha entrega cliente</span>
      </div>
      <input type="text" class="form-control" id="entregacliente">
    </div>
    <div class="input-group input-group-sm mb-3 botones">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Prenda</span>
      </div>
      <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
    </div>
  </div>
  <div id="tercerafila">
    <div class="input-group input-group-sm  botones">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Coordinado</span>
      </div>
      <input type="text" class="form-control" name="country" placeholder="" />
    </div>
    <div class="botonradio" style="display: flex; max-height: 45px; ">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Muestra Original</span>
        <div class="radio miradio">
          <label><input type="radio" name="muestra" checked>Si</label>
        </div>
        <div class="radio miradio">
          <label><input type="radio" name="muestra">No</label>
        </div>
      </div>
    </div>
  </div>
  <script>
    $("#datepicker").datepicker();
    $("#entregacliente").datepicker();
  </script>
  <script>
    $('input[name="country"]').amsifySuggestags();
  </script>
  <div id="contenedormatrizmarcas">
    <input type="button" class="btn btn-primary" value="obtener jsonn" onclick="obtenerJson()" />
    <input type="button" class="btn btn-primary" value="Agregar Talla" onclick="addColumn('tblSample')" />
    <input type="button" class="btn btn-primary" value="Eliminar Talla" onclick="deleteColumn('tblSample')" />
    <input type="button" class="btn btn-primary" value="Agregar Fila" onclick="addRow('tblSample')" />
    <input type="button" class="btn btn-primary" value="Eliminar Ultima Fila" onclick="deleteRow('tblSample')" />
    </p>
    <table align="center" id="tblSample" border="1">
      <thead>
        <tr>
          <th>Coordinado</th>
          <th>Color</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <select class="custom-select" name="coordinadoselect">
              <option value="none">Nothing</option>
              <option value="guava">Guava</option>
              <option value="lychee">Lychee</option>
              <option value="papaya">Papaya</option>
            </select>
          </td>
          <td><input type="text" id="yeah" class="form-control" value="" class="ssss" oninput='alert("alert me");'></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="wrapper">
    <canvas id="firma" class="signature-pad" width=400 height=200></canvas>
  </div>

  {{-- <form method="GET" action="crearorden">
    <textarea name="hola" id="insert" cols="30" rows="10"></textarea>
    <button type="submit"></button>
    <!-- <input type="button" class="btn btn-primary" value="Guardar" onclick="obtenerJson()" />-->
  </form> --}}
  <button id="save-png">Save as PNG</button>
  <button id="save-jpeg">Save as JPEG</button>
  <button id="save-svg">Save as SVG</button>
  <button id="draw">Draw</button>
  <button id="erase">Erase</button>
  <button id="clear">Clear</button>
  <script src="controladores/firma.js"></script>
  <!-- Modal Ver modelos relacionados a la marca-->
  <div class="modal fade" id="verModalModelos" tabindex="-1" test="33" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:900px" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar Modelos de Marca</h5>
          <button type="button" id="cerrarModalModelos" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!-- Tabla modelos -->
        <div class="modal-body" id="bodyTablaModelos">
          <button id="botonCrearMarca" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter" onclick="modalCrearOrdenMaquila()">
            Crea Orden de Maquila
          </button>
          <div class="container-fluid">
            <table id="tablaModelos"></table>
            <hr />
            <!-- Aqui van los botones para los modelos -->
            <div class="modal-footer" id="footerTablaModelos">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar sin guardar</button>
              <button id="actualizarModelos" type="button" class="btn btn-primary" onclick="actualizarModelos(verModalModelos)">Guardar cambios</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Crear Orden Maquila-->
  <div class="modal fade" id="crearOrdenMaquila" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:1100px" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Orden de maquila</h5>
          <button type="button" id="cerrarModalModelos" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!-- Tabla informativa -->
        <div class="modal-body" id="bodyOrdenMaquila">
          <div class="container-fluid">
            <div id="headerOrdenMaquila">
              <input type="text" name="marca" placeholder="Ingrese una marca" class="form-control header_input">
              <input type="text" name="folio" placeholder="Ingresa el folio" class="form-control header_input">
              <input type="text" name="folio" placeholder="Ingresa el folio" class="form-control header_input">
              <input type="text" class="form-control header_input" id="fechaOrdenMaquila">
            </div>
          </div>
        </div>
      </div>
      @endsection