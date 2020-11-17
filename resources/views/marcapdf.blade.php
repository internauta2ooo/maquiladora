<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <table class="banner">
    <tbody>
      <tr>
        <td class="td-25">
           <p>Marca:{{$filasOrdenadas["datos_orden"]["marca"]}}</p>
  
        </td>
        <td class="td-25">
     
    Folio: {{$filasOrdenadas["datos_orden"]["folio"]}}
  </p>
        </td>
        <td class="td-25">
<p>Modelo: {{$filasOrdenadas["datos_orden"]["modelo"]}}</p>
        </td>
        <td class="td-25">
<p>Fecha creacion: {{$filasOrdenadas["datos_orden"]["fecha_creacion"]}}</p>
        </td>

      </tr>
      <tr>
        <td class="td-25">
<p>Fecha entrega: {{$filasOrdenadas["datos_orden"]["fecha_entrega"]}}</p>
        </td>
        <td class="td-25">
          <p>Usuario: {{$filasOrdenadas["datos_orden"]["usuario"]}}</p>
        </td>
        <td class="td-25">
          <p>Total prendas:  {{$filasOrdenadas["datos_orden"]["total_piezas"]}}</p>
        </td>
       


      </tr>
      <tr>
        <td class="td-25">
          <p>Muestra original:  {{$filasOrdenadas["datos_orden"]["muestra_original"]}}</p>
        </td>
        <td class="td-25">
          <p>Muestra referencia:  {{$filasOrdenadas["datos_orden"]["muestra_referencia"]}}</p>
        </td>
        <td class="td-25">
          <p>Prendas:  {{$filasOrdenadas["datos_orden"]["prenda"]}}</p>
        </td>
      </tr>
    </tbody>
  </table>
   
  









  
  
 
 
  
  
  <div id="tabla">
   
    <table id="tablatallas" cellspacing="0" cellpadding="0" align="center" border="1">               
        @foreach($filasOrdenadas["lista"] as $filas)
      <tr class="trclass">
        @foreach($filas as $columnas)
        <td class="tdclass">{{$columnas}}</td>       
          @endforeach   
      </tr>
        @endforeach            
    </table>
    
    </div>
  </body>
</html>
<style>

.trclass,.tdclass{
padding:10px;
margin:10px;
}
.td-25{
    width: 50%;
    padding:0px;
}
.td-50{
    width: 50%;
}
.td-100{
    width: 100%;
}
#tabla{
 padding-top:15px;
 margin-top:15px;

}
</style>