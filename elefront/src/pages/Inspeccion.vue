<template>
  <q-page class="q-pa-md q-gutter-md">
    <div>INSPECCION O MANTENIMIENTO </div>
    <div class="row">
      <div class="col-4"><q-input v-model="fecha" type="date" label="Fecha" /></div>
      <div class="col-4"><q-btn color="primary" icon="search" label="OK" @click="Buscar" /></div>
    </div>
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

    <q-table :data="puntos"  :columns="colums" :filter="filter">
      <template v-slot:body-cell-nroposte="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
          {{props.row.nroposte}}
        </q-td>
      </template>
      <template v-slot:body-cell-ubicacion="props">
        <q-td :props="props">
          <q-btn :color="props.row.estado=='ACTIVO'?'primary':'negative'" @click="clickclientes(props.row)"  icon="place" size="xs" />
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
          <q-form >
            <div class="row">
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
      fecha:date.formatDate(new Date(),'YYYY-MM-DD'),
      filter:'',
      url:'https://electrica.gamo.cf/#/consulta',
      poste:{},
      persona:{},
      qrImage2:'',
      qrImage:'',
      colums:[
        {name:"nroposte",label:"nroposte",field:"nroposte"},
         {name:"potencia",label:"potencia",field:"potencia"},
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
    //this.mispuntos()
    this.Buscar()
  },
  methods:{
    Buscar(){
      this.$axios.post('listplan',{fecha:this.fecha}).then(res=>{
        console.log(res.data)
        res.data.forEach(r=>{
          if(r.nroposte==null)
          r.nroposte=''
          this.puntos.push(r)
        })
      })

    },

    frmmodalpunto(p){
       console.log(p)
       this.modalpunto=true
        this.poste=p
    },
    clickclientes(c){
      this.center = {lat:c.lat, lng:c.lng}
      this.zoom= 18
    },
    centro(){
      this.center = [-17.970310, -67.111780]
      this.zoom= 13
    },
  },
}
</script>
