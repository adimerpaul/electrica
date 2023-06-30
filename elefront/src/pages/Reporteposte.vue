<template>
<q-page class="q-pa-xs">
  <div class="row">
    <div class="col-12">
      <q-card>
        <q-card-section  class="bg-green text-white text-center text-subtitle2">Reporte por Poste</q-card-section>
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
                <q-input v-model="numero" type="text" label="Nro Poste" dense outlined required/>
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
          <q-btn
            color="primary"
            label="Exportar PDF"
            @click="exportPdf"
          />
        </template>
      </q-table>
    </div>
  </div>
  <div id="myelement" class="hidden"></div>

</q-page>
</template>
<script>
import {date} from "quasar"
import {jsPDF} from "jspdf"
import { exportFile } from 'quasar'
import {Printd} from "printd";


export default {
  name: `Reportedenuncia`,
  data(){
    return{
      fecha1:date.formatDate(new Date(),'YYYY-MM-DD'),
      fecha2:date.formatDate(new Date(),'YYYY-MM-DD'),
      misdenuncias:[],
      numero:'',
      cadena:'',
      users:[{lable:'TODOS',id:0}],
      user:{},
      filtUser:[],
      columns:[
        {name:"actividad",field:"actividad",label:"ACTIVIDAD"},
        {name:"estado",field:"estado",label:"ESTADO"},
        {name:"tipo",field:"tipo",label:"TIPO"},
        {name:"fechaman",field:"fechaman",label:"FECHA MAN"},
        {name:"horaman",field:"horaman",label:"HORA MAN"},
        {name:"tecnico",field:"tecnico",label:"TECNICO"},

        {name:"nroposte",field:row=>row.poste.nroposte,label:"NRO POSTE"},
        {name:"reclamo",field:"reclamo",label:"RECLAMO"},
        {name:"fecha",field:"fecha",label:"FECHA"},
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
  },
  methods:{
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

      reporte2(){
        this.cadena=''
        this.$axios.post('reportePoste',{ini:this.fecha1,fin:this.fecha2,numero:this.numero}).then(res=>{
          console.log(res.data)
        this.misdenuncias=res.data

          })

      },
 /*   reporte(){
      this.$q.loading.show()
      this.$axios.post('consulta',{inicio:this.fecha1,fin:this.fecha2}).then(res=>{
        this.misdenuncias=res.data
        let cm=this;
        function header(){
          var img = new Image()
          img.src = 'logo.jpg'
          doc.addImage(img, 'jpg', 0.5, 0.5, 2, 2)
          doc.setFont(undefined,'bold')
          doc.setFontSize(9);
          doc.text(5, 1, 'RESUMEN DIARIO DE INGRESOS')
          doc.text(5, 1.5, 'DEL '+cm.fecha1+' AL '+ cm.fecha2)
          doc.text(1, 3, '__________________________________________________________________________________________________')
          doc.text(1, 3, 'FECHA')
          doc.text(3, 3, 'Nº POSTE')
          doc.text(5.5, 3, 'ESTADO')
          doc.text(7.5, 3, 'CONTRIBUYENTE')
          doc.text(12.8, 3, 'CI/RUN')
          doc.text(14.3, 3, 'HORA.')
          doc.text(16, 3, 'OPERADOR')
          doc.text(18.7, 3, 'Fecha Man.')
          doc.text(21.2, 3, 'Actividad')
          doc.setFont(undefined,'normal')
        }
        var doc = new jsPDF('L','cm','letter')
        doc.setFont("courier");

        header(this.fecha)
        let y=0
        let sumtotal=0
        let con=0
        this.misdenuncias.forEach(r=>{
          console.log(r)
          // if (r.nrocomprobante!='' && r.nrocomprobante!=null){
            y+=0.4
            con++
            doc.setFontSize(8);
            doc.text(1, y+3, r.fecha)
            doc.text(3, y+3, r.nroposte.substring(0,15))
            doc.text(5.5, y+3, r.estado)
            doc.text(7.5, y+3, (r.nombre).substring(0,30))
            doc.text(12.8, y+3, r.ci)
            doc.text(14.3, y+3, r.hora)
            // // sumtotal+=parseInt(r.total)
            // // console.log(r.total)
            doc.text(16, y+3, r.tecnico==null?'':r.tecnico.substring(0,15) )
            doc.text(18.7, y+3, r.fechaman==null?'':r.fechaman )
            doc.text(21.2, y+3, r.actividad==null?'':r.actividad.substring(30))
            if (y>=16){
              // con=0
              doc.addPage();
              header()
              y=0
            }
          // }
        })
        doc.setFontSize(9);
        doc.setFont(undefined,'bold')
        doc.text(3, y+3.5, 'SON: '+con+' DENUNCIAS')
        // doc.text(12, y+3.5, 'TOTAL RECAUDADCION: ')
        doc.text(1.8, y+5, '_____________________          _____________________________       _________________________')
        doc.text(2, y+5.3, 'FIRMA SELLO CAJERO')
        doc.text(8, y+5.3, 'FIRMA SELLO CONTROL INTERNO')
        doc.text(15, y+5.3, 'FIRMA SELLO LIQUIDADOR')
        // doc.setFont(undefined,'normal')
        // doc.text(18, y+3.5, sumtotal+ ' Bs')
        // const conversor = require('conversor-numero-a-letras-es-ar');
        // let ClaseConversor = conversor.conversorNumerosALetras;
        // let miConversor = new ClaseConversor();
        // var a = miConversor.convertToText(sumtotal);
        // doc.text(1, y+4, 'SON: '+ a.toUpperCase()+' BS')
        // doc.save("Pago"+date.formatDate(Date.now(),'DD-MM-YYYY')+".pdf");
        window.open(doc.output('bloburl'), '_blank');
        // console.log(res.data)
        this.$q.loading.hide()
      })
    },*/
  }
}
</script>

<style scoped>

</style>
