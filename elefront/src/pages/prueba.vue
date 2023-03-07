<template>
<q-layout view="lHh Lpr lFf">
  <q-header elevated>
    <q-toolbar>


      <q-toolbar-title>
        <q-img src="logo.png" style="width: 30px"/>
      </q-toolbar-title>
      <div>
      </div>
    </q-toolbar>
  </q-header>
  <q-page-container>
    <q-page >
    <div class="row" :style="backgroundDiv">
      <div class="q-pa-md col-md-6 col-xs-12">
        <div  style="width:100%;">
        <!--q-banner rounded class="bg-green text-white text-h5" style="width:100%; text-align: center; ">-->
         <q-btn class="text-h6" color='green' @click="$router.replace({ path: '/consulta' })">IR CONSULTA RECLAMOS</q-btn>

    </div>
        <div class="q-pa-xs"><q-input bg-color="blue-grey-1" outlined rounded v-model="persona.ci"
          label="Cedula de Identidad *" @change="buscar"/></div>
        <div class="q-pa-xs"><q-input bg-color="blue-grey-1" outlined rounded v-model="persona.nombre" type="text" label="Nombre Completo *" /></div>
        <div class="q-pa-xs"><q-input bg-color="blue-grey-1" outlined rounded v-model="persona.telefono" type="text" label="Celular *" /></div>
        <div class="q-pa-xs row"><div class="col-10"><q-input bg-color="blue-grey-1" dense outlined rounded v-model="nposte" type="text" label="Nro Poste" /></div> <q-btn color="green"  icon="search" @click="buscarPoste" /></div>

      </div>
      <div class="q-pa-md   col-md-6 col-xs-12">
        <div style="color:white; font-weight: bold; background: gray; font-size: 10px;"> * Puede arrastrar su ubicacion o hacer click en cualquier parte del mapa</div>
        <div style="color:white; font-weight: bold; background: gray; font-size: 10px;" > * Hacer click al punto de su ubicacion para cargar los postes cercanos</div>
        <div style="color:white; font-weight: bold; background: gray; font-size: 10px;"> * Click sobre alguno de los postes para registrar su reclamo</div>
<br>
        <l-map style="height: 50vh" :zoom="zoom" :center="center" @click="cargarpunto">
          <l-tile-layer :url="styleMap?`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`:`https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}`"
                      layer-type="base"
                      name="OpenStreetMap"></l-tile-layer>
        <l-marker :lat-lng="[lat,lng]" draggable  @moveend="updateCoordinates" @click="cargarUbicacion" title="Usted esta Aqui"
        >
        <l-icon
          icon-url="http://maps.google.com/mapfiles/ms/icons/ylw-pushpin.png"
        />
      </l-marker>
        <l-marker v-for="m in datos" :key="m.id" :lat-lng="[m.lat,m.lng]" @click="center=[m.lat,m.lng];punto=m;seleccionar(); ">        <l-icon
          icon-url="http://maps.google.com/mapfiles/ms/icons/red-dot.png"
        /></l-marker>

        <l-control position="topright" >
                      <q-btn @click="styleMap=!styleMap" icon="map" class="bg-primary text-white" dense round></q-btn>
                    </l-control>
    </l-map>
    </div>
   <!--
      <div class="q-pa-md   col-md-6 col-xs-12">
        <gmap-map
    :center="center"
    :zoom="zoom"
    style="width: 100%; height: 500px"
    @click="handleMarkerDrag"
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
  <gmap-marker
      :position="ubicacion"
      :draggable="true"
      :clickable="true"
      @drag="updateCoordinates"
      @click="cargarUbicacion"
      title="Usted esta Aqui"
      :optimized=true
      icon="http://maps.google.com/mapfiles/ms/icons/ylw-pushpin.png"
    >
    </gmap-marker>
    <gmap-marker
      v-for="m in datos" :key="m.id"
      :position="{'lat':m.lat,'lng':m.lng}"
      :clickable="true"
      :draggable="false"
      :title="m.material"
      :optimized=true
      @click="center={'lat':m.lat,'lng':m.lng};punto=m;seleccionar(); "
    >
    </gmap-marker>
</gmap-map>
      </div>-->
    </div>
    <q-dialog v-model="dialogReclamo" full-width>
      <q-card >
        <q-card-section>
          <div class="text-h6">Datos de Alumbrado {{punto.nroposte}}</div>

          <div class="row">
            <div class="col-md-3 col-xs-12"><b>MATERIAL :</b> {{punto.material}}</div>
            <div class="col-md-3 col-xs-12"><b>LUMINARIA :</b> {{punto.luminaria}}</div>
            <div class="col-md-3 col-xs-12"><b>ALTURA :</b> {{punto.altura}}</div>
            <div class="col-md-3 col-xs-12"><b>POTENCIA :</b> {{punto.potencia}}</div>
            <div class="col-12"><b>OBSERVACION : </b>{{punto.observacion}}</div>
          </div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-input dense outlined v-model="denuncia.reclamo" label="Detalle del reclamo" type="textarea"/>
        </q-card-section>

        <q-card-actions align="right" class="text-primary">
          <q-btn flat label="Cancel" color="red" v-close-popup icon="cancel" />
          <q-btn flat label="Registrar" color="green" @click="registrarReclamo" icon="send" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
  <div id="myelement" class="hidden"></div>

    </q-page-container>
  </q-layout>
</template>

<script>
import {date} from "quasar";
import {Printd} from "printd";
import path from "path";
const QRCode = require('qrcode')

export default {
  name: 'PageIndex',
  data(){
    return {
      styleMap: true,

      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      url2: 'https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',
      attribution:
        '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      zoom: 16,
      markerLatLng: [-17.970310, -67.111780],
      persona:{},
      punto:{},
      denuncia:{},
      nposte:'',
      dialogReclamo:false,
      lat:0,
      lng:0,
      marker:{},
      postes:[],
      urlbase:'https://electrica.gamo.cf/#/consulta',

      datos:[],
      ubicacion:[-17.970310, -67.111780],
      center: [-17.970310, -67.111780],
      backgroundDiv : {
        backgroundImage : 'url(bg.jpg)',
        backgroundRepeat : 'no-repeat',
        backgroundSize : '100% 100%',
      },
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
    }
  },
  mounted(){
    this.geolocate()
    //this.mispuntos()
  },
  methods:{
    async printReclamo(recl) {
      //this.qrImage2 = await QRCode.toDataURL(''+recl.persona.ci, this.opts2)
      this.qrImage = await QRCode.toDataURL(this.urlbase+'/'+recl.persona.ci, this.opts)
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
      font-size: 10px;\
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
      <table>\
        <tr><td class='titder'>FECHA REGISTRO:</td><td class='contenido'>" + recl.fecha + "</td></tr>\
        <tr><td class='titder'>CI:</td><td class='contenido'>" + recl.persona.ci + "</td></tr>\
        <tr><td class='titder'>NOMBRE:</td><td class='contenido'>" + recl.persona.nombre + "</td></tr>\
        <tr><td class='titder'>TELEFONO:</td><td class='contenido'>" + recl.persona.telefono + "</td></tr>\
      </table> \
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
    handleMarkerDrag(e) {
      this.ubicacion = { lat: e.latLng.lat(), lng: e.latLng.lng() };
    },
    cargarUbicacion(){
      this.$axios.post('calcularArea',{lat:this.lat,lng:this.lng,distancia:200}).then(res=>{
          console.log(res.data)
          this.datos=res.data
      })
    },
    cargarpunto(value){
      console.log(value.latlng)
          //return false
          this.lat= value.latlng.lat
              this.lng= value.latlng.lng
            this.ubicacion = [this.lat,this.lng];
            this.center=this.ubicacion
    },
    updateCoordinates(l) {
              this.lat= l.target._latlng.lat
              this.lng= l.target._latlng.lng
            this.ubicacion = [this.lat,this.lng];
            this.center=this.ubicacion

        },
    registrarReclamo(){
      if(this.denuncia.reclamo==undefined || this.denuncia.reclamo==''){
        this.$q.notify({
          message: 'Ingrese su Reclamo',
          color: 'red',
          icon:'info'
        })
        return false
      }
      if(this.persona.id==undefined) this.persona.id=''
      this.denuncia.persona=this.persona
      this.denuncia.fecha=date.formatDate(new Date(),'YYYY-MM-DD')
      this.denuncia.hora=date.formatDate(new Date(),'HH:mm')
      this.denuncia.punto=this.punto
      this.$axios.post('reclamo',this.denuncia).then(res=>{
        console.log(res.data)
        this.printReclamo(res.data)
        this.dialogReclamo=false
        this.$q.notify({
          message: 'Su Reclamo fue Registrado',
          color: 'green',
          icon:'info'
        })
      }).catch(err=>{
        this.$q.notify({
          message:err.response.data.message,
          icon:'error',
          color:'red'
        })
      })
      this.denuncia.reclamo=''
      this.persona={}
      this.denuncia={}
      this.nposte=''
    },
    seleccionar(){
        if(this.persona.ci==undefined ||
           this.persona.nombre==undefined ||
           this.persona.telefono==undefined){

          this.$q.notify({
          message: 'Ingrese su Datos',
          color: 'red',
          icon:'info'
        })
        return false
        }
        this.zoom=19
        this.dialogReclamo=true
    },
    cargar(dist){

    this.$q.loading.show()

      switch (dist) {
        case 'D1': this.datos=this.d1;break;
        case 'D2': this.datos=this.d2;break;
        case 'D3': this.datos=this.d3;break;
        case 'D4': this.datos=this.d4;break;
        case 'D5': this.datos=this.d5;break;
        default:
          this.datos=[]
          break;
      }

    this.$q.loading.hide()

    },
    buscarPoste(){
      this.datos=[]
      this.$axios.get('buscarPoste/'+this.nposte).then(res=>{
        if(res.data.length>0){

          this.datos.push(res.data[0])
          this.ubicacion={lat:res.data[0].lat,lng:res.data[0].lng};
          this.center=this.ubicacion
          this.zoom=18;
        }
      })

    },
    buscar(){
      let carnet=this.persona.ci
      this.$axios.post('buscarPersona/'+carnet).then(res=>{
        console.log(res.data)
        if(res.data){
          this.persona=res.data
        }
        else{
          this.persona={}
          this.persona.ci=carnet
        }

      })
    },

    async geolocate() {
      this.ubicacion= [0, 0]
      // check if API is supported
      console.log(navigator.geolocation)
      if (navigator.geolocation) {

      navigator.geolocation.getCurrentPosition((position) => {
        console.log(position)
        this.lat=position.coords.latitude
          this.lng=position.coords.longitude

        this.ubicacion=[this.lat,this.lng];
        this.center=this.ubicacion
        this.zoom=17;
        this.$axios.post('calcularArea',{lat:this.lat,lng:this.lng,distancia:200}).then(res=>{
          console.log(res.data)
          this.datos=res.data
      })

      },function(error){
// El segundo parámetro es la función de error
    switch(error.code) {
        case error.PERMISSION_DENIED:
            console.log("El usuario denegó el permiso para la Geolocalización")
            break;
        case error.POSITION_UNAVAILABLE:
            console.log("La ubicación no está disponible.")
            break;
        case error.TIMEOUT:
            console.log("Se ha excedido el tiempo para obtener la ubicación")
            break;
        case error.UNKNOWN_ERROR:
            console.log(" Un error desconocido.")
            break;
    }
  });}

    },
  }
}
</script>
