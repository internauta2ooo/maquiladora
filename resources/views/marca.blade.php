@extends('layouts.simpleapp')
<script type="text/javascript" src="{{asset('js/jquery2.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jqueryui1.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/jqueryuitheme.css')}}" />
<link rel="stylesheet" href="{{asset('css/estilosmarca.css')}}" />
<script type="text/javascript" src="{{asset('js/firmapadlibreria.js')}}"></script>
<!-- Iconso para el tag input -->
<link rel="stylesheet" href="{{asset('css/awesomeiconstags.css')}}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />
<!-- Iconso para el tag input -->
<script src="{{asset('js/bootstrap-tagsinput.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/bootstrap-tagsinput.css')}}" />
<script type="text/javascript" src="{{asset('js/axios.js')}}"></script>

@section('content')
<div class="container">
  <script type="text/javascript" src="{{asset('controladores/marca.js')}}"></script>
  <div>
    <script>
      var availableTags;
      var marcas;

      function obtenerMarcas() {
        axios.get("obtenermarcasauto").then(response => {
          marcas = response.data;
        }).catch(error => {
          console.log(error);
        });

        availableTags = marcas;
        console.log(availableTags);

        $(function() {
          $("#tags").autocomplete({
            source: availableTags,
            focus: function(event, ui) {
              $(this).val(ui.item.label);
              console.log("me ejecuto al cambiar?");
              return false;
            },
            select: function(event, ui) {
              $(this).val(ui.item.label);
              $("#idmarca").val(ui.item.value);
              return false;
            }
          });
        });
      }
    </script>

    <div id="filaprincipal">
      <div id="the-basics" class="input-group input-group-sm mb-3 botones ">
        <div class="input-group-prepend ui-widget">
          <span class="input-group-text" id="inputGroup-sizing-sm">Marca</span>
          <input class="typeahead form-control" id="tags" aria-label="Small" aria-describedby="inputGroup-sizing-sm" onchange="obtenerMarcas()">
          <input type="hidden" id="idmarca" />
        </div>
      </div>
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
          <input type="text" class="form-control" id="datepicker">
        </div>
      </div>
    </div>
    <script>
      $("#tags").keyup(function() {
        obtenerMarcas();
      });
    </script>

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
              <select id="cordinadostabla" tablaclass="custom-select" name="coordinadoselect">
                <option value="none">Nothing</option>
                <option value="guava">Guava</option>
                <option value="lychee">Lychee</option>
                <option value="papaya">Papaya</option>
              </select>
            </td>
            <td><input type="text" id="yeah" class="form-control" value="" class="ssss"></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="wrapper">
      <canvas id="firma" class="signature-pad" width=400 height=200></canvas>
    </div>
    <button id="save-png">Save as PNG</button>
    <button id="save-jpeg">Save as JPEG</button>
    <button id="save-svg">Save as SVG</button>
    <button id="draw">Draw</button>
    <button id="erase">Erase</button>
    <button id="clear">Clear</button>
    <script src="{{asset('controladores/firma.js')}}"></script>

  </div>
</div>
<style>
  .col-md-8 {
    border-style: solid;
  }
  #tags {
    width: 130px;
    height: 32px;
  }
</style>
@endsection