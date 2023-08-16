<template>
    <div class="q-pa-md">
      <center class="text-h5">REGISTRO DE MANTENIMIENTO</center>
      <q-btn
        label="Registro"
        color="positive"
        @click="junta={label:''}; alert = true"
        icon="add_circle"
        class="q-mb-xs"
      />
      <q-btn
        label="Registrar Junta V"
        color="accent"
        @click="junta2={}; jvDialog = true"
        icon="add_circle"
        class="q-mb-xs"
      />

      <q-dialog v-model="jvDialog">
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="bg-green-14 text-white">
            <div class="text-h7"><q-icon name="add_circle" /> REGISTRO DE JUNTA VECINAL</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="regJV" @reset="onReset" class="q-gutter-md">
                  <q-input outlined v-model="junta2.nombre" type="text" label="Nombre " hint="Ingresar nombre" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" />

                  <q-select v-model="junta2.distrito" :options="['D1','D2','D3','D4','D5','D6']" label="Distrito" outlined />
                  <q-select v-model="junta2.zona" :options="['ZONA NORTE','ZONA ESTE','ZONA CENTRAL','ZONA OESTE','ZONA SUD','ZONA NOR ESTE','ZONA NOR OESTE','ZONA SUD ESTE','ZONA SUD OESTE']"  label="Zona" outlined />

                  <q-input outlined v-model="junta2.representante" type="text" label="Representante" hint="nombre completo"  />

                  <q-input outlined v-model="junta2.celular" label="Celular" hint="numero de contacto"  />

              <div>
                <q-btn label="Crear" type="submit" color="positive" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>

      <q-dialog v-model="alert">
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="bg-green-14 text-white">
            <div class="text-h7"><q-icon name="add_circle" /> REGISTRO NUEVO</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
                <div class="row">
                    <div class="col-12"><q-select dense use-input input-debounce="0"   @input="cargaDatos" @filter="filterFn" v-model="junta" :options="juntas" label="Junta Vecinal" outlined >
                        <template v-slot:no-option>
                                <q-item>
                          <q-item-section class="text-grey">
                            No results
                          </q-item-section>
                        </q-item>
                      </template>
                    </q-select>
                  </div>
                  <div class="col-6"><q-input v-model="agenda.junta" type="text" label="JuntaV / Ubicacion" outlined dense /></div>
                  <div class="col-6"><q-select v-model="agenda.distrito" :options="distritos" label="Distrito" outlined dense /></div>
                  <div class="col-6"><q-input v-model="agenda.dirigente" type="text" label="Dirigente / Rep" outlined dense /></div>
                  <div class="col-6"><q-input v-model="agenda.telefono" type="text" label="Telefono" outlined dense /></div>

                    <div class="col-6"><q-input outlined dense v-model="agenda.fecha" label="Fecha" type="date" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" /></div>
                    <div class="col-12"><q-input outlined dense v-model="agenda.nueva" label="Inst Nuevas"   /></div>
                    <div class="col-12"><q-input outlined dense v-model="agenda.mtto" label="Mantenimiento"   /></div>
                    <div class="col-12"><q-input outlined dense v-model="agenda.repot" label="Repotenciacion"   /></div>
                    <div class="col-12"><q-input outlined dense v-model="agenda.otros" label="Otros"   /></div>
                    <div class="col-12"><q-input outlined dense v-model="agenda.observacion" label="Observacion"   /></div>

                </div>
              <div>
                <q-btn label="Crear" type="submit" color="positive" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
        <div class="row">
            <div class="col-3"><q-input dense outlined v-model="ini" type="date" label="Fecha Ini" /></div>
            <div class="col-3"><q-input dense outlined v-model="fin" type="date" label="Fecha Fin" /></div>
            <div class="col-3"><q-select v-model="estado" :options="estado1" label="Estado" outlined dense /></div>
            <div class="col-2"><q-btn dense color="green" icon="search" label="Buscar" @click="Buscar" /></div>
            <!--<div class="col-2"><q-btn dense color="info" icon="file_download" label="Excel Report" @click="impresion" /></div>
            <div class="col-2"><q-btn dense color="accent" icon="file_download" label="Excel Plan" @click="exportExcel" /></div>-->
        </div>
      <q-table dense :filter="filter" title="CRONOGRAMA" :data="data" :columns="columns" row-key="name" :rows-per-page-options="[0,50,100]">
        <template v-slot:top-right>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Buscar">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>

        <template v-slot:body="props">
          <q-tr  :props="props" :style="'background-color:'+ props.row.color" >
            <q-td key="opcion" :props="props">
              <q-btn-dropdown dense auto-close rounded color="primary" label="OP" split>
        <!-- dropdown content goes here -->
        <q-list dense style="width: 20px;">

              <q-btn dense round flat color="indigo-7"  icon="checklist_rtl" @click="cargaEstado(props.row)" v-if="props.row.estado!='INICIO'"/>
              <q-btn dense round flat color="accent"  icon="manage_accounts"  @click="cargaAsigna(props.row)" v-if="props.row.estado!='SUSPENDIDO' && props.row.estado!='REALIZADO'"/>
              <q-btn dense round flat color="blue-10"  icon="today" @click="cargaFecha(props.row)" v-if="props.row.estado!='SUSPENDIDO'  && props.row.estado!='REALIZADO'"/>
              <q-btn dense round flat color="orange-6" @click="editRow(props)" icon="edit" v-if="props.row.estado!='SUSPENDIDO' && props.row.estado!='REALIZADO'"/>
              <q-btn dense round flat color="info" @click="printReport(props.row)" icon="print" v-if="props.row.cantidad>0"/>
        </q-list>
        </q-btn-dropdown>

            </q-td>
            <q-td key="distrito" :props="props">{{ props.row.distrito }}</q-td>
            <q-td key="junta" :props="props">{{ props.row.junta }}</q-td>
            <q-td key="dirigente" :props="props">{{ props.row.dirigente }}</q-td>
            <q-td key="telefono" :props="props">{{ props.row.telefono }}</q-td>
            <q-td key="fecha" :props="props">{{ props.row.fecha }}</q-td>
            <q-td key="estado" :props="props" class="text-bold">{{ props.row.estado }}</q-td>
            <q-td key="nueva" :props="props">{{ props.row.nueva }}</q-td>
            <q-td key="plantado" :props="props">{{ props.row.plantado }}</q-td>
            <q-td key="mtto" :props="props">{{ props.row.mtto }}</q-td>
            <q-td key="repot" :props="props">{{ props.row.repot }}</q-td>
            <q-td key="otros" :props="props">{{ props.row.otros }}</q-td>
            <q-td key="detalle" :props="props"><q-btn dense flat color="info" icon="visibility"  @click="verDetalle(props.row)" v-if="props.row.cantidad > 1"/></q-td>
            <q-td key="observacion" :props="props">{{ props.row.observacion }}</q-td>
            <q-td key="tecnico" :props="props">{{ props.row.tecnico }}</q-td>
          </q-tr>
          </template>
      </q-table>

      <q-dialog v-model="dialog_mod">
        <q-card style="max-width: 80%; width: 50%">
          <q-card-section class="bg-warning text-white">
            <div class="text-h7"> <q-icon name="edit"/> MODIFICAR DATOS </div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="onMod" class="q-gutter-md">
                  <div class="row">
                    <div class="col-12"><q-select dense use-input input-debounce="0"   @input="cargaDatos2" @filter="filterFn" v-model="junta" :options="juntas" label="Junta Vecinal" outlined >
                        <template v-slot:no-option>
                                <q-item>
                          <q-item-section class="text-grey">
                            No results
                          </q-item-section>
                        </q-item>
                      </template>
                    </q-select>
                  </div>
                  <div class="col-6"><q-input v-model="agenda2.junta" type="text" label="JuntaV / Ubicacion" outlined dense /></div>
                  <div class="col-6"><q-select v-model="agenda2.distrito" :options="distritos" label="Distrito" outlined dense /></div>
                  <div class="col-6"><q-input v-model="agenda2.dirigente" type="text" label="Dirigente / Rep" outlined dense /></div>
                  <div class="col-6"><q-input v-model="agenda2.telefono" type="text" label="Telefono" outlined dense /></div>

                    <div class="col-6"><q-input outlined dense v-model="agenda2.fecha" label="Fecha" type="date" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" /></div>
                    <div class="col-12"><q-input outlined dense v-model="agenda2.nueva" label="Inst Nuevas"   /></div>
                    <div class="col-12"><q-input outlined dense v-model="agenda2.mtto" label="Mantenimiento"   /></div>
                    <div class="col-12"><q-input outlined dense v-model="agenda2.repot" label="Repotenciacion"   /></div>
                    <div class="col-12"><q-input outlined dense v-model="agenda2.otros" label="Otros"   /></div>
                    <div class="col-12"><q-input outlined dense v-model="agenda2.observacion" label="Observacion"   /></div>
                </div>
              <div>
                <q-btn label="Modificar" type="submit" color="positive" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>

      <q-dialog v-model="dialog_asig">
        <q-card style="width: 700px; max-width: 80vw;">
          <q-card-section class="row items-center">
            <span class="q-ml-sm">ASIGNAR PERSONAL / INICIO</span>
          </q-card-section>
          
          <q-card-section style="font-size: 10px;">
            <div>{{ agenda2.fecha }}</div>
            <div>{{ agenda2.junta }}</div>
            <ul>
              <li v-if="agenda2.nueva!=null">{{ agenda2.nueva }}</li>
              <li v-if="agenda2.repot!=null">{{ agenda2.repot }}</li>
              <li v-if="agenda2.mtto!=null">{{ agenda2.mtto }}</li>
              <li v-if="agenda2.plantado!=null">{{ agenda2.plantado }}</li>
              <li v-if="agenda2.otros!=null">{{ agenda2.otros }}</li>
            </ul>
            <div class="row ">
            <div class="col-12 q-pa-xs"><q-select v-model="tecnico" :options="tecnicos" label="Tecnicos" outlined dense use-input @filter="filterTec"/></div>
            <div class="col-md-4 col-xs-12 q-pa-xs"><q-input v-model="agenda2.cantidad" type="number" label="Cantidad" dense outlined/></div>
            <div class="col-md-4 col-xs-12 q-pa-xs"><q-input v-model="agenda2.tipo" type="text" label="Tipo" dense outlined/></div>
            <div class="col-md-4 col-xs-12 q-pa-xs"><q-input v-model="agenda2.potencia" type="text" label="Potencia" dense outlined/></div>
            <div class="col-12 q-pa-xs"><q-input v-model="agenda2.observacion" type="text" label="Observacion" dense outlined/></div>
          </div>
          </q-card-section>

          <q-card-actions align="right">
            <q-btn flat label="Asignar" color="purple" @click="asignar" />
            <q-btn flat label="Cancelar" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>

      <q-dialog v-model="dialog_fec">
        <q-card>
          <q-card-section class="row items-center">
            <span class="q-ml-sm">MODIFICAR FECHA</span>
          </q-card-section>
            <q-card-section style="font-size: 10px;">
            <div>{{ agenda2.fecha }}</div>
            <div>{{ agenda2.junta }}</div>
            <ul>
              <li v-if="agenda2.nueva!=null">{{ agenda2.nueva }}</li>
              <li v-if="agenda2.repot!=null">{{ agenda2.repot }}</li>
              <li v-if="agenda2.mtto!=null">{{ agenda2.mtto }}</li>
              <li v-if="agenda2.plantado!=null">{{ agenda2.plantado }}</li>
              <li v-if="agenda2.otros!=null">{{ agenda2.otros }}</li>
            </ul>
            <div class="col-12"><q-input v-model="agenda2.fecha" type="date" label="Fecha" dense outlined /></div>
            <div class="col-12"><q-input v-model="agenda2.observacion" type="text" label="Observacion" dense outlined/></div>
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="Modificar" color="yellow" @click="cambioFecha" />
            <q-btn flat label="Cancelar" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>

      <q-dialog v-model="dialog_estado">
        <q-card>
          <q-card-section class="row items-center">
            <span class="q-ml-sm">MODIFICAR ESTADO</span>
          </q-card-section>
          <q-card-section style="font-size: 10px;">
            <div>{{ agenda2.fecha }}</div>
            <div>{{ agenda2.junta }}</div>
            <ul>
              <li v-if="agenda2.nueva!=null">{{ agenda2.nueva }}</li>
              <li v-if="agenda2.repot!=null">{{ agenda2.repot }}</li>
              <li v-if="agenda2.mtto!=null">{{ agenda2.mtto }}</li>
              <li v-if="agenda2.plantado!=null">{{ agenda2.plantado }}</li>
              <li v-if="agenda2.otros!=null">{{ agenda2.otros }}</li>
            </ul>
            <div class="col-12"><q-select v-model="agenda2.estado" type="date" label="Estado" :options="estado2" dense outlined /></div>
            <div class="col-12"><q-input v-model="agenda2.observacion" type="text" label="Observacion" dense outlined/></div>
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="Modificar" color="deep-orange" @click="cambioEstado" />
            <q-btn flat label="Cancelar" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <div id="myelement" class="hidden"></div>

    </div>
  </template>

  <script>
  import { date } from 'quasar'
import xlsx from "json-as-xlsx"
import {Printd} from "printd";
import moment from 'moment';
  const { addToDate } = date
  export default {
    data() {
      return {
        estado:'TODO',
        estado1:['TODO','INICIO','EN PROCESO','REALIZADO','PENDIENTE','SUSPENDIDO'],
        estado2:['REALIZADO','PENDIENTE','SUSPENDIDO'],
        distritos:['D1','D2','D3','D4','D5','D6'],
        agendas:[],
        agenda:{fecha:date.formatDate(new Date(), "YYYY-MM-DD"),distrito:'D1'},
        junta:{},
        alert: false,
        junta2:{},
        agenda2:{},
        cEstado:'',
        jvDialog:false,
        dialog_mod: false,
        dialog_del: false,
        dialog_asig: false,
        dialog_fec: false,
        dialog_estado: false,
        filter:'',
        tipo:{},
        dato: {},
        juntas:[],
        jfilter:[],
        ini:date.formatDate(new Date(), "YYYY-01-01"),
        fin:date.formatDate(new Date(), "YYYY-12-31"),

        model:'',
        dato2: {},
        options: [],
        tecnico:{},
        tecnicos:[],
        filtertecnicos:[],
        props: [],
        modelpermiso:false,
        uni:{},
        columns: [
            {name: "opcion", label: "OPCION", field: "opcion", sortable: false },
            {name: "distrito", align: "left", label: "DISTRITO ", field:'distrito', sortable: true,},
            {name: "junta", align: "left", label: "JUNTA", field: 'junta', sortable: true,},
            {name: "dirigente", align: "left", label: "DIRIGENTE ", field: 'dirigente', sortable: true,},
            {name: "telefono", align: "left", label: "TELEFONO", field: 'telefono', sortable: true,},
            {name: "fecha", align: "left", label: "FECHA", field: 'fecha', sortable: true,},
            {name: "estado", align: "left", label: "ESTADO", field: 'estado', sortable: true,},
            {name: "nueva", align: "left", label: "NUEVOS", field: 'nueva', sortable: true,},
            {name: "mtto", align: "left", label: "MTTO", field: 'mtto', sortable: true,},
            {name: "plantado", align: "left", label: "PLANTADO ", field: 'plantado', sortable: true,},
            {name: "repot", align: "left", label: "REPOTENCIACION", field: 'repot', sortable: true,},
            {name: "otros", align: "left", label: "OTROS", field: 'otros', sortable: true,},
            {name: "detalle", align: "left", label: "DETALLE", field: 'detalle', sortable: true,},
            {name: "observacion", align: "left", label: "OBSERVACION", field: "observacion", sortable: true,},
            {name: "tecnico", align: "left", label: "TECNICO", field: "tecnico", sortable: true,},
        ],
        data: [],

      }

    },
    created() {
      // if (!this.$store.state.login.boolusuario){
      //    this.$router.replace({ path: '/' })
      // }

      this.Buscar();
      this.listJunta();
      this.cargarTecnicos()

    },
    methods: {
      printReport(agenda){
          let cadena="<style>\
            .table1{\
        width:100%;\
        border-collapse: collapse;\
      }\
      table, th, td {\
        border: 1px solid;\
        font-size:10px;\
      }\
      .titulo{\
      text-align:center;\
      font-weight:bold;\
      font-size:20px;\
      text-decoration-line: underline;\
      font-style: oblique;\
      }\
      .tabtitulo{writing-mode:  vertical-rl; width:0.7cm;}\
      .encabezado{\
      text-align:justify;\
      font-size:14px;\
      }\
      .enc{\
        justify-content: space-between;\
      font-size:9px;\
      font-style: oblique;\
      display: flex; \
      flex-wrap: wrap;\
      align-content: flex-end;\
      }\
      .pie{\
      font-size:9px;\
      font-style: oblique;\
      }\
      .contenido{\
      height:0.5cm}\
      .cuadrado{ border-radius: 5px;  border: 1px solid #000000;     background: #000;    width: 0.4cm;    height: 0.4cm; }\
      .tab2 {font-size:10px; font-weight:bold;}\
      </style>\
      <div id='myelement'>\
      <div class='enc'><div><img src='logelectrico.png' style='height:70px; width:60px;  position:absolute; left:20px;'/></div><span>Unidad de Alumbrado Publico y Servicios Electricos</span></div>\
      <br><div class='titulo'>ACTA DE INSTALACION DE<br> LUMINARIAS GESTION 2023</div> <br><br>\
      <div class='encabezado'>En Fecha <b>"+moment(agenda.fecha).format("DD/MM/YYYY")+"</b> se realizo la instalacion de nuevas luminarias en la Junta Vecinal <b>"+agenda.junta+"</b>, Distrito <b>"+agenda.distrito+"</b>\
        con el fin de proveer iluminacion en areas y sectores de acceso publico, en horario nocturno, y brindar seguridad ciudadana, en el marco \
         de las compotencias de la Unidad de Alumbrado Publico y Servicios Electricos del Gobierno Autonomo Municipal de Oruro, para lo cual se detallan\
          las siguientes instalaciones: </div><br>\
          <div class='encabezado' style='text-justify: distribute; '>Cantidad luminarias = <b>"+agenda.cantidad+" (Unid)</b>;  Tipo = <b>"+agenda.tipo+"</b> ; Potencia = <b>"+agenda.potencia +" (Watts)</b></div><br>\
      <table class='table1'><tr><th rowspan=2>COORDENADAS</th><th colspan=3>Codigo</th><th colspan=3>Tipo Poste</th><th colspan=2>Tipo Brazo</th><th>Cable</th><th rowspan=2>Observaciones</th></tr>\
        <tr><th style='width:1.5cm;'>Poste</th> <th style='width:1.5cm;'>luminaria</th><th style='width:1.5cm;'>Fotocelula</th><th class='tabtitulo'>Metalico</th><th class='tabtitulo'>Madera</th><th class='tabtitulo'>Cemento</th><th class='tabtitulo'>Brazo 3m</th><th class='tabtitulo'>Brazo 1.5m</th><th>Metros<br><div style='  display: flex;justify-content: space-between;'>Cu <div class='cuadrado'></div></div><div style='  display: flex; justify-content: space-between;'>Al<div class='cuadrado'></div></div></th></tr>"
      //res.data.forEach(r => {
      
     //   cadena+='<tr><td>'+r.codigo+'</td><td>'+r.cantidad+'</td><td>'+r.material+'</td></tr> '
          
      //})
      for (let i = 0; i < 15; i++) {
        cadena+="<tr class='contenido'><td></td><td rowspan=2></td><td rowspan=2></td><td rowspan=2></td><td rowspan=2></td><td rowspan=2></td><td rowspan=2></td><td rowspan=2></td><td rowspan=2></td><td rowspan=2></td><td rowspan=2></td></tr><tr class='contenido'><td></td></tr>"
        
      }
      cadena+="</table>En constancia firman: <br><br>\
      <table class='table1'><tr><td><span class='tab2'>DIRIGENTE O ENCARGADO SELLO FIRMA:</span><br><br><br><br><br><br><br><div style='font-size:8px; text-align:center;'>"+agenda.dirigente+"<br>Celular: "+agenda.telefono+"</div></td>\
      <td style='vertical-align:top;'><span class='tab2'>SELLO DE LA URBANIZACION Y/O JUNTA VECINAL: </span></td></tr><tr><td style='vertical-align:top;'><span class='tab2'>TRABAJO INSTRUIDO POR: </span></td><td><span class='tab2'>TECNICO:</span> <br><br><br><br><br><br><br><div style='text-align:center'> Tec: "+agenda.user.name+"</div</td></tr></table>\
      <span class='pie'>Este documento es legal para ser llevado a procesos de auditoria a personal de la U.A.P.</span>\
      </div>"
      
      document.getElementById('myelement').innerHTML = cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )

    },
      verDetalle(v){
        this.$q.dialog({
          title: 'DETALLE',
          message: '<ul><li><b>CANTIDAD: </b>'+v.cantidad+'</li><li><b>TIPO: </b>'+v.tipo+'</li><li><b>POTENCIA: </b>'+v.potencia+'</li></ul>',
          html: true
        }).onOk(() => {
          // console.log('OK')
        }).onCancel(() => {
          // console.log('Cancel')
        }).onDismiss(() => {
          // console.log('I am triggered on both OK and Cancel')
        })
      },
      cargaEstado(ag){
        this.agenda2.id=ag.id
         this.agenda2.distrito= ag.distrito
        this.agenda2.junta= ag.junta
        this.agenda2.dirigente= ag.dirigente
        this.agenda2.telefono= ag.telefono
        this.agenda2.fecha= ag.fecha
        this.agenda2.estado= ag.estado
        this.agenda2.nueva= ag.nueva
        this.agenda2.plantado= ag.plantado
        this.agenda2.mtto= ag.mtto
        this.agenda2.repot= ag.repot
        this.agenda2.otros= ag.otros
        this.agenda2.observacion= ag.observacion
        this.dialog_estado=true
      },
      cargaFecha(ag){
        this.agenda2.id = ag.id
        this.agenda2.distrito= ag.distrito
        this.agenda2.junta= ag.junta
        this.agenda2.dirigente= ag.dirigente
        this.agenda2.telefono= ag.telefono
        this.agenda2.fecha= ag.fecha
        this.agenda2.estado= ag.estado
        this.agenda2.nueva= ag.nueva
        this.agenda2.plantado= ag.plantado
        this.agenda2.mtto= ag.mtto
        this.agenda2.repot= ag.repot
        this.agenda2.otros= ag.otros
        this.agenda2.observacion= ag.observacion
        this.dialog_fec=true
      },
      cargaAsigna(ag){
        this.agenda2.id=ag.id
         this.agenda2.distrito= ag.distrito
        this.agenda2.junta= ag.junta
        this.agenda2.dirigente= ag.dirigente
        this.agenda2.telefono= ag.telefono
        this.agenda2.fecha= ag.fecha
        this.agenda2.estado= ag.estado
        this.agenda2.nueva= ag.nueva
        this.agenda2.plantado= ag.plantado
        this.agenda2.mtto= ag.mtto
        this.agenda2.repot= ag.repot
        this.agenda2.otros= ag.otros
        this.agenda2.observacion= ag.observacion
        this.tecnico=ag.user
        this.tecnico.label=this.tecnico.name
        this.dialog_asig=true
      },
      asignar(){
        if(this.tecnico.id==undefined)
          return false

        this.agenda2.user_id=this.tecnico.id
        this.$axios.post( "asignar", this.agenda2).then((res) => {
          this.dialog_asig=false
          this.agenda2={}
          this.Buscar()
          this.$q.notify({
            color: "green-4",
            icon: "info",
            message: "Modificado Tecnico",
          });
        })
      },
      cambioFecha(){
        if(this.agenda2.fecha==undefined || this.agenda2.fecha=="")
          return false

        this.$axios.post( "cambioFecha", this.agenda2).then((res) => {
          this.agenda2={}
          this.dialog_fec=false
          this.Buscar()
          this.$q.notify({
            color: "green-4",
            icon: "info",
            message: "Modificado Fecha",
          });
        })
      },
      cambioEstado(){

        this.$axios.post( "cambioEstado", this.agenda2).then((res) => {
          this.agenda2={}
          this.dialog_estado=false
          this.Buscar()
          this.$q.notify({
            color: "green-4",
            icon: "info",
            message: "Modificado Estado",
          });
        })
      },
      cargaDatos(){
        console.log(this.junta)
        if(this.junta.id==undefined){
          this.agenda.junta=''
          this.agenda.distrito='D1'
          this.agenda.dirigente=''
          this.agenda.telefono=''
          this.agenda.junta_id=''      
   
        }
        else{
          this.agenda.junta_id=this.junta.id      
          this.agenda.distrito=this.junta.distrito
          this.agenda.junta=this.junta.nombre
          this.agenda.dirigente=this.junta.representate
          this.agenda.telefono=this.junta.celular

        }
      },
      cargaDatos2(){
        console.log(this.junta)
        if(this.junta.id==undefined){
    

          this.agenda2.junta=''
          this.agenda2.dirigente=''
          this.agenda2.telefono=''
          this.agenda2.junta_id=''      
        }
        else{

          this.agenda2.junta_id=this.junta.id      
          this.agenda2.distrito=this.junta.distrito
          this.agenda2.junta=this.junta.nombre
          this.agenda2.dirigente=this.junta.representate
          this.agenda2.telefono=this.junta.celular
        }
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
      regJV() {
        this.$q.loading.show();
        this.$axios.post( "junta", this.junta2).then((res) => {
          // console.log(res.data)
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "JV Agregado",
          });
          this.junta2={}
          this.jvDialog = false;
          this.listJunta();
        }).catch(err=>{
          console.log(err.response.data);
          this.$q.notify({
            message:err.response.data.message,
            icon:'close',
            color:'red'
          })
          this.$q.loading.hide()
        })
      },
      filterFn (val, update) {
        if (val === '' || val===null) {
          update(() => {
            this.juntas = this.jfilter
          })
          return
        }

        update(() => {
          const needle = val.toLowerCase()
          this.juntas = this.jfilter.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
        })
      },

      Buscar() {
        this.$q.loading.show();
        this.data=[]
        this.$axios.post("listAgenda",{ini:this.ini,fin:this.fin,estado:this.estado}).then((res) => {
          console.log(res.data)
          res.data.forEach(r => {
            if(r.user_id==undefined || r.user_id==null)
              r.tecnico=''
            else
              r.tecnico=r.user.name
            switch (r.estado) {
              case 'EN PROCESO':
                r.color='#FF6948'
                break;
              case 'REALIZADO':
                r.color='yellow'
                break;
              case 'PENDIENTE':
                r.color='#FF48FF'
                break;
              case 'SUSPENDIDO':
                r.color='#A8A1C9'
                break;
              default:
                r.color='white'

                break;
            }
            this.data.push(r)
          })
          this.$q.loading.hide()
        });
      },

      listJunta() {
        this.juntas=[{label:''}]
        this.$q.loading.show();
        this.$axios.get("junta").then((res) => {
          //console.log(res.data)
          res.data.forEach(r => {
            r.label=r.nombre+' ' +r.distrito
            this.juntas.push(r)
          });
          this.$q.loading.hide();
          this.jfilter=this.juntas
          this.junta=this.juntas[0]
        });
      },

  impresion() {
        this.$q.loading.show();
        this.$axios.post("datoImp",{ini:this.ini,fin:this.fin}).then((res) => {
        let dataimp = [
  {
    sheet: "Cronograma",
    columns: [
      { label: "CAT PROD", value: "codigo" }, // Top level data
      { label: "NOMBRE DE LA ACTIVIDAD", value: "actividad" }, // Top level data
      { label: "DISTRITO", value: "distrito" }, // Top level data
      { label: "BENEFICIARIO", value: "junta" }, // Top level data
      { label: "UBICACION", value: "zona" }, // Top level data
      { label: "SUPERVISOR", value: "" }, // Top level data
      { label: "AVANCE FISICO DEL PERIODO", value: "valor1" }, // Top level data
      { label: "AVANCE FISICO ACUMULADO", value: "valor2" }, // Top level data
      { label: "CANTIDAD", value: "total" }, // Top level data
      { label: "UNIDAD", value: "unidad" }, // Top level data
      { label: "DESCRIPCION", value: "tipo" }, // Top level data
      { label: "OBSERVACION", value: "detalle" }, // Top level data
    ],
    content: res.data
  },

]

let settings = {
  fileName: "Cronograma", // Name of the resulting spreadsheet
  extraLength: 6, // A bigger number means that columns will be wider
  writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
}

xlsx(dataimp, settings) // Will download the excel file

          console.log(res.data)
          this.$q.loading.hide();
        });
      },
      exportExcel() {
        this.$q.loading.show();
        let dataimp = [
  {
    sheet: "Cronograma",
    columns: [
      { label: "DISTRITO", value: row=>row.junta.distrito }, // Top level data
      { label: "BENEFICIARIO", value: row=>row.junta.nombre }, // Top level data
      { label: "CELULAR", value: row=>row.junta.celular }, // Top level data
      { label: "UBICACION", value: row=>row.junta.zona }, // Top level data
      { label: "CANTIDAD", value: "cantidad" }, // Top level data
      { label: "FECHA", value: "fecha" }, // Top level data
      { label: "DESCRIPCION", value: "tipo" }, // Top level data
      { label: "OBSERVACION", value: "descripcion" }, // Top level data
    ],
    content: this.data
  },

]

let settings = {
  fileName: "Planificacion", // Name of the resulting spreadsheet
  extraLength: 6, // A bigger number means that columns will be wider
  writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
}

xlsx(dataimp, settings) // Will download the excel file

          this.$q.loading.hide();
},
      editRow(item) {
        this.agenda2={}
        this.agenda2.id=item.row.id
        this.agenda2.distrito= item.row.distrito
        this.agenda2.junta= item.row.junta
        this.agenda2.dirigente= item.row.dirigente
        this.agenda2.telefono= item.row.telefono
        this.agenda2.fecha= item.row.fecha
        this.agenda2.estado= item.row.estado
        this.agenda2.nueva= item.row.nueva
        this.agenda2.plantado= item.row.plantado
        this.agenda2.mtto= item.row.mtto
        this.agenda2.repot= item.row.repot
        this.agenda2.otros= item.row.otros
        this.agenda2.observacion= item.row.observacion
      //  this.junta=this.agenda2.junta
        //this.junta.label = this.junta.nombre +' ' +this.junta.distrito
        //this.tipo=this.lista.find(t=>{return t.tipo==this.dato2.tipo})
        this.dialog_mod = true;
      },
      deleteRow(item) {
        this.dato2 = item.row;
        this.dialog_del = true;
      },
      onSubmit() {
        this.$q.loading.show();
        if(this.junta.id!=undefined)
        this.agenda.junta_id=this.junta.id
        this.$axios.post( "agenda", this.agenda).then((res) => {
          // console.log(res.data)
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Creado correctamente",
          });
          this.agenda={fecha:date.formatDate(new Date(), "YYYY-MM-DD"),distrito:'D1'}
          this.junta={label:''}
          this.alert = false;
          this.Buscar();
          this.$q.loading.hide()
        })
      },
      onMod() {
        this.$q.loading.show();
        this.$axios.put("agenda/" + this.agenda2.id, this.agenda2).then((res) => {
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Modificado correctamente",
          });
          this.dialog_mod = false;
          this.Buscar();
        });
      },
      onDel() {
        this.$q.loading.show();
        this.$axios.delete("cronograma/" + this.dato2.id)
          .then((res) => {
            this.$q.notify({
              color: "green-4",
              textColor: "white",
              icon: "cloud_done",
              message: "Eliminado correctamente",
            });
            this.dialog_del = false;
            this.buscar();
          this.$q.loading.hide()
        })
      },
      onReset() {
        this.dato={};
      },
    },
  };
  </script>
