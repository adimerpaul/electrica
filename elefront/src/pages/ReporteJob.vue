<template>
<q-page class="q-pa-xs">
  <div class="row">
    <div class="col-12">
      <q-card>
        <q-card-section  class="bg-accent text-white text-center text-subtitle2">REPORTE DEPENDENCIAS / PLAZAS Y PARQUES</q-card-section>
        <q-card-section>
          <q-form @submit.prevent="reporte2">
            <div class="row">
              <div class="col-xs-12 col-md-3">
                <q-input outlined dense label="Fecha inicio" type="date" v-model="fecha1" required/>
              </div>
              <div class="col-xs-12 col-md-3">
                <q-input outlined dense label="Fecha fin" type="date" v-model="fecha2" required/>
              </div>
              <div class="col-xs-12 col-md-3">
                <q-select v-model="tipo" :options="lTipo" label="Seleccionar" outlined dense />
                <!--<q-toggle
                  v-model="tipo"
                  color="green"
                  :label="tipo"
                  true-value="PLAZAS Y PARQUES"
                  false-value="DEPENDENCIAS"
                />--></div>
              <div class="col-12 col-sm-3">
                <q-select outlined dense label="Usuario" v-model="user" :options="users" use-input @filter="filterUs" />
              </div>
              <div class="col-12 col-sm-3 flex flex-center">
                <q-btn type="submit" label="Generar reporte" color="positive" icon="print" class="full-width full-height" />
              </div>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </div>
<!--    <div class="col-12">-->
<!--      <pre>{{misdenuncias}}</pre>-->
<!--    </div>-->
    <div class="col-12">
      <q-table
        title="Denuncias"
        :data="misjobs"
        color="primary"
        row-key="name"
        :columns="columns"
        :filter="filter"
      >
      <template v-slot:top-right>
        <q-btn color="red" label="PDF" @click="exportPdf" />
        <q-btn color="green" label="EXCEL" @click="descarga" />
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>

      </q-table>
    </div>
  </div>
  <q-table
    title="Listado Reporte Material en Mtto"
    :data="listRepMaterial"
    row-key="name"

    >
    <template v-slot:top-right>
          <div class="col-4"><q-select outlined dense use-input @filter="filterMat" v-model="material" :options="materiales" label="Material" filled /></div>
          <q-btn color="green"  label="Buscar" @click="getRepMaterial" dense/>
          <q-btn color="indigo"  label="EXCEL" @click="generarExcel" dense/>
        </template>
    </q-table>
  <div id="myelement" class="hidden"></div>

</q-page>
</template>
<script>
import {date} from "quasar"
import {jsPDF} from "jspdf"
import { exportFile } from 'quasar'
import {Printd} from "printd";
import xlsx from "json-as-xlsx"

export default {
  name: `Reportejob`,
  data(){
    return{
      tipo:"PLAZAS Y PARQUES",
      lTipo:['PLAZAS Y PARQUES','DEPENDENCIAS','SEMAFOROS'],
      material:{label:''},
      materiales:[],
      filterMateriales:[],
      listRepMaterial:[],
      tiprint:'',
      filter:'',
      fecha1:date.formatDate(new Date(),'YYYY-MM-DD'),
      fecha2:date.formatDate(new Date(),'YYYY-MM-DD'),
      misjobs:[],
      ini:'',
      fin:'',
      cadena:'',
      users:[{lable:'TODOS',id:0}],
      user:{},
      userprint:{},
      filtUser:[],
      columns:[
        {name:"lat",field:"lat",label:"LAT"},
        {name:"lng",field:"lng",label:"LNG"},
        {name:"fecha",field:"fecha",label:"FECHA",sortable:true},
        {name:"hora",field:"hora",label:"HORA",sortable:true},
        {name:"lugar",field:"lugar",label:"LUGAR",sortable:true},
        {name:"tecnico",field: row=>row.user.name,label:"TECNICO",sortable:true},
        {name:"tipo",field:"tipo",label:"TIPO"},
        {name:"actividad",field:"actividad",label:"ACTIVIDAD"},
      ]
    }
  },
  created(){
    this.getUsers()
    if (!this.$store.state.login.boolreporte){
       this.$router.replace({ path: '/home' })
    }
    this.getMaterial()
  },
  methods:{
    generarExcel(){
    let dataimp = [
  {
    sheet: "Reporte",
    columns: [
        {label:'FECHA',value:'fecha'},
        {label:'HORA',value:'hora'},
        {label:'LAT',value:'lat'},
        {label:'LNG',value:'lng'},
        {label:'LUGAR',value: 'lugar'},
        {label:'TECNICO',value:'name'},
        {label:'MATERIAL',value:'material'},
        {label:'CODIGO',value:'codigo'},
    ],
    content: this.listRepMaterial
  },
]
    let settings = {
      fileName: "ReporteMaterialMttoDep", // Name of the resulting spreadsheet
      extraLength: 6, // A bigger number means that columns will be wider
      writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
    }
      this.$q.loading.hide()
    xlsx(dataimp, settings) // Will download the excel file

  },
    getRepMaterial(){
      if(this.material.id==undefined) return false
      this.$axios.post("reportTrabMaterial",{material_id:this.material.id,ini:this.fecha1,fin:this.fecha2}).then((res) => {
        console.log(res.data)
        this.listRepMaterial=res.data
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
      this.$axios.get("listmaterial").then((res) => {
        res.data.forEach(r => {
          r.label=r.nombre
          this.materiales.push(r)
        })
        this.filterMateriales=this.materiales
      })
    },
    descarga(){
      if(this.misjobs.length<1)
        return false
      //return false
      let impresion=this.misjobs
        this.userprint=this.user
        this.tiprint=this.tipo
        this.ini=this.fecha1
        this.fin=this.fecha2

        this.$q.loading.show();
        let dataimp = [
  {
    sheet: "Reporte",
    columns: [
        {label:'FECHA',value:'fecha'},
        {label:'HORA',value:'hora'},
        {label:'LUGAR',value:'lugar'},
        {label:'TECNICO',value:row=>row.user.name},
        {label:'TIPO',value:'tipo'},
        {label:'ACTIVIDAD',value:'actividad'},
    ],
    content: impresion
  },
]
    let settings = {
      fileName: "ReporteMtto", // Name of the resulting spreadsheet
      extraLength: 6, // A bigger number means that columns will be wider
      writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
    }
      this.$q.loading.hide()
    xlsx(dataimp, settings) // Will download the excel file

    },
    filterUs (val, update) {
        if (val === '') {
          update(() => {
            this.users = this.filtUser

            // here you have access to "ref" which
            // is the Vue reference of the QSelect
          })
          return
        }

        update(() => {
          const needle = val.toLowerCase()
          this.users = this.filtUser.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
        })
      },
    getUsers(){
      this.users=[{label:'TODOS',id:0}]
      this.$axios.get('listUser').then(res=>{
        res.data.forEach(r => {
          r.label=r.name
          this.users.push(r)
        })
        this.filtUser= this.users
        this.user={label:''}
      })

    },
    exportPdf(){
      if((this.misjobs).length>0){
        this.cadena="<style>\
          .fuente{font-size:10px}\
          table{width:100%; border-collapse: collapse;}\
          table, th, td {  border: 1px solid;}\
          titulo{font-weight: bold; font-size:16px; text-align: center;}\
          </style>\
          <div class='fuente'>\
            <table style='border:0;'><tr><td style='border:0;width:20%;'><img src='logo.jpg' style='width:50px; height:50px;'/></td>\
               <td class='titulo' style='border:0;'> RESUMEN DE "+this.tiprint+" DEL "+this.ini+" AL "+this.fin+" Usuario: "+this.userprint.label+"</td>\
               </tr>\
            </table>\
            <table>\
            <thead><tr><th>N</th><th>LAT</th><th>LNG</th><th>FECHA</th><th>HORA</th><th>LUGAR</th><th>TECNICO</th><th>TIPO</th><th>ACTIVIDAD</th></tr></thead>\
          <tbody>"
            let con=0
            this.misjobs.forEach(r=>{
            con++
            this.cadena+="<tr>\
              <td>"+con+"</td>\
              <td>"+r.lat+"</td>\
              <td>"+r.lng+"</td>\
              <td>"+r.fecha+"</td>\
              <td>"+r.hora+"</td>\
              <td>"+r.lugar+"</td>\
              <td>"+r.user.name+"</td>\
              <td>"+r.tipo+"</td>\
              <td>"+r.actividad+"</td>\
              </tr>"})
              this.cadena+="</tbody></table></div>"
              //console.log(this.cadena)
          document.getElementById('myelement').innerHTML = this.cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )}

    },

      reporte2(){
        if(this.user.id==undefined)
          return false
        this.cadena=''
        this.$axios.post('reportJob',{ini:this.fecha1,fin:this.fecha2,id:this.user.id,tipo:this.tipo}).then(res=>{
          console.log(res.data)
        this.misjobs=res.data
        this.userprint=this.user
        this.tiprint=this.tipo
        this.ini=this.fecha1
        this.fin=this.fecha2
           })

      },

  }
}
</script>

<style scoped>

</style>
