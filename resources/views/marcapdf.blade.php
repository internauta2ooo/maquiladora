<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <table>
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
    </tbody>
  </table>
   
  


<p>Fecha entrega: {{$filasOrdenadas["datos_orden"]["fecha_entrega"]}}</p>
<p>Usuario: {{$filasOrdenadas["datos_orden"]["usuario"]}}</p>
<p>Total prendas:  {{$filasOrdenadas["datos_orden"]["total_piezas"]}}</p>

<p>Prendas:  {{$filasOrdenadas["datos_orden"]["prenda"]}}</p>
<p>Muestra original:  {{$filasOrdenadas["datos_orden"]["muestra_original"]}}</p>
<p>Muestra referencia:  {{$filasOrdenadas["datos_orden"]["muestra_referencia"]}}</p>
  
  
 
 
  
  
  
    <table class="table table-bordered">               
        @foreach($filasOrdenadas["lista"] as $filas)
      <tr>
        @foreach($filas as $columnas)
        <td>{{$columnas}}</td>       
          @endforeach   
      </tr>
        @endforeach            
    </table>
  </body>
</html>
<style>

  table, th, td {
  border: 1px solid black;
}
.td-25{
    width: 50%;
    padding:10px;
}
.td-50{
    width: 50%;
}
.td-100{
    width: 100%;
}
</style>