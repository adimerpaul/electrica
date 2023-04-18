<template>
  <div class="q-pa-md">
    <div class="row">
      <div class="col-3"><q-input outlined v-model="nrocompra" type="text" label="NRO COMPRA" /></div>
      <div class="col-3"><q-select outlined  v-model="material" :options="materiales" label="MATERIALES" /></div>
      <div class="col-2"><q-btn color="green" icon="search" label="Buscar" @click="misdatos" /></div>
      <div class="col-2"><q-btn color="info" icon="print"  @click="printfull" /></div>
      <div class="col-2"><q-btn color="red" icon="delete_sweep"  @click="baja" /></div>
    </div>
    <q-table :filter="filter" title="LISTA DE INVENTARIO" :data="data" :columns="columns"
    row-key="id" :rows-per-page-options="[50,100]"
    :selected-rows-label="getSelectedString"
    selection="multiple"
    :selected.sync="selected"
     >

      <template v-slot:top-right>
        <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:body-cell-opcion="props">
          <q-td key="opcion" :props="props">
            <q-btn color="info" icon="qr_code_2" dense @click="qrPrinttodo(props.row)" />
          </q-td>

      </template>
      <template v-slot:body-cell-nombre="props">
          <q-td key="nombre" :props="props">
            {{props.row.material.nombre }}
          </q-td>

      </template>
    </q-table>
    <div id="myelement" class="hidden"></div>

  </div>
</template>

<script>
import { date } from 'quasar'
import QRCode from 'qrcode'
import {Printd} from "printd";
const { addToDate } = date
export default {
  data() {
    return {
      selected:[],
      alert: false,
      dialog_mod: false,
      dialog_del: false,
      nrocompra:'',
      filter:'',
      dato: {codificar:'NO'},
      model:'',
      dato2: {},
      options: [],
      props: [],
      modelpermiso:false,
      imgqr:{},
      uni:{},
      columns: [
      {name: "codigo", align: "left", label: "CODIGO ", field: "codigo", sortable: true,},
      {name: "nombre", align: "left", label: "NOMBRE ", field: "nombre", sortable: true,},
      {name: "cantidad", align: "left", label: "CANTIDAD ", field: "cantidad", sortable: true,},
      {name: "num", align: "left", label: "NUMERO ", field: "num", sortable: true,},
      {name: "gestion", align: "left", label: "GESTION ", field: "gestion", sortable: true,},
      {name: "estado", align: "left", label: "ESTADO ", field: "estado", sortable: true,},
      { name: "opcion", label: "OPCIÓN", field: "opcion", sortable: false },
      ],
      materiales:[],
      url: process.env.API,
      material:{},
      data: [],
      opts : {
        errorCorrectionLevel: 'M',
        type: 'png',
        quality: 0.95,
        width: 100,
        margin: 1,
        color: {
          dark: '#000000',
          light: '#FFF',
        },
      }
    };
  },
  created() {
    // if (!this.$store.state.login.boolusuario){
    //    this.$router.replace({ path: '/' })
    // }
    this.mismateriales();

    this.misdatos();

  },
  methods: {
    getSelectedString () {
      return this.selected.length === 0 ? '' : `${this.selected.length} record${this.selected.length > 1 ? 's' : ''} selected of ${this.data.length}`
    },
    qrPrint(row){
      // console.log(row)
      QRCode.toDataURL(row.codigo)
        .then(url => {
          this.$q.dialog({
            title: 'Codigo QR',
            message: `<div style="text-align: center"><img src="${url}" style='width:50px;height:50px;'/></div>`,
            html: true,
            ok: false,
            // persistent: true,
            cancel: {
              label: 'Cerrar',
              color: 'primary',
              flat: true,
              noDismiss: true
            }
          })
          // console.log(url)
        })
        .catch(err => {
          console.error(err)
        })
    },
    async qrPrinttodo(row){
      // console.og(row)
    let cadena=''
        this.imgqr= await QRCode.toDataURL(row.codigo,this.opts)
          cadena="<div style=''><img src="+this.imgqr+" style='width:50px;height:50px;padding:5px 0 0 0;margin:0 '/></div> <div style='font-size:4px; padding:0;margin:0;'>"+row.codigo+"</div>"

      document.getElementById('myelement').innerHTML = cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )
    },
   printfull(){
     if(this.selected.length==0)
     return false
      this.$axios.post("generatePdf",this.selected).then(response => {
        console.log(response.data);
        window.open(this.url+'qrFile', '_blank');})
    },
    baja(){
      console.log(this.selected)
      if(this.selected.length==0)
     return false
     this.$q.dialog({
        title: 'Baja Inventario',
        message: 'Cual es el motivo?',
        prompt: {
          model: '',
          type: 'text' // optional
        },
        cancel: true,
        persistent: true
      }).onOk(data => {
        // console.log('>>>> OK, received', data)
        this.$axios.post("bajaInv",{motivo:data,datos:this.selected}).then(response => {
          console.log(response.data)
          this.misdatos()
        })
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })

    },
   async cadenatexto(row){
      this.imgqr= await QRCode.toDataURL(row.codigo,this.opts)
          return "<div><img src="+this.imgqr+" style='width:50px;height:50px;'/></div>"
    },

    mismateriales() {
        this.$q.loading.show();
        this.$axios.get("material").then((res) => {
          console.log(res.data)
          res.data.forEach(r => {
            r.label=r.nombre
          });
          this.materiales = res.data;
          this.$q.loading.hide();
          this.material=this.materiales[0]
        })
      },
    misdatos() {
      this.$q.loading.show();
      this.$axios.post("buscarInv",{compra:this.nrocompra,material_id:this.material.id}).then((res) => {
        console.log(res.data)
        this.data = res.data;
        this.$q.loading.hide();
      });
    },

  },
};
</script>
