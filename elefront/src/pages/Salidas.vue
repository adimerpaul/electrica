<template>
  <q-page padding>
    <div class="text-h5 text-center">SALIDA DE MATERIAL</div>
    <div><q-btn color="green" icon="check" label="Registro" @click="salida={}; dialogReg=true" /></div>
    <div class="row">
      <div class="col-4 q-pa-xs"><q-input dense v-model="fecha" type="date" label="Fecha" outlined/></div>
      <div class="col-4"><q-btn color="green" icon="search" label="Buscar" @click="generarList" /></div>
    </div>
    <div class="col-12">
      <q-table
        title="DATOS DE SALIDA"
        :data="salidas"
        :columns="colsalida"
        row-key="name"
      >
      <template v-slot:body-cell-elementos="props">
              <q-td key="elementos" :props="props">
                <div v-for=" mat in props.row.elementos" :key="mat.id">
                  {{ mat.cantidad }} {{ mat.material }} - {{ mat.inventario.num }}
                </div>

              </q-td>
            </template>
            <template v-slot:body-cell-opcion="props">
              <q-td key="opcion" :props="props">
                <q-btn color="info" icon="print" dense @click="printSalida(props.row)" />
              </q-td>
            </template>
      </q-table>
    </div>
    <q-dialog v-model="dialogReg" full-width>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="shopping_cart" color="primary" text-color="white" dense size="40px"/>
          <span class="q-ml-sm">REGISTRO SALIDA DE MATERIAL</span>
        </q-card-section>
        <q-card-section>
          <div class="row">
          <div class="col-md-6 col-xs-12"><q-select v-model="tecnico" :options="tecnicos" label="Tecnicos" outlined dense /></div>
          <div class="col-md-6 col-xs-12"><q-input v-model="salida.destino" type="text" label="Destino" outlined dense /></div>
          <div class="col-md-6 col-xs-12"><q-input v-model="salida.motivo" type="text" label="Motivo" outlined dense/></div>
          <div class="col-md-6 col-xs-12"><q-select v-model="salida.carro" :options="carros" label="Carro" outlined dense/></div>
          <div class="col-12">
            <q-form
              @submit.prevent="regList"
            >
                <q-input outlined v-model="codigo" type="text" label="Codigo Material"  dense autofocus/>
            </q-form>
          </div>
        </div>
          <div class="col-12">
            <q-table
              title="Detalle"
              :data="detalle"
              :columns="columna"
              row-key="name"
              dense
            >
            <template v-slot:body-cell-opcion="props">
              <q-td key="opcion" :props="props">
                  <q-btn color="red" icon="delete" dense @click="quitarLista(props)"/>
              </q-td>
            </template>
            <template v-slot:body-cell-material="props">
              <q-td key="material" :props="props">
                {{ props.row.material.nombre }}
              </q-td>
            </template>
            </q-table>
          </div>
          <div class="row">
            <div class="col-4"><q-btn dense color="red" icon="cancel" label="CANCELAR"  v-close-popup /></div>
            <div class="col-4"><q-btn dense color="green" icon="check" label="REGISTRAR" @click="registroSalida" /></div>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  <div id="myelement" class="hidden"></div>

  </q-page>
</template>
<script>
import { METHODS } from 'http';
import { Notify } from 'quasar';
import { date } from 'quasar'
import {Printd} from "printd";

export default {
  data() {
    return {
      salida:{},
      fecha:date.formatDate(new Date(),'YYYY-MM-DD'),
      salidas:[],
      tecnico:{},
      tecnicos:[],
      detalle:[],
      columna:[
        {name:'cant',label:'Cantidad',field:'cant'},
        {name:'codigo',label:'codigo',field:'codigo'},
        {name:'material',label:'material',field:'material'},
        {name:'opcion',label:'opcion',field:'opcion'},
      ],
      colsalida:[
        {name:'tecnico',label:'TECNICO',field:row=>row.tecnico.name},
        {name:'carro',label:'CARRO',field:'carro'},
        {name:'destino',label:'DESTINO',field:'destino'},
        {name:'motivo',label:'MOTIVO',field:'motivo'},
        {name:'elementos',label:'MATERIALES',field:'elementos'},
        {name:'opcion',label:'OPCION',field:'opcion'},

      ],
      codigo:'',
      carros:['','CARRO VALDE 1','CARRO VALDE 2','CARRO VALDE 3','CARRO VALDE AZUL','CAMIONETA ROJA','CAMIONETA BLANCA'],
      dialogReg:false,
    }
  },
  created() {
    this.cargarTecnicos()
    this.generarList()
  },
  methods: {
    generarList(){
      if(this.fecha==undefined || this.fecha=='')
        return false
      this.$axios.post("listsalida",{fecha:this.fecha}).then((res) => {
        this.salidas=res.data
      })

    },
    registroSalida(){
      if(this.detalle.length==0){
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: "DEBE REGISTRAR MATERIAL",
        });
        return false
      }
      this.salida.user_id=this.$store.state.login.user.id
      this.salida.tecnico=this.tecnico.id
      this.salida.detalle=this.detalle
      this.$axios.post("salida",this.salida).then((res) => {
        console.log(res.data)
        this.salida={}
        this.tecnico=this.tecnicos[0]
        this.detalle=[]
        this.dialogReg=false
      })

    },
    quitarLista(props){
      console.log(props)
      this.detalle.splice(props.rowIndex,1);
    },
    cargarTecnicos(){
      this.$axios.get("listUser").then((res) => {
        res.data.forEach(r => {
          r.label=r.name
        });
        this.tecnicos=res.data
        this.tecnico=this.tecnicos[0]
      })

    },
    printSalida(salida){
      let contenido=''
      salida.elementos.forEach(r => {
        contenido+='<tr><td>'+r.cantidad+'</td><td>'+r.material+'</td><td>'+r.inventario.codigo+'</td></tr>'
      });
      let cadena="<style>\
      table{\
        width:100%;\
      }\
      .titulo{\
      text-align:center;\
      font-weight:bold;\
      }\
      </style>\
      <div id='myelement'>\
        <table style='padding:5px'><tr><td>\
      <div class='titulo'>SALIDA DE ALMACEN</div>\
      <div><b>FECHA: </b>"+salida.fecha+" "+salida.hora+"</div>\
      <div><b>TECNICO: </b>"+salida.tecnico.name+"</div>\
      <div><b>CARRO: </b>"+salida.carro+"</div>\
      <div><b>DESTINO: </b>"+salida.destino+"</div>\
      <div><b>MOTIVO: </b>"+salida.motivo+"</div><hr>\
      <div class='titulo'><b>DETALLE </b></div>\
      <table style='padding:10px'><tr><th>CANTIDAD</th><th>MATERIAL</th><th>CODIGO</th></tr>"+contenido+"</table>\
        </td><td>\
          <div class='titulo'>SALIDA DE ALMACEN</div>\
      <div><b>FECHA: </b>"+salida.fecha+" "+salida.hora+"</div>\
      <div><b>TECNICO: </b>"+salida.tecnico.name+"</div>\
      <div><b>CARRO: </b>"+salida.carro+"</div>\
      <div><b>DESTINO: </b>"+salida.destino+"</div>\
      <div><b>MOTIVO: </b>"+salida.motivo+"</div><hr>\
      <div class='titulo'><b>DETALLE </b></div>\
      <table style='padding:10px'><tr><th>CANTIDAD</th><th>MATERIAL</th><th>CODIGO</th></tr>"+contenido+"</table></td></tr>"


      cadena+=`</table></div>
      `
      document.getElementById('myelement').innerHTML = cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )
    },
    regList(){
      this.$axios.get("consultaInv/"+this.codigo).then((res) => {
        console.log(res.data)
        if(res.data!=''){
          let valida=this.detalle.find(d=> d.codigo===this.codigo)
          console.log(valida)
          if(valida==undefined){
            res.data.cant=1
            this.detalle.push(res.data)
          }
            else{
              if(valida.cant>=res.data.cantidad)
              {

              }
              else{
                valida.cant+=1
              }
            console.log(valida)

        }
          console.log(this.detalle)
        }
        this.codigo=''
      })

    }
  }

}
</script>
