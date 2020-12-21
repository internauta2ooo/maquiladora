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
      console.log("RUN2");
      console.log(this.$refs[value]);
      axios
        .get("obtenernumerotallas?ordenTalla=" + idOrdenTalla)
        .then((response) => {
          console.log(response.data.data);
          console.log("aaas1");
          Swal.fire({
            icon: "success",
            text: "Puedes entregar la cantidad",
          });
        })
        .catch((error) => {
          console.log("el error");
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
