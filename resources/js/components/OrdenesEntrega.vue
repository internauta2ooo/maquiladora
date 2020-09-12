<template>
  <div class="container">
    <div>
      <b-table :items="items" :fields="fields" striped responsive="sm">
        <template v-slot:cell(show_details)="row">
          <b-button
            size="sm"
            @click="row.toggleDetails"
            class="mr-2"
          >{{ row.detailsShowing ? 'Esconder' : 'Mostrar'}} Detalles</b-button>
          <!-- As `row.showDetails` is one-way, we call the toggleDetails function on @change -->
        </template>
        <template v-slot:row-details="row">
          <b-card>
            <b-row class="mb-2">
              <b-col sm="3" class="text-sm-right">
                <b>Coordinado</b>
              </b-col>
              <b-col>{{ row.item.numerodeprendas }}</b-col>
            </b-row>
            <b-button size="sm" @click="row.toggleDetails">Esconder detalles</b-button>
          </b-card>
        </template>
      </b-table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fields: [
        { key: "id_orden", label: "Numero de Orden" },
        { key: "marca", label: "marca" },
        { key: "fecha_entrega", label: "Fecha de Entrega" },
        "show_details",
      ],
      items: [
        {
          isActive: true,
          id_orden: 10,
          marca: "Polo",
          fecha_entrega: "12/12/2021",
          numerodeprendas: [
            {
              id_entrada: 1,
              color: "rojo",
              tallas: [
                { descripcion: "mediana", numero: 15 },
                { descripcion: "xl", numero: 1 },
                { descripcion: "chica", numero: 150 },
              ],
            },
          ],
        },
        {
          isActive: true,
          id_orden: 40,
          marca: "DC",
          fecha_entrega: "12/12/2021",
          numerodeprendas: [
            { talla: "Mediana", numero: 15 },
            { talla: "XL", numero: 25 },
          ],
        },
        {
          isActive: true,
          id_orden: 30,
          marca: "Dickerson",
          fecha_entrega: "12/12/2021",
          numerodeprendas: [
            { talla: "Mediana", numero: 15 },
            { talla: "XL", numero: 25 },
          ],
        },
        {
          isActive: true,
          id_orden: 20,
          marca: "Alexis",
          fecha_entrega: "12/12/2021",
          numerodeprendas: [
            { talla: "Mediana", numero: 15 },
            { talla: "XL", numero: 25 },
          ],
        },
      ],
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
