
<template>
<q-page  padding>
  <div class="text-h5" align="center">DEVOLUCION MATERIAL TECNICO</div>
  <div class="col-6">
    <q-form
      @submit.prevent="consultar"
      class="q-gutter-md"
      >
      <div class="col-6"><q-input v-model="codigo" type="text" label="Codigo Material" autofocus dense outlined/></div>

            <div>
      </div>
    </q-form>
   </div>
    <template v-if="correcto">
   <div class="row">
   <div class="col-6">
    <table>
      <tr><th>Tecnico</th><td>{{ info.user.name }}</td></tr>
      <tr><th>Codigo </th><td>{{ info.inventario.codigo}}</td></tr>
      <tr><th>Material</th><td>{{ info.material.nombre }}</td></tr>
      <tr><th>Cantidad</th><td>{{ info.saldo }}</td></tr>
      <tr><th>Fecha</th><td>{{ info.fecha }}</td></tr>
    </table>

   </div>
   <div class="col-6">
    <q-btn color="primary" icon="keyboard_return  " label="DEVOLVER" @click="onClick" />
   </div>
  </div>
</template>

</q-page>
</template>

<script>
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'
import {date} from "quasar"

export default {
  components: {

  },
  data() {
      return {
        isLoading: true,
        camera: 'auto',
        result: null,
        showScanConfirmation: false,
        info: {},
        correcto:false,
        codigo:'',
        error: ''
      }
  },
  mounted() {


  },
  methods: {
    onClick(){
      this.$q.dialog({
        title: 'DEVOULCION MATERIAL',
        message: 'Esta seguro de devolver?',
        cancel: true,
        persistent: false
      }).onOk(() => {
        // console.log('>>>> OK')
      this.$axios.post("devolucionMaterial",{codigo:this.info.inventario.codigo}).then((res) => {
        console.log(res.data)
        this.correcto=false
        this.info={}
      })
      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    },
    onDecode (decodedString) {
    // ...
    console.log(decodedString)
    this.codigo=decodedString
    this.consultar()
  },
   consultar(){
      if(this.codigo==undefined || this.codigo=='')
        return false
      this.info={}
      this.correcto=false
      this.$axios.get("consultaBodega/"+this.codigo).then((res) => {
        console.log(res.data)
        if(res.data!='')
        {this.info=res.data
          this.info.fecha=date.formatDate(this.info.created_at, "YYYY-MM-DD H:m"),
          this.correcto=true
        }
      })
      this.codigo=''
    }
  }
}
</script>

<style>
table{
  width: 100%;

}
th{text-align: right;}
td{text-align: left;}
</style>
