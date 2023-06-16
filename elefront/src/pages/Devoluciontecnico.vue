
<template>
<q-page  padding>
  <div class="text-h5" align="center">DEVOLUCION MATERIAL TECNICO</div>
  <div class="col-6">
    <q-form
      @submit.prevent="consultar"
      class="q-gutter-md"
      >
      <div class="row">
      <div class="col-md-6 col-xs-12"><q-select use-input @filter="filterTec" v-model="tecnico" :options="tecnicos" label="Tecnicos" outlined dense /></div>
      <div class="col-md-6 col-xs-12"><q-input v-model="codigo" type="text" label="Codigo Material" autofocus dense outlined/></div>
      <div class="col-md-6 col-xs-12"><q-input v-model="cantidad" type="number" label="Cantidad"  dense outlined/></div>
      <div class="col-3"><q-btn color="primary" icon="check" label="OK" type="submit" /></div>
      </div>
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
        cantidad:1,
        camera: 'auto',
        result: null,
        showScanConfirmation: false,
        info: {},
        correcto:false,
        codigo:'',
        error: '',
        tecnicos:[],
        tecnico:{},
        filtertecnicos:[]
      }
  },
  mounted() {

    this.cargarTecnicos()
  },

  methods: {
    cargarTecnicos(){
      this.$axios.get("listUser").then((res) => {
        res.data.forEach(r => {
          r.label=r.name
        });
        this.tecnicos=res.data
        this.tecnico={label:''}
        this.filtertecnicos=this.tecnicos
      })

    },
    filterTec (val, update) {
        if (val === '') {
          update(() => {
            this.tecnicos = this.filtertecnicos

            // here you have access to "ref" which
            // is the Vue reference of the QSelect
          })
          return
        }

        update(() => {
          const needle = val.toLowerCase()
          this.tecnicos = this.filtertecnicos.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
        })
      },
    onClick(){
      if(this.cantidad<=0 || this.cantidad=='' || this.cantidad==undefined)
        return false
        
      this.$q.dialog({
        title: 'DEVOLUCION MATERIAL',
        message: 'Esta seguro de devolver?',
        cancel: true,
        persistent: false
      }).onOk(() => {
        // console.log('>>>> OK')
      this.$axios.post("devolucionMaterial",{codigo:this.info.inventario.codigo,id:this.info.user_id,cantidad:this.cantidad}).then((res) => {
        console.log(res.data)
        this.correcto=false
        this.info={}
        this.cantidad=1
        this.codigo=''
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
    console.log(this.tecnico)
      if(this.codigo==undefined || this.codigo=='')
        return false
      if(this.tecnico.id==undefined)
        return false
      this.info={}
      this.correcto=false
      this.$axios.post("consultaBodega",{cod:this.codigo,id:this.tecnico.id}).then((res) => {
        console.log(res.data)
        if(res.data!='')
        {this.info=res.data
          this.cantidad=res.data.saldo
          this.info.fecha=date.formatDate(this.info.created_at, "YYYY-MM-DD H:m"),
          this.correcto=true
        }
      })
      //this.codigo=''
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
