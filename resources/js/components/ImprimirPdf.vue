<template>
  <div class="container">
    <b-modal title="Pdf orden maquila" id="imprimirpdf" size="xl">
      <p>
        <a
          download="ordenMaquila"
          :href="pdf"
          title="Descarga pdf"
          target="_blank"
          >Descarga Pdf</a
        >
      </p>
      <iframe :src="pdf" id="pdforden"></iframe>
    </b-modal>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import Vue from "vue";
import VueSignaturePad from "vue-signature-pad";
import ImageUploader from "vue-image-upload-resize";
import UploadImage from "vue-upload-image";

Vue.use(ImageUploader);
Vue.use(VueSignaturePad);

export default {
  name: "imprimirPdf",
  props: ["pdf"],
  created: function () {
    console.log("se creo el componente orden de entrega...");
  },
  methods: {
    eliminarImagen(imagenOrdenId) {
      Swal.fire({
        title: "¿Quieres eliminar la imagen?",
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Eliminar`,
        cancelButtonText: "Cancelar",
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          Swal.fire("Saved!", "", "success");
        } else if (result.isDenied) {
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
            .get("eliminarimagen?imagenOrdenId=" + imagenOrdenId)
            .then((response) => {
              Swal.close();
              console.log("Se elimino la imagen");
              Swal.fire({
                icon: "success",
                text: "Se elimino la imagen",
                showConfirmButton: true,
                timer: 2000,
              }).then(() => {});
              console.log("Esperando elimnar la imagen");
              console.log(response.data.data);
              this.$parent.obtenerImagenes(this.idOrdenMaquila);
            })
            .catch((error) => {
              console.log(error);
            });
        }
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
          Swal.close();
          Swal.fire({
            icon: "success",
            text: "Se guardo la imagen",
            showConfirmButton: false,
            timer: 1000,
          }).then(() => {
            this.$parent.obtenerImagenes(this.idOrdenMaquila);
          });
        })
        .catch((error) => {
          console.log(error);
          Swal.close();
          Swal.fire({
            icon: "error",
            text: "Hubo error al guardar la imagen",
            showConfirmButton: true,
            timer: 2000,
          }).then(() => {
            this.$parent.obtenerImagenes(this.idOrdenMaquila);
          });
        });
    },
    setImage: function (file) {
      Swal.fire({
        title: "Espere por favor...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });
      this.hasImage = true;
      this.image = file;
      let objFoto = { id: file };
      this.fotos.push(objFoto);
      let textoParaCortar = this.image.dataUrl.split(",");
      let tipoArchivo = textoParaCortar[0];
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
          let refss = document
            .getElementById("tablaParaEntregar")
            .rows[index].getElementsByTagName("div")
            [indexColumna].getElementsByTagName("input")[0].id;
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
          // console.log("Validando la cantidad de entrega...");
          // console.log(response.data.data);
          // console.log(response.data.data.cantidad_orden);
          // console.log(response.data.data.cantidad_orden_entregadas);
          // console.log(this.$refs[value]);
          // console.log(this.$refs[value][0]);
          // console.log(this.$refs[value][0]._data.localValue);
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
  },
  data() {
    return {
      firmarYa: 1,
      fotos: [],
    };
  },
  created: function () {
    console.log("Component created crearordenentrega....");
  },
  mounted() {
    console.log("Component mounted crear orden.");
  },
};
</script>
<style>
#fileInput {
  padding: 20px;
}
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
  top: 10px;
  left: 75%;
}
.filasinput {
  display: flex;
}
#ordenes {
  border-style: solid;
  border-color: rgb(199, 199, 199);
  margin: 5px;
}
#pdforden {
  width: 100%;
  height: 500px;
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
