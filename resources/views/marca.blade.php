@extends('layouts.simpleapp')
<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery2.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jqueryui1.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
<link href="{{ asset('css/jqueryuitheme.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/estilosmarca.css') }}" />
<script type="text/javascript" src="{{ asset('js/firmapadlibreria.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sweetalert2.js') }}"></script>
<!-- Iconos para el tag input -->
<link rel="stylesheet" href="{{ asset('css/awesomeiconstags.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />
<!-- Iconso para el tag input -->
<script src="{{ asset('js/bootstrap-tagsinput.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-tagsinput.css') }}" />
<script type="text/javascript" src="{{ asset('js/axios.js') }}"></script>
@section('content')
    <div class="container">
        <div id="filaprincipal" class="filas">
            <div id="the-basics" class="input-group input-group-sm mb-3 botones ">
                <div class="input-group-prepend ui-widget">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Marca</span>
                    <input class="typeahead form-control" id="tags" aria-label="Small"
                        aria-describedby="inputGroup-sizing-sm" onchange="obtenerMarcas()">
                    <input type="hidden" id="idmarca" />
                </div>
            </div>
            <div class="input-group input-group-sm mb-3 botones">
                <span class="input-group-text" id="inputGroup-sizing-sm">Numero de orden</span>
                <input type="text" id="numeroorden" class="form-control" aria-label="Small"
                    aria-describedby="inputGroup-sizing-sm">
            </div>
        </div>
        <div id="segundafila" class="filas">
            <div class="input-group input-group-sm mb-3 botones">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Modelo</span>
                </div>
                <input type="text" id="modelo" class="form-control" aria-label="Small"
                    aria-describedby="inputGroup-sizing-sm">
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
                <input type="text" id="prenda" class="form-control" aria-label="Small"
                    aria-describedby="inputGroup-sizing-sm">
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
                        <label><input type="radio" value=true name="muestrareferencia">Si</label>
                    </div>
                    <div class="radio miradio">
                        <label><input type="radio" value=false name="muestrareferencia" checked>No</label>
                    </div>
                </div>
            </div>
            <div class="botonradio" style="display: flex; max-height: 45px; ">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Muestra Original</span>
                    <div class="radio miradio">
                        <label><input type="radio" value=true name="muestraoriginal">Si</label>
                    </div>
                    <div class="radio miradio">
                        <label><input type="radio" value=false name="muestraoriginal" checked>No</label>
                    </div>
                </div>
            </div>
        </div>
        <div id="contenedormatrizmarcas">
            <input type="button" class="btn btn-primary botonprincipal" value="Agregar Talla"
                onclick="addColumn('tblSample')" />
            <input type="button" class="btn btn-primary botonprincipal" value="Eliminar Talla"
                onclick="deleteColumn('tblSample')" />
            <input type="button" class="btn btn-primary botonprincipal" value="Agregar Fila"
                onclick="addRow('tblSample')" />
            <input type="button" class="btn btn-primary botonprincipal" value="Eliminar Ultima Fila"
                onclick="deleteRow('tblSample')" />
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
        <button type="button" class="btn btn-primary botonprincipal" onclick="crearOrdenMaquila()">Guardar orden de
            maquila</button>

    </div>
    <script type="text/javascript" src="{{ asset('controladores/marca.js') }}"></script>
    <script>
        obtenerMarcas();
        var availableTags;
        var marcas;

        function obtenerOrdenesMaquila() {
            axios.get("obtenerordenesmaquila").then(response => {}).catch(error => {});
        }

        function obtenerMarcas() {
            console.log("me ejucuto al iniciar");
            return new Promise((resolve) => {
                axios.get("obtenermarcasauto").then(response => {
                    marcas = response.data;
                    Swal.close();
                }).catch(error => {

                    Swal.fire({
                        icon: "error",
                        text: "Hubo un error...",
                    });
                    Swal.close();
                });

                availableTags = marcas;
                $(function() {
                    $("#tags").autocomplete({
                        source: availableTags,
                        focus: function(event, ui) {
                            $(this).val(ui.item.label);
                            return false;
                        },
                        select: function(event, ui) {
                            $(this).val(ui.item.label);
                            $("#idmarca").val(ui.item.value);
                            return false;
                        }
                    });
                });
                resolve("yeah");
            });

        }

        async function crearOrdenMaquila() {
            let nombreMarca = document.getElementById("tags").value;
            let nuevaMarca = true;
            idMarca = "";
            if (nombreMarca == null || nombreMarca == "") {
                alert("Favor de llenar la marca...");
                return false;
            } else {
                if (typeof availableTags === 'undefined') {
                    var testme = await obtenerMarcas();
                }
                availableTags.filter(function(item) {
                    if (item.label === nombreMarca) {
                        nuevaMarca = false;
                        idMarca = document.getElementById("idmarca").value;
                    }
                });
            }
            validarFormulario();
            Swal.showLoading();
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
                        let texto = JSON.stringify(ordenEntrada);
                        let reConvertido = JSON.parse(texto);
                        totalEntradas.push(reConvertido);
                    }
                }
            }
            let coordinados = $('input[name="coordinados"]').amsifySuggestags();
            var date = $("#fechaentrega").datepicker("getDate");
            let numeroOrden = document.getElementById("numeroorden").value;
            let modelo = document.getElementById("modelo").value;
            let fechaEntrega = document.getElementById("fechaentrega").value;
            let prenda = document.getElementById("prenda").value;
            let muestraOriginal = $('input[name=muestraoriginal]:checked').val();
            let muestraReferencia = $('input[name=muestrareferencia]:checked').val();
            if (muestraOriginal == "true") {
                muestraOriginal = true;
            } else {
                muestraOriginal = false;
            }
            if (muestraReferencia == "true") {
                muestraReferencia = true;
            } else {
                muestraReferencia = false;
            }

            var enUtc = moment.utc(date).toDate();
            var zonaLocal = moment(enUtc).local().format("DD-MM-YYYY HH:mm:ss");
            let ordenMaquila = {
                nombreMarca: nombreMarca,
                nuevaMarca: nuevaMarca,
                idMarca: idMarca,
                muestraOriginal: muestraOriginal,
                muestraReferencia: muestraReferencia,
                modelo: modelo,
                prenda: prenda,
                numeroOrden: numeroOrden,
                fechaEntrega: enUtc,
                totalEntradas: totalEntradas
            };
            axios.post("crearordenmaquila", ordenMaquila).then(response => {
                Swal.close();
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Se guardo la orden correctamente',
                    showConfirmButton: false,
                    timer: 25000
                })
                window.location.href = 'ordenesmaquila';
            }).catch(error => {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Opps! Hubo un error',
                    showConfirmButton: false,
                    timer: 2500
                })
            });
        }

    </script>
    <script>
        $("#tags").keyup(function() {
            if (document.getElementById("tags").value.length > 1) {
                return false;
            } else {
                obtenerMarcas();
            }
        });
        document.getElementById("idmarca").value = "";
        document.getElementById("tags").value = "";

    </script>
    <script>
        $("#datepicker").datepicker();
        $("#fechaentrega").datepicker({
            dateFormat: 'dd-mm-yy'
        });

    </script>
    <script>
        $('input[name="coordinados"]').amsifySuggestags();

    </script>
    <style>
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
