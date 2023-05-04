<template>
  <q-page>
    <div class="col-12">
      <l-map style="height: 50vh" :zoom="zoom" :center="center" >
          <l-tile-layer :url="styleMap?`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`:`https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}`"
                      layer-type="base"
                      name="OpenStreetMap" :attribution="attribution"></l-tile-layer>

        <l-marker v-for="m in puntos" :key="m.id" :lat-lng="[m.lat,m.lng]" @click="center=[m.lat,m.lng];;punto=m;frmmodalpunto(m); ">        <l-icon
          icon-url="http://maps.google.com/mapfiles/ms/icons/red-dot.png"
        /></l-marker>

        <l-control position="topright" >
                      <q-btn @click="styleMap=!styleMap" icon="map" class="bg-primary text-white" dense round></q-btn>
                    </l-control>
    </l-map>
    </div>
  <!--  <div>
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
     use the default slot to pass markers to it -

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
-->
    <q-table :data="puntos"  :columns="colums" :filter="filter">
      <template v-slot:body-cell-nombre="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
          {{props.row.persona.nombre}} - {{props.row.persona.telefono}}
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
        <q-btn icon="refresh" color="info" @click="mispuntos "/>
        <q-btn icon="adjust" color="negative" @click="centro"/>
        <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:top-left>
        <div class="row">
          <q-select style="width: 200px;
          " outlined dense  v-model="per" :options="personas"/>
          <q-btn color="green" dense icon="search" @click="listReclamos" />
      </div>
      </template>
    </q-table>

    <q-dialog v-model="modalpunto" full-width>
      <q-card>
        <q-card-section>
          <div class="text-h6">Nro poste {{poste.nroposte}} lat:{{poste.lat}} lng:{{poste.lng}}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form @submit="regmatto">
            <div class="row">
              <div class="col-xs-12 col-md-6">
                <b>Nombre: </b>{{ persona.nombre }}
              </div>
              <div class="col-xs-12 col-md-3">
                <b>CI: </b> {{ persona.ci }}
              </div>
              <div class="col-xs-12 col-md-3">
                <b>Telefono: </b>{{ persona.telefono }}
              </div>

              <div class="col-xs-9">
                <b>Reclamo: </b>{{ punto.reclamo }}
              </div>
              <div class="col-md-3 col-xs-6">
                <b>Fecha Hora:</b> {{ punto.fecha }} {{ punto.hora }}
              </div>
            </div>
            <hr>

            <div class="row">
              <div class="col-md-3 col-xs-6">
                <b>Luminaria: </b> {{ poste.luminaria }}
              </div>
              <div class="col-md-3 col-xs-6">
                <b>Material: </b>{{ poste.material }}
              </div>
              <div class="col-md-3 col-xs-6">
                <b>Altura: </b>{{ poste.altura }}
              </div>
              <div class="col-md-3 col-xs-6">
                <b>Potencia: </b>{{ poste.potencia }}
              </div>
              <div class="col-md-12">
                <b>Observacion: </b>{{ poste.observacion }}
              </div>
              </div>
              <div class="col-12 flex flex-center">
                <q-btn label="Generar Ruta" class="full-width" color="primary" icon="map" type="a" target="_blank" :href="'https://www.google.com.bo/maps/place/'+poste.lat+','+poste.lng+'/@'+poste.lat+','+poste.lng+',17z/data=!3m1!4b1!4m6!3m5!1s0x0:0xeda9371aeb8c1574!7e2!8m2!3d-17.981432!4d-67.1061122?hl=es'"/>
              </div>

              <div class="col-12">
                <q-input required dense outlined type="textarea" label="Actividad" v-model="punto.actividad" />
              </div>
              <div class="row ">
                <div class="col-md-3 col-xs-6">
                  <q-select dense v-model="material" :options="listMaterial" label="Materiales" outlined @input="buscar()" />
                </div>
                <div class="col-md-3 col-xs-6">
                  <q-select dense v-model="codigo" :options="codigos" label="Codigo" outlined />
                </div>
                <div class="col-md-3 col-xs-6">
                  <b>Disponible:</b>{{ codigo.saldo }}
                </div>
                <div class="col-md-2 col-xs-4">
                  <q-input v-model="cantidad" type="number" label="Cantidad" required outlined dense/>
                </div>
                <div class="col-md-1 col-xs-2 q-pa-xs">
                  <q-btn color="green" icon="add_circle" dense @click="agregar" />
                </div>
                <div class="col-12">
                  <q-table
                    title="DETALLE"
                    :data="detalle"
                    :columns="coldetalle"
                    row-key="name"
                    dense
                  >
                  <template v-slot:body-cell-opcion="props">
                    <q-td :props="props" >
                      <q-btn color="red" icon="delete"  size="xs" round @click="delDetalle(props.rowIndex)" />
                    </q-td>
                  </template>
                  </q-table>
                </div>
              </div>
              <div class="row">
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
      styleMap: true,
      personas:[],
      detalle:[],
      material:{},
      codigo:{},
      codigos:[],
      fechaplan:date.formatDate(new Date(),'YYYY-MM-DD'),
      filter:'',
      url:'https://electrica.gamo.cf/#/consulta',
      poste:{},
      persona:{},
      per:{label:''},
      qrImage2:'',
      cantidad:1,
      qrImage:'',
      colums:[
        {name:"nroposte",label:"nroposte",field:"nroposte"},
        {name:"nombre",label:"nombre",field:"nombre"},
        // {name:"potencia",label:"potencia",field:"potencia"},
        {name:"ubicacion",label:"ubicacion",field:"ubicacion"},
      ],
      coldetalle:[
        {label:'OP',field:'opcion',name:'opcion'},
        {label:'MATERIAL',field:'material',name:'material'},
        {label:'CODIGO',field:row=>row.inventario.codigo,name:'codigo'},
        {label:'CANT',field:'uso',name:'uso'}
      ],
      puntos:[],
      puntostabla:[],
      listMaterial:[],
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
    this.cargarMaterial()
  },
  methods:{
    listPersona(){
      this.personas=[]
      this.$axios.get('listPerReclamo').then(res=>{
        console.log(res.data)
        res.data.forEach(r => {
          r.label=r.nombre
          this.personas.push(r)
        });
      })

    },
    listReclamos(){
      if(this.per.id==undefined || this.per.id=='')
        return false
      this.$axios.get('listAtencion2/'+this.per.id).then(res=>{
        this.$q.loading.show()
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
      })
    },
    regmatto(){
      this.$axios.post('reclamofin',{punto:this.punto,detalle:this.detalle}).then(res=>{
        console.log(res.data)
        this.modalpunto=false
        this.mispuntos()
        this.cargarMaterial()

      })
    },
    delDetalle(ind){
      this.detalle.splice(ind,1)
      this.llenarActividad
    },
    agregar(){
      if(this.cantidad==undefined || this.cantidad=='' || this.cantidad<=0){
        this.cantidad=1
        return false
      }

      if(this.cantidad>this.codigo.saldo)
      {
        this.cantidad=this.codigo.saldo
      }
      if(!this.detalle.find(x=> x.id=== this.codigo.id)){
        this.codigo.uso=this.cantidad
        this.detalle.push(this.codigo)
        this.llenarActividad()
      }

    },
    llenarActividad(){
      let cadena='Cambio o instalacion materiales: '
      if(this.detalle.length==0)
        { cadena=''}
      this.detalle.forEach(x => {
        cadena+=' '+x.material+' Cod: '+ x.inventario.codigo+', '
      });
      this.punto.actividad= cadena
    },
    buscar(){
      this.codigos=this.material.lista
      this.codigo=this.material.lista[0]
          this.cantidad=1

    },
    cargarMaterial(){
      this.$axios.post('disponible').then(res=>{

        res.data.forEach(r => {
          r.label=r.inventario.codigo
          let p=this.listMaterial.find(x=>x.id===r.material_id)
          if(! p)
          {this.listMaterial.push({'id':r.material_id,label:r.material,lista:[r]})}
          else{
              p.lista.push(r)
          }
        });
        console.log(this.listMaterial)
        if(this.listMaterial.length>0){
        this.material=this.listMaterial[0]
        this.codigo=this.material.lista[0]
        this.codigos=this.material.lista
          this.cantidad=1
        }
        else{
          this.material={label:''}
        this.codigo={label:''}
          this.cantidad=0
        }
      })

    },
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
      if(this.fechaplan!=undefined && this.fechaplan!='')
        this.punto.fechaplan=this.fechaplan
      //console.log(this.punto.id)
      this.modalpunto=false
      this.$axios.put('reclamo/'+this.punto.id,this.punto).then(res=>{
        console.log(res.data)
        this.mispuntos()
        this.fechaplan=date.formatDate(new Date(),'YYYY-MM-DD')
      })
    },
    frmmodalpunto(p){
       console.log(p)
       this.modalpunto=true

        this.punto=p
        this.detalle=[]
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
        this.listPersona()
        this.$q.loading.hide()
        console.log(this.puntos)
      })
    }
  },
}
</script>
