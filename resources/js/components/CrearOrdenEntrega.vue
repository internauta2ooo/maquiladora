<template>
  <div class="container">
    <p>slpslsls 2.000</p>
    <b-modal id="crearorden" size="xl">
      <div>Mensaje:{{ idOrdenMaquila[0].orden_entrega_id }}</div>
      <table>
        <tbody>
          <template v-for="(asFor, index) in idOrdenMaquila[0].listaOrdenada">
            <tr class="filasinput" :key="asFor">
              <template v-for="(asForColumna, indexColumna) in asFor">
                <div :key="asForColumna" v-if="index == 0">
                  <b-form-input
                    :id="asForColumna"
                    :value="asForColumna"
                    @change="validarCantidadPorEntregar(asForColumna, $event)"
                    disabled="true"
                  ></b-form-input>
                </div>
                <div
                  :key="asForColumna"
                  v-else-if="indexColumna == 0 || indexColumna == 1"
                >
                  <b-form-input
                    :id="asForColumna"
                    :value="asForColumna"
                    @change="validarCantidadPorEntregar(asForColumna, $event)"
                    disabled="true"
                  ></b-form-input>
                </div>
                <div v-else :key="asForColumna">
                  <b-form-input
                    :id="asForColumna"
                    :ref="asForColumna"
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
    </b-modal>
  </div>
</template>

<script>
import Swal from "sweetalert2";
export default {
  name: "crearOrdenEntrega",
  props: ["idOrdenMaquila"],
  methods: {
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
          console.log(response.data.data);
          console.log(response.data.data.cantidad_orden);
          console.log(response.data.data.cantidad_orden_entregadas);
          console.log("Lo que puedo entregar223 1xx...");
          console.log(this.$refs[value]);
          console.log(this.$refs[value][0]);
          console.log(this.$refs[value][0]._data.localValue);
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
            console.log("Valido");
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
            console.log("Te excedes! 778++52558");
          }
          this.$refs[value][0]._data.localValue = totalPorEntregar;
          console.log("Lo que puedo entregar");
          console.log(totalPorEntregar);
          console.log("Lo que puedo entregar");
          console.log(this.$refs[value][0].value);
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
          console.log("el error");
          console.log(error);
          Swal.close;
          Swal.fire({
            icon: "error",
            text: "Hubo un error...",
            showConfirmButton: false,
            timer: 2500,
          }).then(() => {
            Swal.close;
          });
        });
    },
    crearOrdenEntrega() {
      this.$emit("sendData", this.idOrdenParaEntregar);
      console.log("ssksskk11");
      console.log(this.idOrdenMaquila);
      console.log("ssksskk111");
    },
  },
  data() {
    return {};
  },
  created: function () {
    console.log("Component created.");
    console.log(this.idOrdenMaquila);
  },
  mounted() {
    console.log("Component mounted.");
    console.log(this.idOrdenMaquila);
  },
};
</script>
<style scoped>
.filasinput {
  display: flex;
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
