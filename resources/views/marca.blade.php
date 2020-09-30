@extends('layouts.simpleapp')
<script type="text/javascript" src="{{asset('js/jquery2.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jqueryui1.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/jqueryuitheme.css')}}" />
<link rel="stylesheet" href="{{asset('css/estilosmarca.css')}}" />
<script type="text/javascript" src="{{asset('js/firmapadlibreria.js')}}"></script>
<!-- Iconos para el tag input -->
<link rel="stylesheet" href="{{asset('css/awesomeiconstags.css')}}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />
<!-- Iconso para el tag input -->
<script src="{{asset('js/bootstrap-tagsinput.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/bootstrap-tagsinput.css')}}" />
<script type="text/javascript" src="{{asset('js/axios.js')}}"></script>
@section('content')
<div class="container">
  <div id="filaprincipal" class="filas">
    <div id="the-basics" class="input-group input-group-sm mb-3 botones ">
      <div class="input-group-prepend ui-widget">
        <span class="input-group-text" id="inputGroup-sizing-sm">Marca</span>
        <input class="typeahead form-control" id="tags" aria-label="Small" aria-describedby="inputGroup-sizing-sm" onchange="obtenerMarcas()">
        <input type="hidden" id="idmarca" />
      </div>
    </div>
    <div class="input-group input-group-sm mb-3 botones">
      <span class="input-group-text" id="inputGroup-sizing-sm">Numero de orden</span>
      <input type="text" id="numeroorden" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
    </div>
  </div>
  <div id="segundafila" class="filas">
    <div class="input-group input-group-sm mb-3 botones">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Modelo</span>
      </div>
      <input type="text" id="modelo" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
    </div>
    <div class="input-group input-group-sm mb-3 botones">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Fecha entrega cliente</span>
      </div>
      <input type="text" id="fechaentrega" class="form-control">
    </div>
  </div>
  <div id="tercerafila" class="filas">
    <div class="input-group input-group-sm mb-3 botones">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Prenda</span>
      </div>
      <input type="text" id="prenda" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
    </div>
    <div class="input-group input-group-sm  botones">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Coordinado</span>
      </div>
      <input type="text" class="form-control" name="coordinados" placeholder="" />
    </div>
  </div>
  <div id="cuartafila" class="filas">
    <div class="botonradio" style="display: flex; max-height: 45px; ">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm">Muestra Referencia</span>
        <div class="radio miradio">
          <label><input type="radio" value=true name="muestrareferencia" checked>Si</label>
        </div>
        <div class="radio miradio">
          <label><input type="radio" value=false name="muestrareferencia">No</label>
        </div>
      </div>
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
  <div id="contenedormatrizmarcas">
    <input type="button" class="btn btn-primary botonprincipal" value="Agregar Talla" onclick="addColumn('tblSample')" />
    <input type="button" class="btn btn-primary botonprincipal" value="Eliminar Talla" onclick="deleteColumn('tblSample')" />
    <input type="button" class="btn btn-primary botonprincipal" value="Agregar Fila" onclick="addRow('tblSample')" />
    <input type="button" class="btn btn-primary botonprincipal" value="Eliminar Ultima Fila" onclick="deleteRow('tblSample')" />
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
            <select id="coordinadostabla" tablaclass="custom-select" name="coordinadoselect">
              <option value="none">Crea los coordinados</option>
            </select>
          </td>
          <td><input type="text" id="yeah" class="form-control" value="" name="entradastallas"></td>
        </tr>
      </tbody>
    </table>
  </div>
  <button type="button" class="btn btn-primary botonprincipal" onclick="crearOrdenMaquila()">Guardar orden de maquila</button>
  <!-- <div class="wrapper">
    <canvas id="firma" class="signature-pad" width=400 height=200></canvas>
  </div>
  <button id="save-png">Save as PNG</button>
  <button id="save-jpeg">Save as JPEG</button>
  <button id="save-svg">Save as SVG</button>
  <button id="draw">Draw</button>
  <button id="erase">Erase</button>
  <button id="clear">Clear</button>
  <script src="{{asset('controladores/firma.js')}}"></script> -->

</div>
<!-- </div> -->
<script type="text/javascript" src="{{asset('controladores/marca.js')}}"></script>
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

  function crearOrdenMaquila() {
    validarFormulario();
    var tablaPartidas = document.getElementById("tblSample");
    var totalEntradas = [];
    var ordenEntrada = {
      coordinado: null,
      color: null
    };

    totalColumnas = tablaPartidas.rows[0].cells.length;

    for (var i = 0, row; row = tablaPartidas.rows[i]; i++) {
      for (var j = 0, col; col = row.cells[j]; j++) {

        if (i > 0 && j == 0) {
          let elcoordinado = col.children[0].selectedOptions[0].text;
          ordenEntrada.coordinado = elcoordinado;
        }
        if (i > 0 && j == 1) {
          let elcolor = col.firstElementChild.value;
          ordenEntrada.color = elcolor;
        }
        if (i > 0 && j > 1) {
          let latalla = col.firstElementChild.value;
          ordenEntrada[tablaPartidas.rows[0].cells[j].firstElementChild.value] = latalla;

        }
        if (i > 0 && j == totalColumnas - 1) {
          // console.log("Solo deberia ir este" + (totalColumnas - 1));
          let texto = JSON.stringify(ordenEntrada);
          let reConvertido = JSON.parse(texto);
          totalEntradas.push(reConvertido);
        }

      }
    }

    console.log("total entradas");
    console.log(totalEntradas);

    console.log("logssss inputs");
    let coordinados = $('input[name="coordinados"]').amsifySuggestags();

    console.log(coordinados);
    // var o = document.getElementById("coordinadostabla");
    // console.log(o);
    // for (let i = 0; i < o.options.length; i++) {
    //   console.log(o.options[i].value);
    //   tagsinput.push(o.options[i].value);
    // }
    // var buscandoMarca = marcas.find(item => item.label === marca);

    // if (typeof buscandoMarca !== 'undefined') {
    //   // alert("Si me encuentro");
    //   console.log(buscandoMarca);
    // } else {
    //   // alert("No encuentro esta marca");
    //   console.log(buscandoMarca);
    // }
    let nombreMarca = document.getElementById("tags").value;
    let nuevaMarca = true;
    idMarca = "";
    if (nombreMarca == null || nombreMarca == "") {
      alert("Favor de llenar la marca...");
    } else {
      console.log("logffff");
      console.log(nombreMarca);
      console.log(availableTags);
      availableTags.filter(function(item) {
        console.log("el item");
        console.log(item)
        if (item.label === nombreMarca) {
          nuevaMarca = false;
          console.log("soy true?");
          idMarca = document.getElementById("idmarca").value;
        }
      });
    }
    console.log("la log ++++");
    console.log(nuevaMarca);
    console.log("para la data");
    let numeroOrden = document.getElementById("numeroorden").value;
    let modelo = document.getElementById("modelo").value;
    let fechaEntrega = document.getElementById("fechaentrega").value;
    let prenda = document.getElementById("prenda").value;
    let muestraOriginal = $('input[name=muestraoriginal]:checked').val();
    let muestraReferencia = $('input[name=muestrareferencia]:checked').val();
    let ordenMaquila = {
      nombreMarca: nombreMarca,
      nuevaMarca: nuevaMarca,
      idMarca: idMarca,
      muestraOriginal: muestraOriginal,
      muestraReferencia: muestraReferencia,
      modelo: modelo,
      prenda: prenda,
      numeroOrden: numeroOrden,
      fechaEntrega: fechaEntrega,
      totalEntradas: totalEntradas
    };
    axios.post("crearordenmaquila", ordenMaquila).then(response => {
      console.log("la response de fuardas");
      console.log(response);
    }).catch(error => {
      console.log(error);
    });
    // let marca = document.getElementById("tags").value;
    console.log(ordenMaquila);

    // let idMarca = document.getElementById("idmarca").value;
    // let marca = document.getElementById("tags").value;
    // let numeroOrden = document.getElementById("numeroorden").value;
    // let modelo = document.getElementById("modelo").value;
    // let fechaCreacionOrden = document.getElementById("fechacreacion").value;
    // let fechaEntrega = document.getElementById("fechaentrega").value;
    // let prenda = document.getElementById("prenda").value;
    // var tagsinput = [];
    // let muestraOriginal = $('input[name=muestraoriginal]:checked').val();
    // let muestraReferencia = $('input[name=muestrareferencia]:checked').val();

    // var datoOrdenEntrega = {
    //   marca: marca,
    //   idMarca: idMarca,
    //   numeroOrden: numeroOrden,
    //   modelo: modelo,
    //   fechaCreacionOrden: fechaCreacionOrden,
    //   fechaEntrega: fechaEntrega,
    //   prenda: prenda,
    //   muestraReferencia: muestraReferencia,
    //   muestraOriginal: muestraReferencia
    // };
  }
</script>
<script>
  $("#tags").keyup(function() {
    obtenerMarcas();
  });
  document.getElementById("idmarca").value = "";
  document.getElementById("tags").value = "";
</script>
<script>
  $("#datepicker").datepicker();
  $("#fechaentrega").datepicker();
</script>
<script>
  $('input[name="coordinados"]').amsifySuggestags();
</script>

<style>
  .col-md-8 {
    border-style: solid;
  }

  #tags {
    width: 130px;
    height: 32px;
  }

  .filas {
    display: flex;
    justify-content: flex-start;
  }

  .botonprincipal {
    margin: 10px;
  }
</style>
@endsection