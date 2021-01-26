<template>
  <div class="container">
    <b-modal title="Crear orden de entrega" id="crearorden" size="xl">
      <p>{{ idOrdenMaquila[0].listaOrdenada.folio_id }}</p>
      <b-row>
        <b-col>
          <table
            id="tablaParaEntregar"
            ref="tablaParaEntregar"
            v-show="!firmarYa"
          >
            <tbody>
              <template
                v-for="(asFor, index) in idOrdenMaquila[0].listaOrdenada"
              >
                <tr class="filasinput" :key="asFor.id">
                  <template v-for="(asForColumna, indexColumna) in asFor">
                    <div :key="asForColumna.id" v-if="index == 0">
                      <b-form-input
                        :id="asForColumna"
                        :value="asForColumna"
                        :ref="asForColumna"
                        @change="
                          validarCantidadPorEntregar(asForColumna, $event)
                        "
                        disabled
                      ></b-form-input>
                    </div>
                    <div
                      :key="asForColumna"
                      v-else-if="indexColumna == 0 || indexColumna == 1"
                    >
                      <b-form-input
                        :id="asForColumna"
                        :value="asForColumna"
                        :ref="asForColumna"
                        @change="
                          validarCantidadPorEntregar(asForColumna, $event)
                        "
                        disabled
                      ></b-form-input>
                    </div>
                    <div v-else :key="asForColumna">
                      <b-form-input
                        :id="asForColumna"
                        :ref="asForColumna"
                        type="number"
                        :value="0"
                        @change="
                          validarCantidadPorEntregar(asForColumna, asForColumna)
                        "
                      ></b-form-input>
                    </div>
                  </template>
                </tr>
              </template>
            </tbody>
          </table>
        </b-col>
      </b-row>

      <b-row class="text-center">
        <b-col>
          <b-button
            v-show="!firmarYa"
            size="sm"
            variant="primary"
            class="botonesmargen"
            @click="avanzarFirma(firmarYa)"
          >
            Pasar a firmar la orden
          </b-button>
          <b-button
            v-show="firmarYa"
            size="sm"
            variant="primary"
            class="botonesmargen"
            @click="avanzarFirma(firmarYa)"
          >
            Cambiar cantidades a entregar
          </b-button>
        </b-col>
      </b-row>
      <b-row class="text-center">
        <VueSignaturePad
          v-show="firmarYa"
          style="display: none"
          id="signature"
          width="100%"
          height="300px"
          ref="signaturePad"
          :options="{
            onBegin: () => {
              $refs.signaturePad.resizeCanvas();
            },
          }"
        />
      </b-row>
      <b-row class="text-center">
        <b-col>
          <b-button
            v-show="firmarYa"
            size="sm"
            variant="primary"
            class="botonesmargen"
            @click="undo"
            >Limpiar</b-button
          >
        </b-col>
      </b-row>
      <template #modal-footer="{ ok, cancel, hide }">
        <b-button size="sm" variant="danger" @click="cancel()">
          Cancelar
        </b-button>
        <b-button size="sm" variant="success" @click="guardarOrdenEntrega()">
          Guardad orden de entrega</b-button
        >
      </template>
    </b-modal>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import Vue from "vue";
import VueSignaturePad from "vue-signature-pad";

Vue.use(VueSignaturePad);
export default {
  name: "crearOrdenEntrega",
  props: ["idOrdenMaquila", "reiniciarFirmas"],
  created: function () {
    console.log("se creo el componente orden de entrega...");
  },
  methods: {
    async guardarOrdenEntrega() {
      var awaitTest = await this.save();
      if (!awaitTest) {
        Swal.fire({ icon: "error", text: "Por favor firme la orden!" });
        return false;
      } else {
      }
      console.log(awaitTest);
      console.log("after all");
      let filas = this.$refs.tablaParaEntregar.rows;
      let marca_id = this.idOrdenMaquila[0].marca_id;
      let orden_entrega = this.idOrdenMaquila[0].orden_entrega_id;
      let modelo_id = this.idOrdenMaquila[0].modelo_id;
      let folio_id = this.idOrdenMaquila[0].folio_id;
      let prenda_id = this.idOrdenMaquila[0].prenda_id;
      let muestra_original = this.idOrdenMaquila[0].muestra_original;
      let muestra_referencia = this.idOrdenMaquila[0].muestra_referencia;
      let usuario_id = this.idOrdenMaquila[0].usuario_id;

      let textoParaCortar = this.firma.split(",");
      let tipoArchivo = textoParaCortar[0];
      // this.subirImagen(this.image.dataUrl, this.idOrdenMaquila, tipoArchivo);

      let tallasActualizar = {
        marcaId: marca_id,
        firma: this.firma,
        tipoArchivo: tipoArchivo,
        modelo: modelo_id,
        prenda: prenda_id,
        folioId: folio_id,
        ordenEntregaId: orden_entrega,
        muestraOriginal: muestra_original,
        referencia: muestra_referencia,
        usuarioId: usuario_id,
        tallas_actualizar: [],
        filasOrdenes: [],
      };
      Array.from(filas).forEach(function (elementR, indexR) {
        let columnas = elementR.childNodes;
        Array.from(columnas).forEach(function (elementC, indexC) {
          if (indexR > 0 && indexC > 1) {
            let talla = {
              talla: filas[0].childNodes[indexC].firstChild.value,
              coordinado: filas[indexR].childNodes[0].firstChild.value,
              color: filas[indexR].childNodes[1].firstChild.value,
              tallaActualizar: elementC.firstChild.id,
              cantidad: elementC.firstChild.value,
            };
            tallasActualizar["tallas_actualizar"].push(talla);
          }
        });
      });
      let porFilas = [];
      var fila = {};
      Array.from(filas).forEach(function (elementR, indexR) {
        if (indexR > 0) {
          let columnas = elementR.childNodes;
          fila = {
            coordinado: columnas[0].firstChild.value,
            color: columnas[1].firstChild.value,
          };
          Array.from(columnas).forEach(function (elementC, indexC) {
            if (indexC > 1) {
              fila[filas[0].childNodes[indexC].firstChild.value] =
                columnas[indexC].firstChild.value;
              // console.log(fila);
            }
          });
          porFilas.push(fila);
        }
      });
      tallasActualizar["filasOrdenes"] = porFilas;

      Swal.fire({
        title: "Espere por favor...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        didOpen: () => {
          Swal.showLoading();
        },
      }).then((result) => {});
      console.log("after all");
      axios
        .post("actualizarTallas", tallasActualizar)
        .then((response) => {
          Swal.close();
          Swal.fire({
            icon: "success",
            text: "Se guardo la orden de entrega correctamente...",
            showConfirmButton: true,
            timer: 2000,
          }).then(() => {
            console.log("la response");
            console.log(response);
            this.$parent.obtenerOrdenesMaquila();
            console.log("parent");
          });
        })
        .catch();
      console.log("after all");
    },
    avanzarFirma(firmarYa) {
      console.log("before error");
      this.firmarYa = !this.firmarYa;
      let filas = document.getElementById("tablaParaEntregar").rows.length;
      for (let index = 1; index < filas; index++) {
        let columnas = document
          .getElementById("tablaParaEntregar")
          .rows[index].getElementsByTagName("div").length;
        for (let indexColumna = 2; indexColumna < columnas; indexColumna++) {
          console.log("el value 2.0");
          let refss = document
            .getElementById("tablaParaEntregar")
            .rows[index].getElementsByTagName("div")
            [indexColumna].getElementsByTagName("input")[0].id;
          // console.log(this.$refs[refss][0].$refs.input.value);
        }
      }
    },
    undo() {
      this.$refs.signaturePad.undoSignature();
    },
    save() {
      return new Promise((resolve) => {
        const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
        console.log("save image 2.01");
        console.log(isEmpty);
        console.log(data);
        console.log(this.firma);
        this.firma = "";
        var _this = this;
        if (isEmpty) {
          console.log("esta vacio y el swal??");
          console.log("esta vacio y el swal??22");
          resolve(false);
        } else {
          console.log(data);
          console.log("frimandno all ok");
          console.log(_this.firma);
          _this.firma = data;
          console.log(_this.firma);
          resolve(true);
        }
      });
    },
    validarCantidadPorEntregar(idOrdenTalla, value) {
      Swal.fire({
        title: "Validando, espere...",
        showConfirmButton: false,
        onBeforeOpen() {
          Swal.showLoading();
        },
        onAfterColes() {},
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
      });
      axios
        .get("obtenernumerotallas?ordenTalla=" + idOrdenTalla)
        .then((response) => {
          let totalPorEntregar = 0;
          let icono = "";
          let texto = "";
          if (
            response.data.data.cantidad_orden ==
            response.data.data.cantidad_orden_entregadas
          ) {
            //Ya entregaste todo
            icono = "success";
            texto = "Ya entregaste todo el material";
            totalPorEntregar = 0;
            console.log("Todo entregado");
          } else if (
            response.data.data.cantidad_orden >
            response.data.data.cantidad_orden_entregadas
          ) {
            icono = "success";
            texto = "Puedes entregar esta cantidad de material";
            let maximasPorEntregar =
              response.data.data.cantidad_orden -
              response.data.data.cantidad_orden_entregadas;
            if (this.$refs[value][0]._data.localValue > maximasPorEntregar) {
              this.$refs[value][0]._data.localValue = maximasPorEntregar;
              texto = "Lo maximo que puedes entregar es: " + maximasPorEntregar;
            }
            totalPorEntregar = this.$refs[value][0]._data.localValue;
          } else if (
            response.data.data.cantidad_orden <
            response.data.data.cantidad_orden_entregadas
          ) {
            icono = "error";
            texto = "Te excediste en la entrega de material, se reacomodara";
            totalPorEntregar =
              response.data.data.cantidad_orden -
              response.data.data.cantidad_orden_entregadas;
            totalPorEntregar = 0;
            console.log("Te excedes...");
          }
          this.$refs[value][0]._data.localValue = totalPorEntregar;
          console.log("Lo que puedo entregar...");
          Swal.fire({
            icon: icono,
            text: texto,
            showConfirmButton: true,
            timer: 2000,
          }).then(() => {
            Swal.close;
          });
        })
        .catch((error) => {
          Swal.close;
          Swal.fire({
            icon: "error",
            text: "Hubo un error...",
            showConfirmButton: false,
            timer: 2000,
          }).then(() => {
            Swal.close;
          });
        });
    },
    crearOrdenEntrega() {
      this.$emit("sendData", this.idOrdenParaEntregar);
      console.log("Crear orden entrega...");
      console.log(this.idOrdenMaquila);
      console.log(this.idOrdenParaEntregar);
    },
  },
  data() {
    return {
      firmarYa: 1,
      firma: "",
    };
  },
  created: function () {
    console.log("Component created crearordenentrega....");
  },
  mounted() {
    console.log("Component mounted crear orden.");
    this.$root.$on("reiniciarfirma", () => {
      this.firmarYa = this.reiniciarFirmas;
      console.log(this.firmarYa);
    });
  },
};
</script>
<style>
.filasinput {
  display: flex;
}
#ordenes {
  border-style: solid;
  border-color: rgb(199, 199, 199);
  margin: 5px;
}
</style>
<style>
@media (min-width: 992px) {
  .modal-dialog >>> .modal-lg,
  .modal-xl {
    max-width: 100%;
  }
}
.botonesmargen {
  margin-top: 5px;
  margin-bottom: 5px;
}
</style>
