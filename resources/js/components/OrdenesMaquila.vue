<template>
    <div class="container">
        <b-row align-h="center">
            <b-col>
                <b-button
                    size="lg"
                    variant="primary"
                    class="botonesmargen"
                    @click="crearMarca()"
                >
                    Crear orden</b-button
                ></b-col
            >
        </b-row>
        <b-input-group size="sm">
            <b-form-input
                v-model="filter"
                type="search"
                id="filterInput"
                placeholder="Busqueda"
            ></b-form-input>
            <b-input-group-append> </b-input-group-append>
        </b-input-group>
        <div>
            <b-table
                :items="items"
                :fields="fields"
                :filter="filter"
                :filter-included-fields="filterOn"
                @filtered="onFiltered"
                :current-page="currentPage"
                :per-page="perPage"
                striped
                responsive="sm"
            >
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
                    <b-modal :id="row.item.orden_entrega_id"
                        >Hello From My Modal!{{
                            row.item.ordenesTallas
                        }}</b-modal
                    >
                    <b-card>
                        <b-row class="mb-2">
                            <b-col sm="3" class="text-sm-right">
                                <b>Entradas de la orden</b>
                            </b-col>
                            <table>
                                <tbody v-for="row in row.item.listaOrdenada">
                                    <tr>
                                        <td v-for="key in row">
                                            {{ key }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                                    <strong
                                        >&nbsp;&nbsp;Piezas por
                                        maquilar:</strong
                                    >
                                    {{ item.cantidad_orden }}
                                    <strong
                                        >&nbsp;&nbsp;Piezas entregadas:</strong
                                    >
                                    {{ item.cantidad_orden_entregadas }}
                                </li>
                            </ul>
                            <b-col sm="6" class="text-sm-right">
                                <strong>&nbsp;&nbsp;Usuario:</strong>
                                {{ row.item.usuario_id }}
                            </b-col>
                            <b-col sm="6" class="text-sm-right">
                                <strong>&nbsp;&nbsp;Prenda:</strong>
                                {{ row.item.prenda_id }}
                                <strong>&nbsp;&nbsp;Muestra Original:</strong>
                                {{
                                    row.item.muestra_original == 0 ? "No" : "Si"
                                }}
                                <strong>&nbsp;&nbsp;Muestra Referencia:</strong>
                                {{
                                    row.item.muestra_referencia == 0
                                        ? "No"
                                        : "Si"
                                }}
                                <b>Total de piezas: </b>
                                {{ row.item.total_piezas }}
                            </b-col>
                        </b-row>

                        <b-button size="sm" @click="row.toggleDetails"
                            >Esconder detalles</b-button
                        >
                        <b-button
                            size="sm"
                            @click="imprimirOrden(row.item.orden_entrega_id)"
                            variant="primary"
                            >Imprimir PDF orden</b-button
                        >
                        <b-button
                            size="sm"
                            @click="crearOrdenEntrega()"
                            variant="primary"
                            >Crear Orden Entrega2</b-button 
                        >
                    </b-card>
                </template>
            </b-table>
        </div>
        <b-row class="justify-content-md-center">
            <b-col sm="7" md="6" class="my-1">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="my-0"
                ></b-pagination> </b-col
        ></b-row>
    </div>
</template>

<script>
export default {
    methods: {
        
        crearMarca() {
            window.location.href = "marca";
        },
        onFiltered(filteredItems) {
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        imprimirOrden(ordenEntregaId) {
            axios
                .get("ordenpdf?idOrden=" + ordenEntregaId, {
                    responseType: "blob"
                })
                .then(response => {
                    console.log(response);
                    let blob = new Blob([response.data], {
                            type: "application/pdf"
                        }),
                        url = window.URL.createObjectURL(blob);
                    window.open(url);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        crearOrdenentrega(){
            // this.$emit('input',this.idOrdenParaEntregar)
            alert("rubidem");
        },
        obtenerOrdenesMaquila() {
            Swal.showLoading();
            axios
                .get("obtenerordenesmaquila")
                .then(response => {
                    Object.keys(response.data).map(function(key, index) {
                        let tiempoUtc = response.data[key].fecha_creacion.split(
                            /[- :]/
                        );
                        moment.locale("es-mx");
                        var tiempoLocal = moment(
                            new Date(
                                Date.UTC(
                                    tiempoUtc[0],
                                    tiempoUtc[1] - 1,
                                    tiempoUtc[2],
                                    tiempoUtc[3],
                                    tiempoUtc[4],
                                    tiempoUtc[5]
                                )
                            )
                        ).format("LLLL");
                        response.data[key].fecha_creacion = tiempoLocal;
                        response.data[key].fecha_entrega = moment(
                            response.data[key].fecha_entrega
                        )
                            .local()
                            .format("LLLL");
                    });
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
            idOrdenParaEntregar:11,
            fields: [
                { key: "folio_id", label: "Numero de Orden" },
                { key: "modelo_id", label: "Modelo" },
                { key: "marca", label: "Marca" },
                {
                    key: "fecha_creacion",
                    label: "Fecha de creación",
                    sortable: true
                },
                {
                    key: "fecha_entrega",
                    label: "Fecha de Entrega",
                    sortable: true
                },
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
                }
            ],
            filter: null,
            filterOn: [],
            currentPage: 1,
            perPage: 50
        };
    },
    created: function() {
        this.obtenerOrdenesMaquila();
    },
    mounted() {
        console.log("Component mounted.");
    },
    computed: {
        totalRows() {
            return this.items.length;
        }
    }
};
</script>
<style scoped>
#ordenes {
    border-style: solid;
    border-color: rgb(199, 199, 199);
    margin: 5px;
}
.botonesmargen {
    margin-top: -5px;
    margin-bottom: 15px;
}
</style>
