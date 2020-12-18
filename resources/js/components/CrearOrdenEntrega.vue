<template>
    <div class="container">
        
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
