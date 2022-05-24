<template>
<q-page class="q-pa-xs">
<q-card>
  <q-card-section  class="bg-green text-white text-center text-subtitle2">Reporte de denuncias</q-card-section>
  <q-card-section>
    <q-form @submit.prevent="reporte">
      <div class="row">
        <div class="col-12 col-sm-4">
          <q-input outlined label="Fecha inicio" type="date" v-model="fecha1" />
        </div>
        <div class="col-12 col-sm-4">
          <q-input outlined label="Fecha fin" type="date" v-model="fecha2" />
        </div>
        <div class="col-12 col-sm-4 flex flex-center">
          <q-btn type="submit" label="Generar reporte" color="positive" icon="print" class="full-width full-height" />
        </div>
      </div>
    </q-form>
  </q-card-section>
</q-card>
</q-page>
</template>
<script>
import {date} from "quasar"
import {jsPDF} from "jspdf"

export default {
  name: `Reportedenuncia`,
  data(){
    return{
      fecha1:date.formatDate(new Date(),'YYYY-MM-DD'),
      fecha2:date.formatDate(new Date(),'YYYY-MM-DD'),
      misdenuncias:[],
    }
  },
  methods:{
    reporte(){
      this.$q.loading.show()
      this.$axios.post('consulta',{inicio:this.fecha1,fin:this.fecha2}).then(res=>{
        this.misdenuncias=res.data
        let cm=this;
        function header(){
          var img = new Image()
          img.src = 'logo.jpg'
          doc.addImage(img, 'jpg', 0.5, 0.5, 2, 2)
          doc.setFont(undefined,'bold')
          doc.text(5, 1, 'RESUMEN DIARIO DE INGRESOS')
          doc.text(5, 1.5, 'DEL '+cm.fecha1+' AL '+ cm.fecha2)
          doc.text(1, 3, '__________________________________________________________________________________________________')
          doc.text(1, 3, 'FECHA')
          doc.text(3.5, 3, 'Nº POSTE')
          doc.text(5.5, 3, 'ESTADO')
          doc.text(7.5, 3, 'CONTRIBUYENTE')
          doc.text(13.5, 3, 'CI/RUN/RUC')
          doc.text(16, 3, 'HORA.')
          doc.text(18, 3, 'OPERADOR')
          doc.setFont(undefined,'normal')
        }
        var doc = new jsPDF('p','cm','letter')
        doc.setFont("courier");
        doc.setFontSize(9);
        header(this.fecha)
        let y=0
        let sumtotal=0
        let con=0
        this.misdenuncias.forEach(r=>{
          console.log(r)
          // if (r.nrocomprobante!='' && r.nrocomprobante!=null){
            y+=0.4
            con++
            doc.text(1, y+3, r.fecha)
            doc.text(3.5, y+3, r.nroposte)
            doc.text(5.5, y+3, r.estado)
            doc.text(7.5, y+3, (r.nombre).substring(0,50))
            doc.text(13.5, y+3, r.ci)
            doc.text(16, y+3, r.hora)
            // // sumtotal+=parseInt(r.total)
            // // console.log(r.total)
            doc.text(18, y+3, r.tecnico==null?'':r.tecnico )
            if (con==55){
              // con=0
              doc.addPage();
              header()
              y=0
            }
          // }
        })
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
    },
  }
}
</script>

<style scoped>

</style>
