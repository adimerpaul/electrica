<template>
<q-page class="q-pa-xs">
  <div class="row">
    <div class="col-12">
      <q-card>
        <q-card-section  class="bg-green text-white text-center text-subtitle2">Reporte de denuncias</q-card-section>
        <q-card-section>
          <q-form @submit.prevent="reporte2">
            <div class="row">
              <div class="col-12 col-sm-3">
                <q-input outlined dense label="Fecha inicio" type="date" v-model="fecha1" required/>
              </div>
              <div class="col-12 col-sm-3">
                <q-input outlined dense label="Fecha fin" type="date" v-model="fecha2" required/>
              </div>
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
        :data="misdenuncias"
        color="primary"
        row-key="name"
        :columns="columns"
      >
        <template v-slot:top-right>
          <q-btn color="primary" icon-right="archive" label="Exportar PDF" @click="exportPdf" />
          <q-btn color="green"  label="EXCEL" @click="descarga" dense/>
        </template>
      </q-table>
    </div>
  </div>
  <q-table
    title="Reporte Material Matto"
    :data="repMaterial"
    :columns="colRep"
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
  name: `Reportedenuncia`,
  data(){
    return{
      fecha1:date.formatDate(new Date(),'YYYY-MM-DD'),
      fecha2:date.formatDate(new Date(),'YYYY-MM-DD'),
      misdenuncias:[],
      cadena:'',
      users:[{lable:'TODOS',id:0}],
      user:{},
      filtUser:[],
      materiales:[],
      material:{label:''},
      filterMateriales:[],
      repMaterial:[],
      colRep:[
        {label:'fecha',name:'fecha',field:'fechaman'},
        {label:'hora',name:'hora',field:'horaman'},
        {label:'lat',name:'lat',field:'lat'},
        {label:'lng',name:'lng',field:'lng'},
        {label:'nroposte',name:'nroposte',field:'nroposte'},
        {label:'poste',name:'poste',field:'poste'},
        {label:'material',name:'material',field:'material'},
        {label:'tecnico',name:'tecnico',field:'tecnico'},
        {label:'codigo',name:'codigo',field:'codigo'},
      ],
      columns:[
        {name:"actividad",field:"actividad",label:"ACTIVIDAD"},
        {name:"estado",field:"estado",label:"ESTADO"},
        {name:"tipo",field:"tipo",label:"TIPO"},
        {name:"fechaman",field:"fechaman",label:"FECHA MAN"},
       // {name:"hora",field:"hora",label:"HORA"},
        {name:"horaman",field:"horaman",label:"HORA MAN"},
       // {name:"id",field:"id",label:"ID"},
       // {name:"lat",field:row=>row.poste.lat,label:"lat"},
        //{name:"lng",field:row=>row.poste.lng,label:"lng"},
        {name:"tecnico",field:"tecnico",label:"TECNICO"},

        {name:"nroposte",field:row=>row.poste.nroposte,label:"NRO POSTE"},
        {name:"reclamo",field:"reclamo",label:"RECLAMO"},
        {name:"fecha",field:"fecha",label:"FECHA"},
        //{name:"supervisor",field:"supervisor",label:"SUPERVISOR"},
        {name:"ci",field:row=>row.persona==undefined?'':row.persona.ci,label:"CI"},
        {name:"nombre",field:row=>row.persona==undefined?'':row.persona.nombre,label:"NOMBRE"},
        {name:"telefono",field:row=>row.persona==undefined?'':row.persona.telefono,label:"TELEFONO"},
      ]
    }
  },
  created(){
    if (!this.$store.state.login.boolreporte){
       this.$router.replace({ path: '/home' })
    }
    this.getUsers()
    this.getMaterial()
  },

  methods:{
    getRepMaterial(){
      if(this.material.id==undefined) return false
      this.$axios.post("reporteRecMaterial",{material_id:this.material.id,ini:this.fecha1,fin:this.fecha2}).then((res) => {
        console.log(res.data)
        this.repMaterial=res.data
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

  generarExcel(){
    let dataimp = [
  {
    sheet: "Reporte",
    columns: [
        {label:'FECHA',value:'fechaman'},
        {label:'HORA',value:'horaman'},
        {label:'LAT',value:'lat'},
        {label:'LNG',value:'lng'},
        {label:'N poste',value: 'nroposte'},
        {label:'POSTE',value:'poste'},
        {label:'MATERIAL',value:'material'},
        {label:'TECNICO',value:'tecnico'},
        {label:'CODIGO',value:'codigo'},
    ],
    content: this.repMaterial
  },
]
    let settings = {
      fileName: "ReporteMaterialMtto", // Name of the resulting spreadsheet
      extraLength: 6, // A bigger number means that columns will be wider
      writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
    }
      this.$q.loading.hide()
    xlsx(dataimp, settings) // Will download the excel file

  },
    descarga(){
      if(this.misdenuncias.length<1)
        return false
      //return false
      let con=0
      let impresion=[]
      this.misdenuncias.forEach(r=>{
            r.orden=con++
            if(r.tecnico==null) r.tecnico=''
            if(r.fechaman==null) r.fechaman=''
            if(r.actividad==null) r.actividad=''
            if(r.persona==undefined) r.persona={nombre:'',ci:'',telefono:''}
            impresion.push(r)
 })

        this.$q.loading.show();
        let dataimp = [
  {
    sheet: "Reporte",
    columns: [
        {label:'N',value:'orden'},
        {label:'FECHA',value:'fecha'},
        {label:'N poste',value:row=>row.poste.nroposte},
        {label:'estado',value:'estado'},
        {label:'contribuyente',value:row=>row.persona.nombre},
        {label:'ci',value:row=>row.persona.ci},
        {label:'tecnico',value:'tecnico'},
        {label:'fecha man',value:'fechaman'},
        {label:'actividad',value:'actividad'},
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

      reporte2(){
        if(this.user.id==undefined)
          return false
        this.cadena=''
        this.$axios.post('reporteReclamo',{ini:this.fecha1,fin:this.fecha2,id:this.user.id}).then(res=>{
          console.log(res.data)
          this.misdenuncias=res.data

          })

      },
      exportPdf(){
        if((this.misdenuncias).length>0){
        this.cadena="<style>\
          .fuente{font-size:10px}\
          table{width:100%; border-collapse: collapse;}\
          table, th, td {  border: 1px solid;}\
          titulo{font-weight: bold; font-size:16px; text-align: center;}\
          </style>\
          <div class='fuente'>\
            <table style='border:0;'><tr><td style='border:0;width:20%;'><img src='logo.jpg' style='width:50px; height:50px;'/></td>\
               <td class='titulo' style='border:0;'> RESUMEN DE MTTOs y ATENCION RECLAMOS DEL "+this.fecha1+" AL "+this.fecha2+" Usuario: "+this.user.label+"</td>\
               </tr>\
            </table>\
            <table>\
            <thead><tr><th>N</th><th>FECHA</th><th>N POSTE</th><th>ESTADO</th><th>CONTRIBUYENTE</th><th>CI/RUN</th><th>HORA</th><th>OPERADOR</th><th>FECHA MAN</th><th>ACTIVIDAD</th></tr></thead>\
          <tbody>"
            let con=0
            this.misdenuncias.forEach(r=>{
            con++
            if(r.tecnico==null) r.tecnico=''
            if(r.fechaman==null) r.fechaman=''
            if(r.actividad==null) r.actividad=''
            if(r.persona==undefined) r.persona={nombre:'',ci:'',telefono:''}
            this.cadena+="<tr>\
              <td>"+con+"</td>\
              <td>"+r.fecha+"</td>\
              <td>"+r.poste.nroposte+"</td>\
              <td>"+r.estado+"</td>\
              <td>"+r.persona.nombre+"</td>\
              <td>"+r.persona.ci+"</td>\
              <td>"+r.hora+"</td>\
              <td>"+r.tecnico+"</td>\
              <td>"+r.fechaman+"</td>\
              <td>"+r.actividad+"</td>\
              </tr>"})
              this.cadena+="</tbody></table></div>"
              //console.log(this.cadena)
          document.getElementById('myelement').innerHTML = this.cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )}
      },

  }
}
</script>

<style scoped>

</style>
