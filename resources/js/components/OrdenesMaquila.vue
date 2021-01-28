/* eslint-disable vue/attribute-hyphenation */
/* eslint-disable vue/attribute-hyphenation */
<template>
  <div class="container">
    <b-row align-h="center">
      <b-col>
        <b-button
          size="sm"
          variant="primary"
          class="botonesmargen"
          @click="crearMarca()"
        >
          Crear orden
        </b-button>
      </b-col>
      <b-col> </b-col>
      <b-col> </b-col>
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
          <b-card>
            <b-row class="mb-2 primerafila">
              <b-col sm="4" class="text-sm-right">
                <p>Folio id: {{ row.item.folio_id }}</p>
                <p>Marca: {{ row.item.marca }}</p>
              </b-col>
              <b-col sm="4" class="text-sm-right">
                <p>Modelo: {{ row.item.modelo_id }}</p>
                <p>Prenda: {{ row.item.prenda_id }}</p>
                <p>Muestra original: {{ row.item.muestra_original | siono }}</p>
              </b-col>
              <b-col sm="4" class="text-sm-right">
                <p>
                  Muestra referencia:
                  {{ row.item.muestra_referencia | siono }}
                </p>
                <p>Usuario: {{ row.item.usuario_id }}</p>
                <p>Total piezas: {{ row.item.total_piezas }}</p>
              </b-col>
            </b-row>
            <b-row class="mb-2">
              <b-col sm="6" class="text-sm-right">
                <b>Entradas de la orden</b>
              </b-col>
              <b-col style="overflow: auto" sm="6" class="text-sm-right">
                <table>
                  <tbody v-for="row in row.item.listaOrdenada" v-bind:key="row">
                    <tr>
                      <td v-for="key in row" v-bind:key="key">
                        {{ key }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </b-col>
            </b-row>
            <b-row class="mb-2">
              <b-col sm="6" class="text-sm-right">
                <b>Entradas de la orden por entregar</b>
              </b-col>
              <b-col style="overflow: auto" sm="6" class="text-sm-right">
                <table>
                  <tbody
                    v-for="row in row.item.listaOrdenadaEntregada"
                    v-bind:key="row"
                  >
                    <tr>
                      <td v-for="key in row" v-bind:key="key">
                        {{ key }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </b-col>
            </b-row>

            <div v-if="row.item.ordenesEntregadas.length > 0" class="dropdown">
              <b-dropdown
                text="Ver ordenes entregadas"
                block
                variant="primary"
                class="m-2"
              >
                <b-dropdown-item
                  v-for="entregadas in row.item.ordenesEntregadas"
                  :key="entregadas"
                  href="#"
                  @click="imprimirOrdenEntregada(entregadas.orden_entregada_id)"
                >
                  Orden:{{ entregadas.orden_entregada_id }}, Fecha de creación:
                  {{ entregadas.fecha_creacion }}, Creada por:
                  {{ entregadas.usuario_id }}
                </b-dropdown-item>
              </b-dropdown>
            </div>
            <div
              v-if="row.item.ordenesEntregadas.length <= 0"
              class="nohayentregadas"
            >
              <p class="exepcionp">No hay ordenes entregadas</p>
            </div>
            <b-button size="sm" @click="row.toggleDetails" class="botonesorden"
              >Esconder detalles</b-button
            >
            <b-button
              size="sm"
              @click="imprimirOrden(row.item.orden_entrega_id)"
              variant="primary"
              class="botonesorden"
              >Imprimir PDF orden</b-button
            >
            <b-button
              size="sm"
              @click="crearOrdenEntrega(row.item.orden_entrega_id)"
              variant="primary"
              class="botonesorden"
              >Crear Orden Entrega</b-button
            >
            <b-button
              size="sm"
              @click="obtenerImagenes(row.item.orden_entrega_id)"
              variant="primary"
              class="botonesorden"
              >Subir imagenes</b-button
            >
          </b-card>
        </template>
      </b-table>
    </div>
    <b-row class="justify-content-md-center">
      <b-col sm="7" md="6" class="my-1">
        <b-pagination
          v-model="currentPage"
          :total-rows="rows"
          :per-page="perPage"
          align="fill"
          size="sm"
          class="my-0"
        ></b-pagination> </b-col
    ></b-row>
    <CrearOrdenEntrega
      :idOrdenMaquila="idOrdenParaEntregar"
      :reiniciarFirmas="reiniciarFirma"
    />
    <SubirImagenes
      :idOrdenMaquila="idOrdenParaEntregarImagenes"
      :imagenes="imagenes"
      :informacionOrden="informacionOrdenImagenes"
    />
    <ImprimirPdf :pdf="pdf" />
  </div>
</template>

<script>
import Swal from "sweetalert2";
import CrearOrdenEntrega from "./CrearOrdenEntrega.vue";
import SubirImagenes from "./SubirImagenes.vue";
import ImprimirPdf from "./ImprimirPdf.vue";
import ImageUploader from "vue-image-upload-resize";
import UploadImage from "vue-upload-image";
// register globally
Vue.use(ImageUploader);
export default {
  components: {
    CrearOrdenEntrega,
    SubirImagenes,
    //  UploadImage,
    ImprimirPdf,
  },
  filters: {
    siono: function (value) {
      let siono = value == 1 ? "Si" : "No";
      return siono;
    },
  },
  data() {
    return {
      informacionOrdenImagenes: [{ folio_id: "" }],
      pdf: "",
      idOrdenParaEntregar: [{ listaOrdenada: [] }],
      idOrdenParaEntregarImagenes: [{ listaOrdenada: [] }],
      imagenes: "",
      reiniciarFirma: false,
      fields: [
        { key: "folio_id", label: "Folio" },
        { key: "modelo_id", label: "Modelo" },
        { key: "marca", label: "Marca" },
        {
          key: "fecha_creacion",
          label: "Fecha de creación",
          sortable: true,
        },
        {
          key: "fecha_entrega",
          label: "Fecha de Entrega",
          sortable: true,
        },
        { key: "show_details", label: "Mostrar Detalles" },
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
              cantidad_orden_entregadas: 15,
            },
            {
              cantidad_ordenes_id: 1,
              coordinado_id: "nike color",
              talla_id: "rojo",
              color_id: "mediana",
              cantidad_orden: 15,
              cantidad_orden_entregadas: 15,
            },
            {
              cantidad_ordenes_id: 1,
              coordinado_id: "nike color",
              talla_id: "rojo",
              color_id: "mediana",
              cantidad_orden: 15,
              cantidad_orden_entregadas: 15,
            },
          ],
        },
      ],
      filter: null,
      filterOn: ["folio_id", "modelo_id", "marca", "fecha_creacion"],
      currentPage: 1,
      totalRows: 1,
      perPage: 10,
    };
  },
  computed: {
    rows() {
      return this.items.length;
    },
  },
  created: function () {
    console.log("Component created ordenesmaquila....");
    this.obtenerOrdenesMaquila();
  },
  mounted() {
    console.log("Component mounted.");
    this.$root.$on("obtenerimagenes", () => {
      this.obtenerImagenes();
    });
  },
  methods: {
    verOrdenEntregada(ordenEntregada) {
      alert(ordenEntregada);
    },
    obtenerImagenes(idOrden) {
      Swal.fire({
        title: "Cargando imagenes...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });
      this.idOrdenParaEntregarImagenes = idOrden;
      this.informacionOrdenes(idOrden);
      axios
        .get("obtenerimagenes?idOrden=" + idOrden)
        .then((response) => {
          var arrayKey = [];
          response.data.data.forEach((valor, index) => {
            arrayKey.push({ index: valor });
            response.data.data[index].imagen_completa =
              valor.tipo_archivo + "," + valor.imagen;
          });
          this.imagenes = response.data.data;
          this.$bvModal.show("subirimagenes");
          Swal.close();
        })
        .catch((error) => {
          Swal.close();
        });
    },
    crearMarca() {
      window.location.href = "marca";
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    blobtoB64(blob) {
      return new Promise((resolve) => {
        var reader = new FileReader();
        var base64data;
        reader.readAsDataURL(blob);
        reader.onloadend = function () {
          base64data = reader.result;
          this.pdf = base64data;
          resolve(base64data);
        };
      });
    },
    async imprimirOrden(ordenEntregaId) {
      let blob;
      await axios
        .get("ordenpdf?idOrden=" + ordenEntregaId, {
          responseType: "blob",
        })
        .then((response) => {
          blob = new Blob([response.data], {
            type: "application/pdf",
          });
        })
        .catch((error) => {});
      let b64 = await this.blobtoB64(blob);
      this.pdf = b64;
      this.$bvModal.show("imprimirpdf");
    },
    async imprimirOrdenEntregada(ordenEntregaId) {
      let blob;
      await axios
        .get("ordenpdfentregada?idOrden=" + ordenEntregaId, {
          responseType: "blob",
        })
        .then((response) => {
          blob = new Blob([response.data], {
            type: "application/pdf",
          });
        })
        .catch((error) => {});
      let b64 = await this.blobtoB64(blob);
      this.pdf = b64;
      this.$bvModal.show("imprimirpdf");
    },
    crearOrdenEntrega(orden_entrega_id) {
      this.$root.$emit("reiniciarfirma");
      let sePuedeGenerarOrden = false;
      axios
        .get("obtenerordenesparaentregar?idOrden=" + orden_entrega_id)
        .then((response) => {
          this.idOrdenParaEntregar = response.data.data;
          this.idOrdenParaEntregar[0].ordenesTallas.forEach(
            (element, index, array) => {
              if (
                element.cantidad_orden_restantes > 0 ||
                element.cantidad_orden_restantes == null
              ) {
                sePuedeGenerarOrden = true;
              }
            }
          );
          if (!sePuedeGenerarOrden) {
            Swal.fire({
              icon: "success",
              text: "Ya se entrego todo el material",
            });
            return false;
          }
          this.$bvModal.show("crearorden");
        })
        .catch(() => {
          Swal.fire({
            icon: "error",
            text: "No hay información para esta orden...",
          });
        });
    },
    informacionOrdenes(orden_entrega_id) {
      axios
        .get("obtenerordenesparaentregar?idOrden=" + orden_entrega_id)
        .then((response) => {
          this.informacionOrdenImagenes = response.data.data;
        })
        .catch(() => {});
    },
    obtenerOrdenesMaquila() {
      let _this = this;
      Swal.fire({
        title: "Espere por favor...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        didOpen: () => {
          Swal.showLoading();
        },
      }).then((result) => {});
      axios
        .get("obtenerordenesmaquila")
        .then((response) => {
          Object.keys(response.data).map(function (key, index) {
            let ordenesEntregadas = response.data[key].ordenesEntregadas;
            // ordenesEntregadas
            ordenesEntregadas.forEach(function (valor, indice, array) {
              let fechaCreacionEntregada =
                response.data[key].ordenesEntregadas[indice].fecha_creacion;
              response.data[key].ordenesEntregadas[
                indice
              ].fecha_creacion = _this.utcToTimeZoneFrontEnd(
                fechaCreacionEntregada
              );
            });
            let fechaCreacion = response.data[key].fecha_creacion;
            response.data[key].fecha_creacion = _this.utcToTimeZoneFrontEnd(
              fechaCreacion
            );
            response.data[key].fecha_entrega = moment(
              response.data[key].fecha_entrega
            )
              .local()
              .format("LLLL");
          });
          this.items = response.data;
          Swal.close();
        })
        .catch((error) => {
          window.location.href = "ordenesmaquila";
        });
    },
    utcToTimeZoneFrontEnd(utc) {
      let tiempoUtc = utc.split(/[- :]/);
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
      return tiempoLocal;
    },
  },
};
</script>
<style scoped>
.exepcionp {
  margin-top: 10px;
}
.nohayentregadas {
  display: flex;
  justify-content: center;
  /* align-items: center; */
  background: rgba(255, 190, 70, 0.808);
  padding: 10px;
  margin: 10px;
  border-radius: 8px;
}
.primerafila {
  background: rgb(214, 214, 214);
  padding: 10px;
  margin: 10px;
  border-radius: 8px;
}
.dropdown {
  margin: 20px 0px 20px 0px;
}
.botonesorden {
  margin: 5px;
}
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
