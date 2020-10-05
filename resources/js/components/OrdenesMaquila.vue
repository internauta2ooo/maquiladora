<template>
    <div class="container">
        <div>
            <b-table :items="items" :fields="fields" striped responsive="sm">
                <template v-slot:cell(show_details)="row">
                    <b-button size="sm" @click="row.toggleDetails" class="mr-2"
                        >{{
                            row.detailsShowing ? "Esconder" : "Mostrar"
                        }}
                        Detalles</b-button
                    >
                    <!-- As `row.showDetails` is one-way, we call the toggleDetails function on @change -->
                </template>
                <template v-slot:row-details="row">
                    <b-card>
                        <b-row class="mb-2">
                            <b-col sm="3" class="text-sm-right">
                                <b>Entradas de la orden</b>
                            </b-col>
                            <ul id="listaordenes" style="list-style-type:none;">
                                <li
                                    id="ordenes"
                                    v-for="item in row.item.ordenesTallas"
                                >
                                    <strong>Id:</strong>
                                    {{ item.cantidad_ordenes_id }}
                                    <strong>&nbsp;&nbsp;Coordinado:</strong>
                                    {{ item.coordinado_id }}
                                    <strong>&nbsp;&nbsp;Color:</strong>
                                    {{ item.talla_id }}
                                    <strong>&nbsp;&nbsp;Talla:</strong>
                                    {{ item.color_id }}
                                    <strong>&nbsp;&nbsp;Piezas:</strong>
                                    {{ item.cantidad_orden }}
                                    <strong>&nbsp;&nbsp;Piezas:</strong>
                                    {{ item.cantidad_orden_entregadas }}
                                </li>
                            </ul>
                            <b-col sm="3" class="text-sm-right">
                                <b>Total de piezas: </b>
                            </b-col>
                        </b-row>
                        <b-button size="sm" @click="row.toggleDetails"
                            >Esconder detalles</b-button
                        >
                    </b-card>
                </template>
            </b-table>
        </div>
        <b-button variant="primary" @click="obtenerOrdenesMaquila()"
            >Ya soy reactivo 2.00</b-button
        >
    </div>
</template>

<script>
export default {
    methods: {
        obtenerOrdenesMaquila() {
            Swal.showLoading();
            console.log("run it");
            console.log(this.items[0]);
            this.items[0].marca = "Reactivo";
            axios
                .get("obtenerordenesmaquila")
                .then(response => {
                    console.log("traemos las ordenes de entrega");
                    console.log(response.data);
                    this.items = response.data;
                    Swal.close();
                })
                .catch(error => {
                    console.log("error general");
                });
        }
    },
    data() {
        return {
            fields: [
                { key: "folio_id", label: "Numero de Orden" },
                { key: "marca", label: "Marca" },
                { key: "fecha_entrega", label: "Fecha de Entrega" },
                { key: "show_details", label: "Mostrar Detalles" }
            ],
            items: [
                {
                    isActive: true,
                    orden_entrega_id: 10,
                    marca_id: "Polo",
                    folio_id: "12/12/2021",
                    modelo_id: 100,
                    fecha_entrega: "12/12/2021",
                    prenda_id: "100",
                    muestra_original: true,
                    ordenesTallas: [
                        {
                            cantidad_ordenes_id: 1,
                            coordinado_id: "nike color",
                            talla_id: "rojo",
                            color_id: "mediana",
                            cantidad_orden: 15,
                            cantidad_orden_entregadas: 15
                        },
                        {
                            cantidad_ordenes_id: 1,
                            coordinado_id: "nike color",
                            talla_id: "rojo",
                            color_id: "mediana",
                            cantidad_orden: 15,
                            cantidad_orden_entregadas: 15
                        },
                        {
                            cantidad_ordenes_id: 1,
                            coordinado_id: "nike color",
                            talla_id: "rojo",
                            color_id: "mediana",
                            cantidad_orden: 15,
                            cantidad_orden_entregadas: 15
                        }
                    ]
                },
                {
                    isActive: true,
                    orden_entrega_id: 10,
                    marca_id: "Polo",
                    folio_id: "12/12/2021",
                    modelo_id: 100,
                    fecha_entrega: "12/12/2021",
                    prenda_id: "100",
                    muestra_original: true,
                    ordenesTallas: [
                        {
                            cantidad_ordenes_id: 1,
                            coordinado_id: "nike color",
                            talla_id: "rojo",
                            color_id: "mediana",
                            cantidad_orden: 15,
                            cantidad_orden_entregadas: 15
                        },
                        {
                            cantidad_ordenes_id: 1,
                            coordinado_id: "nike color",
                            talla_id: "rojo",
                            color_id: "mediana",
                            cantidad_orden: 15,
                            cantidad_orden_entregadas: 15
                        },
                        {
                            cantidad_ordenes_id: 1,
                            coordinado_id: "nike color",
                            talla_id: "rojo",
                            color_id: "mediana",
                            cantidad_orden: 15,
                            cantidad_orden_entregadas: 15
                        }
                    ]
                },
                {
                    isActive: true,
                    orden_entrega_id: 10,
                    marca_id: "Polo",
                    folio_id: "12/12/2021",
                    modelo_id: 100,
                    fecha_entrega: "12/12/2021",
                    prenda_id: "100",
                    muestra_original: true,
                    ordenesTallas: [
                        {
                            cantidad_ordenes_id: 1,
                            coordinado_id: "nike color",
                            talla_id: "rojo",
                            color_id: "mediana",
                            cantidad_orden: 15,
                            cantidad_orden_entregadas: 15
                        },
                        {
                            cantidad_ordenes_id: 1,
                            coordinado_id: "nike color",
                            talla_id: "rojo",
                            color_id: "mediana",
                            cantidad_orden: 15,
                            cantidad_orden_entregadas: 15
                        },
                        {
                            cantidad_ordenes_id: 1,
                            coordinado_id: "nike color",
                            talla_id: "rojo",
                            color_id: "mediana",
                            cantidad_orden: 15,
                            cantidad_orden_entregadas: 15
                        }
                    ]
                }
            ]
        };
    },
    created: function() {
        this.obtenerOrdenesMaquila();
    },
    mounted() {
        console.log("Component mounted.");
    }
};
</script>
<style scoped>
#ordenes {
    border-style: solid;
    border-color: rgb(199, 199, 199);
    margin: 5px;
}
</style>
