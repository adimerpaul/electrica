<template>
  <q-page padding>
    <div class="text-h5 text-center">SALIDA DE MATERIAL</div>
    <div class="row">
      <div class="col-3 q-pa-xs"><q-input dense v-model="fecha" type="date" label="Fecha" outlined/></div>
      <div class="col-3 q-pa-xs"><q-input dense v-model="fecha2" type="date" label="Fecha2" outlined/></div>
      <div class="col-3 q-pa-xs"><q-select outlined dense use-input @filter="filterMat" v-model="material" :options="materiales" label="Material"  /></div>
      <div class="col-3 q-pa-xs"><q-btn color="green" icon="search" label="Buscar" @click="generarList" /></div>
    </div>
    <div class="col-12">
      <q-table
        title="DATOS DE SALIDA"
        :data="salidas"
        :columns="colsalida"
        row-key="name"
        :filter="filter"
      >
      <template v-slot:top-right>
        <q-btn dense color="green" icon="save_alt" label="EXCEL" @click="descarga" v-if="salidas.length>0"/>
        <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>

      </q-table>
    </div>
    <br>
    <div class="row">
      <div class="col-6 q-pa-xs"><q-select outlined dense use-input @filter="filterTec" v-model="tecnico" :options="tecnicos" label="Tecnicos"  /></div>
      <div class="col-6 q-pa-xs"><q-btn dense color="info" icon="search"  @click="listSaldo" /></div>

    </div>
    <div class="col-12">
      <q-table
        title="Lista material del Tecnico"
        :data="saldo"
        row-key="name"
        :filter="filterbuscar"
      >
      <template v-slot:top-right>
        <q-btn dense color="green" icon="save_alt" label="EXCEL" @click="desPendiente" v-if="saldo.length>0"/>
        <q-input outlined dense debounce="300" v-model="filterbuscar" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      </q-table>

    </div>



  <div id="myelement" class="hidden"></div>

  </q-page>
</template>
<script>
import { METHODS } from 'http';
import { Notify } from 'quasar';
import { date } from 'quasar'
import {Printd} from "printd";
import xlsx from "json-as-xlsx"

export default {
  data() {
    return {
      filter:'',
      salida:{},
      cantidad:1,
      material:{label:''},
      fecha:date.formatDate(new Date(),'YYYY-MM-DD'),
      fecha2:date.formatDate(new Date(),'YYYY-MM-DD'),
      salidas:[],
      materiales:[],
      filterMateriales:[],
      tecnico:{label:''},
      filtertecnicos:[],
      tecnicos:[],
      user:{},
      detalle:[],
      saldo:[],
      filterbuscar:'',
      colsalida:[
        {name:'fecha',label:'FECHA',field:'fecha'},
        {name:'destino',label:'DESTINO',field:'destino'},
        {name:'motivo',label:'MOTIVO',field:'motivo'},
        {name:'carro',label:'CARRO',field:'carro'},
        {name:'tecnico',label:'TECNICO',field:'name'},
        {name:'cantidad',label:'CANTIDAD',field:'cantidad'},
        {name:'material',label:'MATERIAL',field:'material'},
        {name:'codigo',label:'CODIGO',field:'codigo'},

      ],
      codigo:'',
      carros:['','CARRO VALDE 1','CARRO VALDE 2','CARRO VALDE 3','CARRO VALDE AZUL','CAMIONETA ROJA','CAMIONETA BLANCA','CARRO DE TRAFICO','C Dependencia','C Parques y Plazas','C Posteadores'],
      dialogReg:false,
    }
  },
  created() {
    this.getTecnico()
    this.getMaterial()
    this.generarList()
  },
  methods: {
    desPendiente(){
      if(this.saldo.length<1)
        return false

        this.$q.loading.show();
        let dataimp = [
  {
    sheet: "Material Pendiente",
    columns: [
        {label:'FECHA',value:'fecha'},
        {label:'MATERIAL',value:'material'},
        {label:'CODIGO',value:'codigo'},
        {label:'SALDO',value:'saldo'},
    ],
    content: this.saldo
  },

]

let settings = {
  fileName: "Reporte-"+this.user.name, // Name of the resulting spreadsheet
  extraLength: 6, // A bigger number means that columns will be wider
  writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
}
  this.$q.loading.hide()
xlsx(dataimp, settings) // Will download the excel file

    },
    descarga(){
      if(this.salidas.length<1)
        return false

        this.$q.loading.show();
        let dataimp = [
  {
    sheet: "Salida Material",
    columns: [
        {label:'FECHA',value:'fecha'},
        {label:'DESTINO',value:'destino'},
        {label:'MOTIVO',value:'motivo'},
        {label:'CARRO',value:'carro'},
        {label:'TECNICO',value:'name'},
        {label:'CANTIDAD',value:'cantidad'},
        {label:'MATERIAL',value:'material'},
        {label:'CODIGO',value:'codigo'},
    ],
    content: this.salidas
  },

]

let settings = {
  fileName: "ReporteMaterial", // Name of the resulting spreadsheet
  extraLength: 6, // A bigger number means that columns will be wider
  writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
}
  this.$q.loading.hide()
xlsx(dataimp, settings) // Will download the excel file

    },
          filterTec (val, update) {
        if (val === '') {
          update(() => {
            this.tecnicos = this.filterTecnicos

            // here you have access to "ref" which
            // is the Vue reference of the QSelect
          })
          return
        }

        update(() => {
          const needle = val.toLowerCase()
          this.tecnicos = this.filterTecnicos.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
        })
      },
        getTecnico(){
      this.tecnicos=[]
      this.$axios.get("listUser").then((res) => {
        console.log(res.data)
        res.data.forEach(r => {
          r.label=r.name
          this.tecnicos.push(r)
        })
        this.filterTecnicos=this.tecnicos
      })

    },
    printReport(){
      this.$axios.post("reporteMaterial",{ini:this.fecha,fin:this.fecha2}).then((res) => {
        console.log(res.data)
        if(res.data.length>0)
        {
          let cadena="<style>\
      *{font-size:10px;}\
      .table1{\
        width:100%;\
        border-collapse: collapse;\
      }\
      table, th, td {\
        border: 1px solid;\
      }\
      .titulo{\
      text-align:center;\
      font-weight:bold;\
      }\
      </style>\
      <div id='myelement'>\
      <div class='titulo'>SALIDA DE MATERIAL DE "+this.fecha+" AL "+this.fecha2+"</div> <br>\
      <table class='table1'><tr><th>CODIGO</th> <th>CANTIDAD</th><th>MATERIAL</th><th>STOCK_ALMACEN</th><tr>"

      res.data.forEach(r => {

        cadena+='<tr><td>'+r.codigo+'</td><td>'+r.cantidad+'</td><td>'+r.material+'</td><td>'+r.almacen+'</td></tr> '

      })
      cadena+="</table></div>"

      document.getElementById('myelement').innerHTML = cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )
        }
      })

    },
    listSaldo(){
      if(this.tecnico.id==undefined)
        return false
      this.saldo=[]
      this.$axios.post("materialTecnico/"+this.tecnico.id).then((res) => {
          this.saldo=res.data
          this.user=this.tecnico
        })

    },
    onclick(){
      if(this.material.id==undefined){
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: "DEBE REGISTRAR MATERIAL"
        })
        return false
      }
      if(this.cantidad<=0 || this.cantidad=='' || this.cantidad==undefined){
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: "DEBE Ingresar cantidad correcta"
        })
        return false
      }
      if(this.material.stock < this.cantidad){
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: "No ay Suficiente Material",
        });
        return false
      }
      this.material.cantidad=this.cantidad
      this.$q.loading.show()
      this.$axios.post("inventDisponible",this.material).then((res) => {
        console.log(res.data)
          if( this.material.codificar=='SI'){
            res.data.forEach(r => {
              let valida=this.detalle.find(d=> d.codigo==r.codigo)
                if(valida==undefined){
                r.cant=1
              this.detalle.push(r)
            }
            })
          }
          else{

              let valida=this.detalle.find(d=> d.codigo===res.data.codigo)
              console.log(valida)
                if(valida==undefined){
                  res.data.cant=this.cantidad
                  this.detalle.push(res.data)
                }
                else{
                  if((valida.cant + parseInt(this.cantidad)) <= this.material.stock)
                  {
                    valida.cant =parseInt(valida.cant) + parseInt(this.cantidad)
                  }
                }

        }
        this.$q.loading.hide()

          console.log(this.detalle)

      }).catch(error=>{
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: error.message
        })
      })

    },
    filterMat (val, update) {
        if (val === '') {
          update(() => {
            this.materiales = this.filterMateriales

            // here you have access to "ref" which
            // is the Vue reference of the QSelect
          })
          return
        }

        update(() => {
          const needle = val.toLowerCase()
          this.materiales = this.filterMateriales.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
        })
      },
    getMaterial(){
      this.materiales=[]
      this.$axios.get("material").then((res) => {
        res.data.forEach(r => {
          r.label=r.nombre
          this.materiales.push(r)
        })
        this.filterMateriales=this.materiales
      })

    },
    generarList(){
      if(this.fecha==undefined || this.fecha=='')
        return false
      if(this.fecha2==undefined || this.fecha2=='')
        return false
      if(this.material.id==undefined)
        return false
      this.$axios.post("reportEntregaMat",{ini:this.fecha,fin:this.fecha2,material_id:this.material.id}).then((res) => {
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
      if(this.tecnico.id==undefined){
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: "Debe Seleccioanr tecnico",
        });
        return false
      }
      this.salida.user_id=this.$store.state.login.user.id
      this.salida.tecnico=this.tecnico.id
      this.salida.detalle=this.detalle
      this.$q.loading.show()

      this.$axios.post("salida",this.salida).then((res) => {
        this.$q.notify({
          color: "green-4",
          icon: "info",
          message: "Regsitrado",
        });
        console.log(res.data)
        this.salida={}
        this.tecnico={label:''}
        this.detalle=[]
        this.dialogReg=false
        this.$q.loading.hide()
      }).catch(error=>{
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: error.message,
        });
        this.$q.loading.hide()

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
        this.tecnico={label:''}
        this.filtertecnicos=this.tecnicos
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
    printDia(){
      let contenido=''

      let cadena="<style>\
      *{font-size:10px;}\
      table{\
        width:100%;\
      }\
      .titulo{\
      text-align:center;\
      font-weight:bold;\
      }\
      </style>\
      <div id='myelement'>\
      <div class='titulo'>SALIDA DE ALMACEN</div>"
      this.salidas.forEach(r => {
        cadena+="<div><b>FECHA: </b>"+r.fecha+" "+r.hora+"</div>\
      <div><b>TECNICO: </b>"+r.tecnico.name+"</div>\
      <div><b>CARRO: </b>"+r.carro+"</div>\
      <div><b>DESTINO: </b>"+r.destino+"</div>\
      <div><b>MOTIVO: </b>"+r.motivo+"</div><hr>\
      <div class='titulo'><b>DETALLE </b></div>\
      <table style='padding:5px'><tr><th>CANTIDAD</th><th>MATERIAL</th><th>CODIGO</th></tr>"
        r.elementos.forEach(e => {

        cadena+='<tr><td>'+e.cantidad+'</td><td>'+e.material+'</td><td>'+e.inventario.codigo+'</td></tr> '

        })
        cadena+='</table><hr style=" border: 1px solid red;">'
      })
      cadena+="</div>"

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
