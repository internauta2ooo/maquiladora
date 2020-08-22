var tablaModelosData;
var tablaTallasData;
var tablaCoordinadosData;
var tablaColoresData;

$(".ssss").change(function(){
  alert("The text has been changed.");
}); 

$("tblSample input").change(function(){
  alert("The text has been changed.");
}); 

function actualizarValor(thiss){
alert("ol");
// console.log($("#yeah").val());
// console.log(thiss);
console.log("true value");
console.log(thiss.value);
}

$(document).ready(function () {
  $("#fechaOrdenMaquila").datepicker({
    format: 'dd/mm/yyyy'
  });
});
function obtenerJson() {
 
  
  // console.log("La tabla -> objeto de DOM");
  // console.log(document.getElementById("tblSample"));
  // console.log(document.getElementById("tblSample").rows[0].cells[0].innerHTML);
  // console.log(document.getElementById("tblSample").rows[0].cells.length);
  var filas=document.getElementById("tblSample").rows.length;
  var columnas=document.getElementById("tblSample").rows[0].cells.length
  for(var filasi=0;filasi<filas;filasi){
    console.log("el for" +filasi);
    // console.log(document.getElementById("tblSample").rows[i].cells[1].firstChild);

    for(var columnasi=0;columnasi<columnas;columnasi){
      console.log("la columna: "+columnasi);      
      // console.log(document.getElementById("tblSample").rows[filasi].cells[columnasi]);
      // console.log(document.getElementById("tblSample").rows[filasi].cells[columnasi].firstChild.value);
      console.log(document.getElementById("tblSample").rows[filasi].cells[columnasi]);
      console.log(document.getElementById("tblSample").rows[filasi].cells[columnasi].firstChild.value);
      columnasi++;
    }
    


    filasi++;
  }

  var x = document.getElementById("tblSample").rows[2].cells[2].firstChild.value;
//   // var x = document.getElementById("tblSample").rows[2].cells[2].firstChild.value;
//   console.log(.rows[2].cells[3].firstChild.value);
//   var x = document.getElementById("tblSample").rows[2].cells[2];
//   console.log("el value");
//   console.log(x);
}
function modalCrearOrdenMaquila() {
  $('#crearOrdenMaquila').modal('toggle');
}
//Crea la vista principal
function obtenerMarcas() {

  var data;
  axios.get("http://192.168.100.231:8089/marca/obtenerMarcas").then(function (response) {
    data = response.data;
    Object.keys(data).forEach(function (key) {
      console.log("la data en el foreachs");
      let botonInformacion = { "botonInformacion": '<button type="button" class="btn btn-primary" data-toggle="modal" onclick="verInformacionPrincipal(' + data[key].marca_id + ')">Ver informacion</button>' };
      console.log(data[key].marca_id)
      Object.assign(data[key], botonInformacion)
    })

    $(document).ready(function () {
      var table = $('#table_id').DataTable({
        responsive: true,
        data: data,

        columns: [
          { data: "marca_id" },
          { data: "nombre" },
          { data: "fecha_creacion" },
          { data: "fecha_modificacion" },
          { data: "botonInformacion" }

        ]
      });

      $('#table_id ').on('click', 'td:nth-child(5n)', function () {

        let marcaId = table.cell(this, 0).data();

      });

    });

  }).catch(
    //Do nothing
  );

}
//Al dar click en ver infomacion
function verInformacionPrincipal(marcaId) {

  document.getElementById("verModalModelos").setAttribute("marcaid", marcaId);
  console.log("ver informacion modelos principal");
  obtenerModelos(marcaId);
  $("#bodyTablaTallas").hide();
  $("#bodyTablaModelos").show();


}
//el regreso al modal
function verInformacionModelos() {
  console.log("ver informacion modelos el regreso");
  var marcaId = document.getElementById("verModalModelos").getAttribute("marcaid");
  obtenerModelos(marcaId);
  $("#bodyTablaTallas").hide();
  $("#bodyTablaModelos").show();


}
function verInformacionTallas(marcaId) {
  console.log("ver informacion tallas");
  console.log(marcaId);
  $("#bodyTablaModelos").hide();
  $("#bodyTablaTallas").show();

}
//Crear marca
function crearMarca(nombre) {

  axios.post("http://192.168.100.231:8089/marca/insertarMarca", { nombre: nombre }).then(
    function (response) {

    }
  ).catch();
}
//Actualilzar modelos
function actualizarModelos(marcaId) {

  console.log("Actualizando Modelos");
  console.log(marcaId.attributes.marcaId.nodeValue);
  marcaId = marcaId.attributes.marcaId.nodeValue;
  var data = tablaModelosData.getAllValue();
  console.log(data);
  axios.post("http://192.168.100.231:8089/modelo/actualizarModelos", { marcaId: marcaId, modelos: data })
    .then(function (response) {
      console.log(response);
      // alert("Actualizamos aqui haremos el recharge del icono");
      $('#verModalModelos').modal('toggle');
      verInformacionModelos(marcaId);
    })
    .catch();

}
//Guardar tallas
// function guardarTallas() {
//   alert("Guardare las tallas");
//   var data = myAppendGrid.getAllValue();
//   axios.post("http://192.168.100.231:8089/talla/actualizarTallas", data).then(function (response) {

//   }).catch();
//   console.log(data);
// }

var tallaId;
var myAppendGrid;

////////////////////////////////////////////////////////////////////
//Aqui se muestra  el modal de los modelos relacionados a la marca...
////////////////////////////////////////////////////////////////////
function obtenerModelos(marcaId) {
  axios.get("http://192.168.100.231:8089/modelo/obtenerModelos?marcaId=" + marcaId).then(
    function (response) {

      console.log("modelos");
      console.log(marcaId);
      console.log(response);

      tablaModelosData = new AppendGrid({
        element: "tablaModelos",
        uiFramework: "bootstrap4",
        iconFramework: "fontawesome5",
        /* oculta la columna de appendgrid */
        hideRowNumColumn: true,
        columns: [
          {
            name: "modelo_id",
            display: "Id Modelo",
            type: "readonly",

            events: {
              // Add click event
              click: function (e) {
                var modeloId = tablaModelosData.getCtrlValue("modelo_id", tablaModelosData.getRowIndex(e.uniqueIndex));
                // axios.get("http://192.168.100.231:8089/talla/obtenerTallas?modeloId=1").then(function(response) {
                alert("consulto las tallas");
                document.getElementById("verModalInformacionModelos").setAttribute("modeloid", modeloId);
                obtenerTallas(modeloId);
                obtenerColores(modeloId);
                obtenerCoordinados(modeloId)
                setTimeout(function () {
                  $('#verModalInformacionModelos').modal('show');
                  // tablaTallasData.load(response.data.data);
                  // console.log("la data");
                  // console.log(tablaTallasData.getAllValue());
                }, 1000)


              }
            }

          },
          {
            name: "modelo",
            display: "Identificador Modelo",

          }, {
            name: "nombre",
            display: "Nombre Modelo",

          },
          {
            name: "fecha_modificacion",
            display: "Fecha Modificacion",
            type: "readonly"

          }
        ],
        // Optional CSS classes, to make table slimmer!
        sectionClasses: {
          table: "table-sm",
          control: "form-control-sm",
          buttonGroup: "btn-group-sm"
        },
        beforeRowRemove: function (caller, rowIndex) {
          return confirm("¿Estas seguro de borrar esta talla?");


        },
        afterRowRemoved: function (caller, rowIndex) {
          // // addLog(caller, "`afterRowRemoved` triggered, rowIndex=" + rowIndex);
          // axios.post("http://192.168.100.231:8089/talla/eliminarTalla",{tallaId:tallaId}).then(function(response){
          //       // alert("eleimnado en el api");
          //       console.log(response);
          //   }).catch();

        }


      });
      // $("#load").on("click", function () {
      tablaModelosData.load(response.data.data);
      console.log("run de mmodal");
      setTimeout(function () {
        $('#verModalModelos').modal('show');
      }, 1000)

    }
  ).catch(

  );
}
//Obtener tallas
function obtenerTallas(modeloId) {
  console.log(modeloId);
  axios.get("http://192.168.100.231:8089/talla/obtenerTallas?modeloId=" + modeloId)
    .then(function (response) {
      console.log("la respuesta de la tallas");
      console.log(response.data.data);
      console.log("la data");
      // console.log(tablaTallasData.getAllValue());
      tablaTallasData = new AppendGrid({
        element: "tablaTallas",
        uiFramework: "bootstrap4",
        iconFramework: "fontawesome5",
        /* oculta la columna de appendgrid */
        hideRowNumColumn: true,
        columns: [
          {
            name: "talla_id",
            display: "Id Talla",
            type: "readonly",


          },
          {
            name: "nombre",
            display: "Nombre",

          },
          {
            name: "descripcion",
            display: "Descripcion",

          },
          {
            name: "fecha_modificacion",
            display: "Fecha Modificacion",
            type: "readonly"

          }
        ],
        // Optional CSS classes, to make table slimmer!
        sectionClasses: {
          table: "table-sm",
          control: "form-control-sm",
          buttonGroup: "btn-group-sm"
        },
        beforeRowRemove: function (caller, rowIndex) {
          return confirm("¿Estas seguro de borrar esta talla?");


        },
        afterRowRemoved: function (caller, rowIndex) {
          // // addLog(caller, "`afterRowRemoved` triggered, rowIndex=" + rowIndex);
          // axios.post("http://192.168.100.231:8089/talla/eliminarTalla",{tallaId:tallaId}).then(function(response){
          //       // alert("eleimnado en el api");
          //       console.log(response);
          //   }).catch();

        }


      });



      // setTimeout(function () {
      //   $('#verModalInformacionModelos').modal('show');
      tablaTallasData.load(response.data.data);
      //   // console.log("la data");
      //   // console.log(tablaTallasData.getAllValue());
      // }, 1000)

    }).catch(function (error) {

    });


}

function obtenerColores(modeloId) {
  console.log(modeloId);
  axios.get("http://192.168.100.231:8089/modelo/obtenerColores?modeloId=" + modeloId)
    .then(function (response) {
      console.log("la respuesta de la tallas");
      console.log(response.data.data);
      console.log("la data");
      // console.log(tablaTallasData.getAllValue());
      tablaColoresData = new AppendGrid({
        element: "tablaColores",
        uiFramework: "bootstrap4",
        iconFramework: "fontawesome5",
        /* oculta la columna de appendgrid */
        hideRowNumColumn: true,
        columns: [
          {
            name: "color_id",
            display: "Id Talla",
            type: "readonly",


          },
          {
            name: "nombre",
            display: "Nombre",

          },
          {
            name: "descripcion",
            display: "Descripcion",

          },
          {
            name: "fecha_modificacion",
            display: "Fecha Modificacion",
            type: "readonly"

          }
        ],
        // Optional CSS classes, to make table slimmer!
        sectionClasses: {
          table: "table-sm",
          control: "form-control-sm",
          buttonGroup: "btn-group-sm"
        },
        beforeRowRemove: function (caller, rowIndex) {
          return confirm("¿Estas seguro de borrar esta talla?");


        },
        afterRowRemoved: function (caller, rowIndex) {
          // // addLog(caller, "`afterRowRemoved` triggered, rowIndex=" + rowIndex);
          // axios.post("http://192.168.100.231:8089/talla/eliminarTalla",{tallaId:tallaId}).then(function(response){
          //       // alert("eleimnado en el api");
          //       console.log(response);
          //   }).catch();

        }

      });

      // setTimeout(function () {
      //   $('#verModalInformacionModelos').modal('show');
      tablaColoresData.load(response.data.data);
      //   console.log("la data");
      //   console.log(tablaTallasData.getAllValue());
      // }, 1000)

    }).catch(function (error) {

    });


}

function obtenerCoordinados(modeloId) {
  console.log(modeloId);
  axios.get("http://192.168.100.231:8089/modelo/obtenerCoordinados?modeloId=" + modeloId)
    .then(function (response) {
      console.log("la respuesta de la tallas");
      console.log(response.data.data);
      console.log("la data");
      // console.log(tablaTallasData.getAllValue());
      tablaCoordinadosData = new AppendGrid({
        element: "tablaCoordinados",
        uiFramework: "bootstrap4",
        iconFramework: "fontawesome5",
        /* oculta la columna de appendgrid */
        hideRowNumColumn: true,
        columns: [
          {
            name: "coordinado_id",
            display: "Id Coordinado",
            type: "readonly",


          },
          {
            name: "nombre",
            display: "Nombre",

          },
          {
            name: "descripcion",
            display: "Descripcion",

          },
          {
            name: "fecha_modificacion",
            display: "Fecha Modificacion",
            type: "readonly"

          }
        ],
        // Optional CSS classes, to make table slimmer!
        sectionClasses: {
          table: "table-sm",
          control: "form-control-sm",
          buttonGroup: "btn-group-sm"
        },
        beforeRowRemove: function (caller, rowIndex) {
          return confirm("¿Estas seguro de borrar esta talla?");


        },
        afterRowRemoved: function (caller, rowIndex) {
          // // addLog(caller, "`afterRowRemoved` triggered, rowIndex=" + rowIndex);
          // axios.post("http://192.168.100.231:8089/talla/eliminarTalla",{tallaId:tallaId}).then(function(response){
          //       // alert("eleimnado en el api");
          //       console.log(response);
          //   }).catch();

        }


      })

      // setTimeout(function () {
      //   $('#verModalInformacionModelos').modal('show');
      tablaCoordinadosData.load(response.data.data);
      //   console.log("la data");
      //   console.log(tablaTallasData.getAllValue());
      // }, 1000)

    }).catch(function (error) {

    });


}

function actualizarInformacionModelo(modeloId) {

  alert("Mi modelo ID: " + modeloId);
  console.log(modeloId);
  actualizarTallas(modeloId, tablaTallasData.getAllValue());
  console.log(tablaTallasData.getAllValue());
  console.log(tablaColoresData.getAllValue());
  console.log(tablaCoordinadosData.getAllValue());


}

function actualizarTallas(modeloId, dataTallas) {
  0
  axios.post("http://192.168.100.231:8089/talla/actualizarTallas?modeloId=" + modeloId, { tallas: dataTallas })
    .then(function (response) {
      console.log("el response de actualizar tallas");
      console.log(response.data);
    }).catch(function (error) {
      console.log("el response de actualizar tallas error");
      console.log(error);
    });

}
function addColumn(tblId) {
  var tblHeadObj = document.getElementById(tblId).tHead;
  for (var h = 0; h < tblHeadObj.rows.length; h++) {
    var newTH = document.createElement('th');
    tblHeadObj.rows[h].appendChild(newTH);
    newTH.innerHTML = '<input type="text" class="form-control" id="yeah" name="yeah" oninput="actualizarValor(this)"/>';
  }

  var tblBodyObj = document.getElementById(tblId).tBodies[0];
  for (var i = 0; i < tblBodyObj.rows.length; i++) {
    var newCell = tblBodyObj.rows[i].insertCell(-1);
    newCell.innerHTML = ' <input type="text" class="form-control" id="yeah" name="yeah" oninput="actualizarValor(this)"/> ';
  }
}
function addRow(tblId) {
  console.log("Para las filas");
  // document.getElementById('table1').rows[0].cells.length
  console.log(document.getElementById(tblId).rows.length);
  console.log(document.getElementById('tblSample').rows[0].cells.length);
  var x = document.getElementById('tblSample').insertRow(-1);
  // let p=x.insertCell(0); 
  // p.innerHTML='<select name="fruit"><option value ="none">Nothing</option><option value ="guava">Guava</option><option value ="lychee">Lychee</option><option value ="papaya">Papaya</option></select>';
  for (var i = 0; i < document.getElementById('tblSample').rows[0].cells.length; i++) {

    var y = x.insertCell(i);
    if (i == 0) {
      y.innerHTML = i + "ss";
      y.innerHTML = '<select class="custom-select" name="coordinadoselect"><option value ="none">Nothing</option><option value ="guava">Guava</option><option value ="lychee">Lychee</option><option value ="papaya">Papaya</option></select>';
    }
    else {

      // let z = x.insertCell(0); 
      // y.innerHTML = ' <input type="text"  id="yeah" name="yeah" oninput="actualizarValor(this);"/> ';
      var elinput=document.createElement('input');
      elinput.setAttribute("value","");
      elinput.classList.add("form-control");

      
      y.appendChild(elinput);
      //   y.innerHTML=i+"ss";
      // console.log(i);
    }
  }
  // z.innerHTML='<select name="fruit"><option value ="none">Nothing</option><option value ="guava">Guava</option><option value ="lychee">Lychee</option><option value ="papaya">Papaya</option></select>';

}
function deleteRow(tblId) {
  console.log(document.getElementById('tblSample').rows[0].cells.length);
  document.getElementById("tblSample").deleteRow(-1);
}
function deleteColumn(tblId) {
  console.log(document.getElementById('tblSample').rows[0].cells.length);
  var columnas = document.getElementById('tblSample').rows[0].cells.length;
  if (columnas <= 2) {
    console.log("sdd");
    return false;
  }
  var allRows = document.getElementById(tblId).rows;
  for (var i = 0; i < allRows.length; i++) {
    if (allRows[i].cells.length > 1) {
      allRows[i].deleteCell(-1);
    }
  }
}


////////////////////////////////// 
////////////////////////////////// 
////////////////////////////////// 

// $("#tagBox").tagging();



////////////////////
////////////////////
////////////////////

////////////////////


// var canvas =  document.getElementById('firma');
// console.log(canvas);
// var signaturePad = new SignaturePad(canvas);

// // Returns signature image as data URL (see https://mdn.io/todataurl for the list of possible parameters)
// signaturePad.toDataURL(); // save image as PNG
// signaturePad.toDataURL("image/jpeg"); // save image as JPEG
// signaturePad.toDataURL("image/svg+xml"); // save image as SVG

// // Draws signature image from data URL.
// // NOTE: This method does not populate internal data structure that represents drawn signature. Thus, after using #fromDataURL, #toData won't work properly.
// signaturePad.fromDataURL("data:image/png;base64,iVBORw0K...");

// // Returns signature image as an array of point groups
// const data = signaturePad.toData();

// // Draws signature image from an array of point groups
// signaturePad.fromData(data);

// // Clears the canvas
// signaturePad.clear();

// // Returns true if canvas is empty, otherwise returns false
// signaturePad.isEmpty();

// // Unbinds all event handlers
// signaturePad.off();

// // Rebinds all event handlers
// signaturePad.on();