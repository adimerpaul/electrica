<template>
<q-page class="q-pa-xs">
  <div class="row">
    <div class="col-12">
      <q-card>
        <q-card-section  class="bg-green text-white text-center text-subtitle2">Reporte de denuncias</q-card-section>
        <q-card-section>
          <q-form @submit.prevent="reporte">
            <div class="row">
              <div class="col-12 col-sm-4">
                <q-input outlined dense label="Fecha inicio" type="date" v-model="fecha1" />
              </div>
              <div class="col-12 col-sm-4">
                <q-input outlined dense label="Fecha fin" type="date" v-model="fecha2" />
              </div>
              <div class="col-12 col-sm-4 flex flex-center">
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
            icon-right="archive"
            label="Exportar a csv"
            no-caps
            @click="exportTable"
          />
        </template>
      </q-table>
    </div>
  </div>

</q-page>
</template>
<script>
import {date} from "quasar"
import {jsPDF} from "jspdf"
import { exportFile } from 'quasar'

function wrapCsvValue (val, formatFn, row) {
  let formatted = formatFn !== void 0
    ? formatFn(val, row)
    : val

  formatted = formatted === void 0 || formatted === null
    ? ''
    : String(formatted)

  formatted = formatted.split('"').join('""')
  /**
   * Excel accepts \n and \r in strings, but some other CSV parsers do not
   * Uncomment the next two lines to escape new lines
   */
  // .split('\n').join('\\n')
  // .split('\r').join('\\r')

  return `"${formatted}"`
}
export default {
  name: `Reportedenuncia`,
  data(){
    return{
      fecha1:date.formatDate(new Date(),'YYYY-MM-DD'),
      fecha2:date.formatDate(new Date(),'YYYY-MM-DD'),
      misdenuncias:[],
      columns:[
        {name:"actividad",field:"actividad",label:"actividad"},
        {name:"ci",field:"ci",label:"ci"},
        {name:"direccion",field:"direccion",label:"direccion"},
        {name:"estado",field:"estado",label:"estado"},
        {name:"fecha",field:"fecha",label:"fecha"},
        {name:"fechaman",field:"fechaman",label:"fechaman"},
        {name:"hora",field:"hora",label:"hora"},
        {name:"horaman",field:"horaman",label:"horaman"},
        {name:"id",field:"id",label:"id"},
        {name:"lat",field:"lat",label:"lat"},
        {name:"lng",field:"lng",label:"lng"},
        {name:"nombre",field:"nombre",label:"nombre"},
        {name:"nroposte",field:"nroposte",label:"nroposte"},
        {name:"reclamo",field:"reclamo",label:"reclamo"},
        {name:"supervisor",field:"supervisor",label:"supervisor"},
        {name:"tecnico",field:"tecnico",label:"tecnico"},
        {name:"telefono",field:"telefono",label:"telefono"},
        {name:"zona",field:"zona",label:"zona"},
      ]
    }
  },
  methods:{
    exportTable () {
      // naive encoding to csv format
      const content = [this.columns.map(col => wrapCsvValue(col.label))].concat(
        this.misdenuncias.map(row => this.columns.map(col => wrapCsvValue(
          typeof col.field === 'function'
            ? col.field(row)
            : row[ col.field === void 0 ? col.name : col.field ],
          col.format,
          row
        )).join(','))
      ).join('\r\n')

      const status = exportFile(
        date.formatDate(new Date(),'YYYY-MM-DD')+'Denuncias.csv',
        content,
        'text/csv'
      )

      if (status !== true) {
        $q.notify({
          message: 'Browser denied file download...',
          color: 'negative',
          icon: 'warning'
        })
      }
      },
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
          doc.setFont(undefined,'normal')
        }
        var doc = new jsPDF('p','cm','letter')
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
            if (con==55){
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
    },
  }
}
</script>

<style scoped>

</style>
