<template>
  <div class="container">
    <b-modal id="subirimagenes" size="xl">
      <b-row>
        <b-col>
          <p>lol 2.0</p>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <template>
            <image-uploader
              :debug="1"
              :maxWidth="512"
              :quality="0.7"
              :autoRotate="true"
              outputFormat="verbose"
              :preview="false"
              :className="['Array', { 'fileinput--loaded': hasImage }]"
              capture="false"
              accept="video/*,image/*"
              doNotResize="['gif', 'svg']"
              @input="setImage"
              @onUpload="startImageResize"
              @onComplete="endImageResize"
            ></image-uploader>
          </template>

          <div class="preview">
            <p></p>
            <div v-for="recorrofotos in imagenes" v-bind:key="recorrofotos">
              <div class="contenedorsubir">
                <img
                  class="ajusteimagenes"
                  :src="recorrofotos.imagen_completa"
                  :id="recorrofotos.imagen_orden_id"
                />
                <!-- <b-buttonrecorrofotos.id.dataUrl, @click="eliminarImagen(1)" class="botonencima"
                  >Eliminar</b-buttonrecorrofotos.id.dataUrl,
                > -->
                <b-button
                  @click="eliminarImagen(recorrofotos.imagen_orden_id)"
                  class="botonencima"
                  >Eliminar imagen x2</b-button
                >
              </div>
            </div>
          </div>
        </b-col>
      </b-row>
      <b-row class="text-center">
        <b-col> </b-col>
      </b-row>
      <b-row class="text-center"> </b-row>
      <b-row class="text-center">
        <b-col> </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import Vue from "vue";
import VueSignaturePad from "vue-signature-pad";
import ImageUploader from "vue-image-upload-resize";
import UploadImage from "vue-upload-image";
// register globally
Vue.use(ImageUploader);
Vue.use(VueSignaturePad);

export default {
  name: "crearOrdenEntrega",
  props: ["idOrdenMaquila", "imagenes"],
  created: function () {
    console.log("se creo el componente orden de entrega...");
  },
  methods: {
    eliminarImagen(imagenOrdenId) {
      // console.log(this.$refs);
      // console.log(idOrden);
      console.log("loggin");
      axios
        .get("eliminarimagen?imagenOrdenId=" + imagenOrdenId)
        .then((response) => {
          console.log("Esperando elimnar la imagen");
          console.log(response.data.data);
          this.$parent.obtenerImagenes(this.idOrdenMaquila);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    b64toBlob(b64Data, contentType = "", sliceSize = 512) {
      return new Promise((resolve) => {
        const byteChar = atob(b64Data);
        const byteArrays = [];
        for (let offset = 0; offset < byteChar.length; offset += sliceSize) {
          const slice = byteChar.slice(offset, offset + sliceSize);
          const byteNumbers = new Array(slice.length);
          for (let i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
          }
          const byteArray = new Uint8Array(byteNumbers);
          byteArrays.push(byteArray);
        }
        const blob = new Blob(byteArrays, { type: contentType });
        resolve(blob);
      });
    },
    blobtoB64(blob) {
      return new Promise((resolve) => {
        var reader = new FileReader();
        var base64data;
        reader.readAsDataURL(blob);
        reader.onloadend = function () {
          base64data = reader.result;
          console.log("Promise 64x");
          console.log(base64data);
          resolve(base64data);
        };
      });
    },
    async subirImagen(dataUrl, idOrden, tipoArchivo) {
      console.log(dataUrl);
      console.log(idOrden);
      const formData = new FormData();
      formData.append("imagen", dataUrl);
      formData.append("idOrden", idOrden);
      formData.append("tipoArchivo", tipoArchivo);
      axios
        .post("guardarimagen", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          console.log("Esperando guardar imagen");
          console.log(response.data.data);
          this.$parent.obtenerImagenes(this.idOrdenMaquila);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    // eliminarImagen(idImagen) {
    //   let anything = atob("lolito");
    //   console.log("any2x");
    //   console.log(anything);
    //   let bas6 = "xxx";
    //   var decode = atob("sss");
    //   var blob = new Blob([decode], { type: "lol" });
    //   var reader = new FileReader();
    //   reader.readAsDataURL(blob);
    //   reader.onloadend = function () {
    //     var base = reader.result;
    //     console.log("base 64");
    //     console.log(base);
    //   };
    //   alert("ss");
    // },
    setImage: function (file) {
      this.hasImage = true;
      this.image = file;
      let objFoto = { id: file };
      this.fotos.push(objFoto);
      console.log("le foto...");
      console.log(this.image.dataUrl);
      let textoParaCortar = this.image.dataUrl.split(",");
      let tipoArchivo = textoParaCortar[0];
      console.log("arrya cortado");
      console.log(tipoArchivo);
      console.log("el id");
      console.log(this.idOrdenMaquila);
      this.subirImagen(this.image.dataUrl, this.idOrdenMaquila, tipoArchivo);
    },
    guardarOrdenEntrega() {
      Swal.fire({
        icon: "success",
        text: "Se guardo la orden de entrega correctamente...",
        showConfirmButton: true,
        timer: 2000,
      }).then(() => {
        this.$bvModal.hide("crearorden");
      });
    },
    avanzarFirma(firmarYa) {
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
          console.log(this.$refs[refss][0].$refs.input.value);
        }
      }
    },
    undo() {
      this.$refs.signaturePad.undoSignature();
    },
    save() {
      const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
      console.log("save image");
      console.log(isEmpty);
      console.log(data);
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
          console.log("Validando la cantidad de entrega...");
          console.log(response.data.data);
          console.log(response.data.data.cantidad_orden);
          console.log(response.data.data.cantidad_orden_entregadas);
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
          console.log("el error");
          console.log(error);
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
    },
  },
  data() {
    return {
      firmarYa: 1,
      fotos: [],
    };
  },
  created: function () {
    console.log("Component created crearordenentrega....");
    console.log(this.idOrdenMaquila);
  },
  mounted() {
    console.log("Component mounted crear orden.");
    console.log(this.idOrdenMaquila);
  },
};
</script>
<style>
.contenedorsubir {
  margin: 20px;
  position: relative;
  width: 90%;
}
.preview {
  display: flex;
  flex-wrap: wrap;
}
.ajusteimagenes {
  width: 100%;
}
.botonencima {
  position: absolute;
  top: 0%;
  left: 70%;
}
.filasinput {
  display: flex;
}
#ordenes {
  border-style: solid;
  border-color: rgb(199, 199, 199);
  margin: 5px;
}
</style>
<style scoped>
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
