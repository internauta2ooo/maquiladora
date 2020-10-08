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
                                    v-for="item in row.item.numerodeprendas"
                                >
                                    <strong>Id entrada:</strong>
                                    {{ item.id_entrada }}
                                    <strong>&nbsp;&nbsp;Coordinado:</strong>
                                    {{ item.coordinado }}
                                    <strong>&nbsp;&nbsp;Color:</strong>
                                    {{ item.color }}
                                    <strong>&nbsp;&nbsp;Talla:</strong>
                                    {{ item.descripcion }}
                                    <strong>&nbsp;&nbsp;Piezas:</strong>
                                    {{ item.numero }}
                                </li>
                            </ul>
                            <b-col sm="3" class="text-sm-right">
                                <b>Total de piezas: </b
                                >{{ row.item.totalpiezas }}
                            </b-col>
                        </b-row>
                        <b-button size="sm" @click="row.toggleDetails"
                            >Esconder detalles</b-button
                        >
                        <b-button size="sm" @click="row.toggleDetails"
                            >Imprimir PDF de la orden</b-button
                        >
                    </b-card>
                </template>
            </b-table>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        actualizarAlgo() {
            console.log("run it");
            console.log(this.items[0]);
            this.items[0].marca = "Reactivo";
        }
    },
    data() {
        return {
            fields: [
                { key: "id_orden", label: "Numero de Orden" },
                { key: "marca", label: "Marca" },
                { key: "fecha_entrega", label: "Fecha de Entrega" },
                { key: "show_details", label: "Mostrar Detalles" }
            ],
            items: [
                {
                    isActive: true,
                    id_orden: 10,
                    marca: "Polo",
                    fecha_entrega: "12/12/2021",
                    totalpiezas: 100,
                    numerodeprendas: [
                        {
                            id_entrada: 1,
                            coordinado: "nike color",
                            color: "rojo",
                            descripcion: "mediana",
                            numero: 15
                        },
                        {
                            id_entrada: 1,
                            coordinado: "nike dos.0",
                            color: "azul",
                            descripcion: "mediana",
                            numero: 25
                        },
                        {
                            id_entrada: 1,
                            coordinado: "stud",
                            color: "verde",
                            descripcion: "grande",
                            numero: 35
                        }
                    ]
                },
                {
                    isActive: true,
                    id_orden: 40,
                    marca: "DC",
                    fecha_entrega: "12/12/2021",
                    totalpiezas: 200,
                    numerodeprendas: [
                        {
                            id_entrada: 1,
                            coordinado: "masal",
                            color: "rojo",
                            descripcion: "xl",
                            numero: 45
                        },
                        {
                            id_entrada: 1,
                            coordinado: "nikecolor3000",
                            color: "rojo",
                            descripcion: "mxl",
                            numero: 105
                        }
                    ]
                },
                {
                    isActive: true,
                    id_orden: 30,
                    marca: "Dickerson",
                    fecha_entrega: "12/12/2021",
                    totalpiezas: 250,
                    numerodeprendas: [
                        {
                            id_entrada: 1,
                            coordinado: "tsuto",
                            color: "rojo verdoso",
                            descripcion: "XL",
                            numero: 15
                        },
                        {
                            id_entrada: 1,
                            coordinado: "noo",
                            color: "azul",
                            descripcion: "SL",
                            numero: 1005
                        },
                        {
                            id_entrada: 1,
                            coordinado: "nike zer",
                            color: "rojo",
                            descripcion: "mediana",
                            numero: 15
                        }
                    ]
                },
                {
                    isActive: true,
                    id_orden: 20,
                    marca: "Alexis",
                    fecha_entrega: "12/12/2021",
                    totalpiezas: 1010,
                    numerodeprendas: [
                        {
                            id_entrada: 1,
                            coordinado: "basket",
                            color: "blanco",
                            descripcion: "SL",
                            numero: 159
                        }
                    ]
                }
            ]
        };
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
