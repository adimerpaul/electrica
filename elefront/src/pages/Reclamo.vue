<template>
  <q-page>
    <div>
    <gmap-map
    :center="center"
    :zoom="zoom"
    style="width: 100%; height: 500px"
    :options="{
   zoomControl: true,
   mapTypeControl: true,
   scaleControl: false,
   streetViewControl: false,
   rotateControl: false,
   fullscreenControl: true,
   disableDefaultUi: false
 }"
  >
    <!-- use the default slot to pass markers to it -->

    <gmap-marker
      v-for="m in puntos" :key="m.id"
      :position="{'lat':m.lat,'lng':m.lng}"
      :clickable="true"
      :draggable="false"
      :title="m.material"
      :optimized="true"
      @click="center={'lat':m.lat,'lng':m.lng};punto=m;frmmodalpunto(m); "
    >
    </gmap-marker>
    </gmap-map>
  </div>

<div>URL: {{url}}</div>
<div>cedula: {{type}}</div>
    <q-table :data="puntos"  :columns="colums" :filter="filter">
      <template v-slot:body-cell-nombre="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
          {{props.row.persona.nombre}}-{{props.row.persona.telefono}}
        </q-td>
      </template>
      <template v-slot:body-cell-nroposte="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
          {{props.row.poste.nroposte}}
        </q-td>
      </template>
      <template v-slot:body-cell-ubicacion="props">
        <q-td :props="props">
          <q-btn :color="props.row.estado=='ACTIVO'?'primary':'negative'" @click="clickclientes(props.row)"  icon="place" size="xs" />
          <q-btn color="info" icon="print" dense  @click="printReclamo(props.row)" />
        </q-td>
      </template>
      <template v-slot:top-right>
        <q-btn icon="refresh" color="info" @click="mispuntos"/>
        <q-btn icon="adjust" color="negative" @click="centro"/>
        <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
    </q-table>
    <q-dialog v-model="modalpunto" full-width>
      <q-card>
        <q-card-section>
          <div class="text-h6">Nro poste {{poste.nroposte}} lat:{{poste.lat}} lng:{{poste.lng}}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form @submit="updatedenuncia">
            <div class="row">
              <div class="col-xs-12 col-md-6">
                <q-input dense outlined :disable="true" label="Nombre" v-model="persona.nombre" />
              </div>
              <div class="col-xs-12 col-md-3">
                <q-input dense outlined :disable="true" label="CI" v-model="persona.ci" />
              </div>
              <div class="col-xs-12 col-md-3">
                <q-input dense outlined :disable="true" label="Telefono" v-model="persona.telefono" />
              </div>

              <div class="col-xs-12 col-md-6">
                <q-input dense outlined :disable="true" type="textarea" label="Reclamo" v-model="punto.reclamo" />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined :disable="true" label="Fecha" v-model="punto.fecha" />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined :disable="true" label="Hora" v-model="punto.hora" />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined :disable="true" v-model="poste.luminaria" type="text" label="Luminaria" />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined :disable="true" v-model="poste.material" type="text" label="Material" />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined :disable="true" v-model="poste.altura" type="text" label="Altura" />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined :disable="true" v-model="poste.potencia" type="text" label="Potencia" />
              </div>
              <div class="col-6">
                <q-input dense outlined :disable="true" label="Estado" v-model="punto.estado" />
              </div>
              <div class="col-12 flex flex-center">
                <q-btn label="Generar Ruta" class="full-width" color="primary" icon="map" type="a" target="_blank" :href="'https://www.google.com.bo/maps/place/'+poste.lat+','+poste.lng+'/@'+poste.lat+','+poste.lng+',17z/data=!3m1!4b1!4m6!3m5!1s0x0:0xeda9371aeb8c1574!7e2!8m2!3d-17.981432!4d-67.1061122?hl=es'"/>
              </div>
              <div class="col-12">
                <q-input required dense outlined label="Tecnico" v-model="punto.tecnico" />
              </div>
              <div class="col-12">
                <q-input required dense outlined type="textarea" label="Actividad" v-model="punto.actividad" />
              </div>
              <div class="row">
                <div class="col-md-3 col-xs-12">
                  <div class="text-h6">LAMPARA W</div>
                  <q-input outlined dense v-model="punto.l70" type="text" label="70W" />
                  <q-input outlined dense v-model="punto.l150" type="text" label="150W" />
                  <q-input outlined dense v-model="punto.l250" type="text" label="250W" />
                  <q-btn dense color="yellow" icon="check" @click="regActividad" />
                </div>
                <div class="col-md-3 col-xs-12">

                <div class="text-h6">REACTOR W</div>
                  <q-input outlined dense v-model="punto.r70" type="text" label="70W" />
                  <q-input outlined dense v-model="punto.r150" type="text" label="150W" />
                  <q-input outlined dense v-model="punto.r250" type="text" label="250W" />
                </div>
                <div class="col-md-3 col-xs-12">

                <div class="text-h6">FOTOCELULA</div>
                  <q-input outlined dense v-model="punto.foto" type="text" label="Foto" />
                  <q-input outlined dense v-model="punto.base" type="text" label="Base" />
                </div>
                <div class="col-md-3 col-xs-12">

                <div class="text-h6">VARIOS</div>
                  <q-input outlined dense v-model="punto.ignitor" type="text" label="IGNITOR" />
                  <q-input outlined dense v-model="punto.cable" type="number" step="0.01" label="CABLE M/L" />
                  <div class="row">
                    <q-toggle false-value="NO"  :label="`Contactor ${punto.contactor}`" true-value="SI" color="green" v-model="punto.contactor" />
                  <q-toggle false-value="NO"  :label="`Termico ${punto.termico}`" true-value="SI" color="green" v-model="punto.termico" />
                </div>
                  <q-input outlined dense v-model="punto.soquet" type="text" label="SOQUET" />
                </div>
              </div>
              <div class="col-md-6 col-xs-6">
                <q-input dense outlined type="date" label="Fecha Man" v-model="punto.fechaman" />
              </div>
              <div class="col-md-6 col-xs-6">
                <q-input dense outlined type="time" label="Hora Man" v-model="punto.horaman" />
              </div>
              <div class="col-12 flex flex-center">
                <q-btn type="submit" label="Realizar mantenimiento" class="full-width" color="positive" icon="construction"/>
              </div>
            </div>
          </q-form>
        </q-card-section>

      </q-card>
    </q-dialog>
  <div id="myelement" class="hidden"></div>

    </q-page>
</template>

<script>

import {date} from "quasar";
import {Printd} from "printd";
import { env } from "process";
const QRCode = require('qrcode')
export default {
  data () {
    return {
      modalpunto:false,
      filter:'',
      url:'https://electrica.gamo.cf/#/consulta',
      poste:{},
      persona:{},
      qrImage2:'',
      qrImage:'',
      colums:[
        {name:"nroposte",label:"nroposte",field:"nroposte"},
        {name:"nombre",label:"nombre",field:"nombre"},
        // {name:"potencia",label:"potencia",field:"potencia"},
        {name:"ubicacion",label:"ubicacion",field:"ubicacion"},
      ],
      puntos:[],
      puntostabla:[],
      punto:{},
     // url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution:
        '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      zoom: 13,
      center: {lat:-17.970310, lng:-67.111780},
      markerLatLng: [-17.970310, -67.111780],
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
      },
      opts2 : {
        errorCorrectionLevel: 'M',
        type: 'png',
        quality: 0.95,
        width: 50,
        margin: 1,
        color: {
          dark: '#000000',
          light: '#FFF',
        },
      }

    };
  },
  created() {
    this.mispuntos()
  },
  methods:{
    async printReclamo(recl) {
      this.qrImage2 = await QRCode.toDataURL(''+recl.persona.ci, this.opts2)
      this.qrImage = await QRCode.toDataURL(this.url+'/'+recl.persona.ci, this.opts)
      let cadena = "<style>\
      .titulo{\
      font-size: 10px;\
      text-align: center;\
      font-weight: bold;\
      }\
      .titulo2{\
      font-size: 10px;\
      font-weight: bold;\
      text-align: center;\
      }\
            .titulo3{\
      font-size: 9px;\
      text-align: center;\
      width:70%;\
      }\
            .contenido{\
      font-size: 9px;\
      text-align: left;\
      }\
      .conte2{\
      font-size: 9px;\
      text-align: right;\
      }\
      .campotd{\
      text-align: center;\
      }\
      .titder{\
      font-size: 10px;\
      text-align: right;\
      font-weight: bold;\
      }\
      hr{\
  border-top: 1px dashed   ;\
}\
  table{\
    width:100%\
  }\
  h1 {\
    color: black;\
    font-family: sans-serif;\
  }</style>\
    <div id='myelement' style='background-image:url(logo3.png);background-repeat: no-repeat; background-size: 30px 60px;'>\
      <div class='titulo2' >\
        GOBIERNO AUTONOMO MUNICIPAL DE ORURO<br>\
        UNIDAD DE ALUMBRADO PUBLICO <br>Y SERVICIOS ELECTRICOS<br>\
        Reclamo Nro: "+recl.id+"<br>\
        Oruro\
      </div>\
      <hr>\
      <table><tr><td>\
      <table>\
        <tr><td class='titder'>FECHA REGISTRO:</td><td class='contenido'>" + recl.fecha + "</td></tr>\
        <tr><td class='titder'>CI:</td><td class='contenido'>" + recl.persona.ci + "</td></tr>\
        <tr><td class='titder'>NOMBRE:</td><td class='contenido'>" + recl.persona.nombre + "</td></tr>\
        <tr><td class='titder'>TELEFONO:</td><td class='contenido'>" + recl.persona.telefono + "</td></tr>\
      </table></<td> \
      <td>\
        <div style='display: flex;justify-content: center;'>\
        <img src="+this.qrImage2+" >\
        </div>\
      </td></tr></table>\
      <hr>\
      <div class='titulo'>DETALLE RECLAMO</div>\
      <div  class='contenido'>"+recl.reclamo+"</div>\
      <div style='display: flex;justify-content: center;'>\
  <img src="+this.qrImage+" >\
      </div>\
      </div>            "
      document.getElementById('myelement').innerHTML = cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )

    },
    regActividad(){
      this.punto.actividad=''
      if(this.punto.l70!=''&&  this.punto.l70!=undefined && this.punto.l70!=null){ this.punto.actividad+='LAMPARA 70W COD '+ this.punto.l70 +',' }
      if(this.punto.l150!=''&&  this.punto.l150!=undefined && this.punto.l150!=null){ this.punto.actividad+='LAMPARA 150W COD '+ this.punto.l150 +',' }
      if(this.punto.l250!=''&&  this.punto.l250!=undefined && this.punto.l250!=null){ this.punto.actividad+='LAMPARA 250W COD '+ this.punto.l250+','  }
      if(this.punto.r70!=''&&  this.punto.r70!=undefined && this.punto.r70!=null){ this.punto.actividad+=' REACTOR 70W COD '+ this.punto.r70 +',' }
      if(this.punto.r150!=''&&  this.punto.r150!=undefined && this.punto.r150!=null){ this.punto.actividad+=' REACTOR 150W COD '+ this.punto.r150+','  }
      if(this.punto.r250!=''&&  this.punto.r250!=undefined && this.punto.r250!=null){ this.punto.actividad+=' REACTOR 250W COD '+ this.punto.r250 +',' }
      if(this.punto.foto!=''&&  this.punto.foto!=undefined && this.punto.foto!=null){ this.punto.actividad+=' FOTOCELULA FOTO  '+ this.punto.foto +',' }
      if(this.punto.base!=''&&  this.punto.base!=undefined && this.punto.base!=null){ this.punto.actividad+=' FOTOCELULA BASE  '+ this.punto.base+','  }

      if(this.punto.ignitor!=''&&  this.punto.ignitor!=undefined && this.punto.ignitor!=null){ this.punto.actividad+=' IGNITOR COD '+ this.punto.ignitor +',' }
      if(this.punto.cable!=''&&  this.punto.cable!=undefined && this.punto.cable!=null){ this.punto.actividad+=' CABLE M/L '+ this.punto.cable +',' }
      if(this.punto.contactor!=''&&  this.punto.contactor!=undefined && this.punto.contactor!=null && this.punto.contactor!='NO'){ this.punto.actividad+=' SI CONTACTOR,' }
      if(this.punto.termico!=''&&  this.punto.termico!=undefined && this.punto.termico!=null && this.punto.termico!='NO'){ this.punto.actividad+=' SI TERMICO,' }
      if(this.punto.soquet!=''&&  this.punto.soquet!=undefined && this.punto.soquet!=null){ this.punto.actividad+=' soquet COD '+ this.punto.soquet +',' }
    },
    updatedenuncia(){
      this.$q.loading.show()
      this.punto.supervisor=this.$store.state.login.user.name
      this.punto.estado='ATENDIDO'
      //console.log(this.punto.id)
      this.modalpunto=false
      this.$axios.put('reclamo/1',this.punto).then(res=>{
        console.log(res.data)
        this.mispuntos()
      })
    },
    frmmodalpunto(p){
       console.log(p)
       this.modalpunto=true

        this.punto=p
        this.persona=p.persona
        this.poste=p.poste
    },
    clickclientes(c){
      // console.log(c)
      // this.center = [-17.970310, -67.111780]
      this.center = {lat:c.lat, lng:c.lng}
      this.zoom= 18
    },
    // cambioestado(){
    //   // console.log(this.punto)
    //   this.$axios.put('punto/'+this.punto.id,this.punto)
    // },
    centro(){
      this.center = [-17.970310, -67.111780]
      this.zoom= 13
    },
    mispuntos(){
      this.$q.loading.show()
      this.$axios.get('listAtencion').then(res=>{
        console.log(res.data)
        this.puntos=[]
        res.data.forEach(r=>{
          if(r.poste.nroposte==null) r.poste.nroposte=''
          r.lat=r.poste.lat
          r.lng=r.poste.lng
          r.fechaman=date.formatDate(new Date(),'YYYY-MM-DD')
          r.horaman=date.formatDate(new Date(),'HH:mm')
          this.puntos.push(r)
        })
        this.$q.loading.hide()
        console.log(this.puntos)
      })
    }
  },
}
</script>
