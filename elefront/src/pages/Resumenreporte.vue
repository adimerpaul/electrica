<template>
  <q-page padding>
    <div class="text-h5 text-center">RESUMEN SALIDA DE MATERIAL</div>
    <div class="row">
      <div class="col-6 q-pa-xs"><q-input dense v-model="fecha" type="date" label="Fecha" outlined/></div>
      <div class="col-6 q-pa-xs"><q-input dense v-model="fecha2" type="date" label="Fecha2" outlined/></div>


    </div>
    <div class="col-12">
      <q-table
        title="REPORTE POR MATERIAL"
        :data="salidas"
        :columns="colsalida"
        row-key="name"
        :filter="filter"
      >
      <template v-slot:top-right>
        <div class="col-6 q-pa-xs"><q-select outlined dense use-input @filter="filterMat" v-model="material" :options="materiales" label="Material"  /></div>
      <div class="col-6 q-pa-xs"><q-btn color="green" icon="search" label="Reporte Material" @click="generarList" /></div>
        <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>

      </q-table>
    </div>
    <div style="font-size: 20px;"><b>TOTAL: </b> {{ totalsalida }}</div>
    <div class="col-12">
      <q-table
        title="REPORTE POR TECNICO"
        :data="reporte"
        :columns="coltecnico"
        row-key="name"
        :filter="filter2"
      >
      <template v-slot:top-right>
              <div class="col-6 q-pa-xs"><q-select outlined dense use-input @filter="filterTec" v-model="tecnico" :options="tecnicos" label="Tecnicos"  /></div>
      <div class="col-6 q-pa-xs"><q-btn color="accent" icon="search" label="Reporte Tecnico" @click="generarListTecnico" /></div>
        <q-input outlined dense debounce="300" v-model="filter2" placeholder="Buscar">
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

export default {
  data() {
    return {
      filter:'',
      filter2:'',
      material:{label:''},
      fecha:date.formatDate(new Date(),'YYYY-MM-DD'),
      fecha2:date.formatDate(new Date(),'YYYY-MM-DD'),
      salidas:[],
      materiales:[],
      filterMateriales:[],
      filterTecnicos:[],
      tecnicos:[],
      detalle:[],
      tecnico:{label:''},
      colsalida:[
        {name:'fecha',label:'FECHA',field:'fecha'},
        {name:'tecnico',label:'TECNICO',field:'tecnico'},
        {name:'total',label:'TOTAL',field:'total'},
        {name:'material',label:'MATERIAL',field:'material'}
      ],
      coltecnico:[
        {name:'codigo',label:'CODIGO',field:'codigo'},
        {name:'material',label:'MATERIAL',field:'material'},
        {name:'total',label:'TOTAL',field:'total'},
      ],
      reporte:[]
    }
  },
  created() {
    this.getMaterial()
    this.getTecnico()
    this.generarList()
    this.generarListTecnico()
  },
  computed: {
    totalsalida() {
      let total=0
      this.salidas.forEach(r => {
          total+=parseInt( r.total)
      })
      return total 
        
    }
  },
  methods: {
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
    generarList(){
      if(this.fecha==undefined || this.fecha=='')
        return false
      if(this.fecha2==undefined || this.fecha2=='')
        return false
      if(this.material.id==undefined)
        return false
      this.$axios.post("reportEntMat",{ini:this.fecha,fin:this.fecha2,material_id:this.material.id}).then((res) => {
        this.salidas=res.data
      })
    },

    generarListTecnico(){
      if(this.fecha==undefined || this.fecha=='')
        return false
      if(this.fecha2==undefined || this.fecha2=='')
        return false
      if(this.tecnico.id==undefined)
        return false
      this.$axios.post("reportTecnicoMat",{ini:this.fecha,fin:this.fecha2,user_id:this.tecnico.id}).then((res) => {
        this.reporte=res.data
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

  
  
  }

}
</script>
