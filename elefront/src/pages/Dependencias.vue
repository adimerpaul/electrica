<template>
  <q-page>
      <q-form
        @submit.prevent="misjobs"
        class="q-gutter-md"
      >
      <div class="row">
        <div class="col-3 q-pa-xs "><q-input outlined v-model="fecha" type="date" label="Fecha" dense  required/></div>
        <div class="col-3 q-pa-xs "> <q-btn label="Buscar" type="submit" color="primary" icon="search" dense/>  </div>

    </div>
        <div>
        </div>
      </q-form>

    <div class="col-12">
      <l-map style="height: 50vh" :zoom="zoom" :center="center" @click="cargarpunto" >
          <l-tile-layer :url="styleMap?`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`:`https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}`"
                      layer-type="base"
                      name="OpenStreetMap" :attribution="attribution" :options="{ maxNativeZoom: 18, maxZoom: 20 }"></l-tile-layer>
       <l-marker :lat-lng="[lat,lng]" draggable  @moveend="updateCoordinates" @click="modalpunto=true" title="Usted esta Aqui">

        <l-icon
          icon-url="http://maps.google.com/mapfiles/ms/icons/pink-pushpin.png"
        />
        </l-marker>
        <l-marker v-for="m in jobs" :key="m.id" :lat-lng="[m.lat,m.lng]" @click="center=[m.lat,m.lng]; ">        <l-icon
          icon-url="http://maps.google.com/mapfiles/ms/icons/red-dot.png"
        /></l-marker>

        <l-control position="topright" >
          <q-btn @click="geolocate" icon="my_location" class="bg-primary text-white" dense round></q-btn>
                      <q-btn @click="styleMap=!styleMap" icon="map" class="bg-primary text-white" dense round></q-btn>
                    </l-control>
    </l-map>
    </div>


    <q-table :data="jobs"  :columns="colums" :filter="filter">
      <template v-slot:top-left>
                <q-toggle
                  v-model="tipo2"
                  color="green"
                  :label="tipo2"
                  true-value="PLAZAS Y PARQUES"
                  false-value="DEPENDENCIAS"
                />
                <q-btn color="red"  label="PDF" @click="exportPdf" />
      </template>
      <template v-slot:top-right>
        <q-btn icon="refresh" color="info" @click="misjobs"/>
        <q-btn icon="adjust" color="negative" @click="centro"/>
        <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:body-cell-op="props">
        <q-td :props="props" >
            <q-btn color="red" icon="location_on" @click="goPlace(props.row)" dense/>
        </q-td>
      </template>

    </q-table>

    <q-dialog v-model="modalpunto" full-width>
      <q-card>
        <q-card-section>
          <div class="text-h6">REGISTRO DE {{ tipo }}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form @submit="registroJob">

            <div class="row">
              <div class="col-6">
                <b>LAT: </b> {{ job.lat }}
              </div>
              <div class="col-6">
                <b>LNG: </b>{{ job.lng }}
              </div>
            </div><hr>
            <div class="row">
              <div class="col-md-6 col-xs-12">
                <q-input style="text-transform: uppercase" outlined dense label="Lugar" list="lugares"  v-model="lugar" />
                    <datalist id="lugares">
                      <option v-for="r in lugares" :key="r.lugar">{{r.lugar}}</option>
                    </datalist>
              </div>
              <div class="col-md-6 col-xs-12">
                <q-toggle
                  v-model="tipo"
                  color="green"
                  :label="tipo"
                  true-value="PLAZAS Y PARQUES"
                  false-value="DEPENDENCIAS"
                /></div>

            </div>


              <div class="col-12">
                <q-input required dense outlined type="textarea" label="Actividad" v-model="job.actividad" />
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
                <q-input dense outlined type="date" label="Fecha" v-model="job.fecha" />
              </div>
              <div class="col-md-6 col-xs-6">
                <q-input dense outlined type="time" label="Hora" v-model="job.hora" />
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
      lugar:'',
      max:19,
      modalpunto:false,
      styleMap: true,
      detalle:[],
      material:{},
      codigo:{},
      codigos:[],
      tipo:'PLAZAS Y PARQUES',
      tipo2:'PLAZAS Y PARQUES',
      fecha:date.formatDate(new Date(),'YYYY-MM-DD'),
      filter:'',
      job:{fecha:date.formatDate(new Date(),'YYYY-MM-DD'),hora:date.formatDate(new Date(),'HH:mm'),},
      persona:{},
      cantidad:1,
      colums:[
        {name:"op",label:"OP",field:'op'},
        {name:"lat",label:"LAT",field:'lat'},
        {name:"lng",label:"LNG",field:'lng'},
        {name:"lugar",label:"LUGAR",field:'lugar'},
        {name:"tipo",label:"TIPO",field:'tipo'},
        {name:"actividad",label:"ACTIVIDAD",field:'actividad'},
      ],
      coldetalle:[
        {label:'OP',field:'opcion',name:'opcion'},
        {label:'MATERIAL',field:'material',name:'material'},
        {label:'CODIGO',field:row=>row.inventario.codigo,name:'codigo'},
        {label:'CANT',field:'uso',name:'uso'}
      ],
      jobs:[],
      lugares:[],
      listMaterial:[],
     // url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution:
        '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      zoom: 13,
      center: {lat:-17.970310, lng:-67.111780},
      markerLatLng: [-17.970310, -67.111780],
      lat:0,
      lng:0,

    };
  },
  created() {
    this.geolocate();
    this.misjobs()
    this.cargarMaterial()
    this.cargalugar()
  },
  methods:{
    exportPdf(){
      if((this.jobs).length > 0){
        this.cadena="<style>\
          .fuente{font-size:10px}\
          table{width:100%; border-collapse: collapse;}\
          table, th, td {  border: 1px solid;}\
          titulo{font-weight: bold; font-size:16px; text-align: center;}\
          </style>\
          <div class='fuente'>\
            <table style='border:0;'><tr><td style='border:0;width:20%;'><img src='logo.jpg' style='width:50px; height:50px;'/></td>\
               <td class='titulo' style='border:0;'> RESUMEN DE "+this.tipo2+" DEL "+this.fecha+" Usuario: "+this.jobs[0].user.name+"</td>\
               </tr>\
            </table>\
            <table>\
            <thead><tr><th>N</th><th>LAT</th><th>LNG</th><th>FECHA</th><th>HORA</th><th>LUGAR</th><th>TECNICO</th><th>TIPO</th><th>ACTIVIDAD</th></tr></thead>\
          <tbody>"
            let con=0
            this.jobs.forEach(r=>{
              if(r.tipo==this.tipo2){
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
              </tr>" }})
              this.cadena+="</tbody></table></div>"
              //console.log(this.cadena)
          document.getElementById('myelement').innerHTML = this.cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )}
    },
    goPlace(punto){
      this.zoom=18;
      this.ubicacion = [punto.lat,punto.lng];
      this.center=this.ubicacion
    },
    cargarpunto(value){
      console.log(value.latlng)
      this.lat= value.latlng.lat
      this.lng= value.latlng.lng
      this.ubicacion = [this.lat,this.lng];
      this.center=this.ubicacion
      this.job.lat=this.lat.toFixed(8)
      this.job.lng=this.lng.toFixed(8)
      this.job.fecha=date.formatDate(new Date(),'YYYY-MM-DD')
      this.hora=date.formatDate(new Date(),'HH:mm')

    },
    updateCoordinates(location) {
        this.lat= location.latLng.lat(),
        this.lng= location.latLng.lng(),
            this.ubicacion = [this.lat,this.lng];
            this.job.lat=this.lat.toFixed(8)
      this.job.lng=this.lng.toFixed(8)
      this.job.fecha=date.formatDate(new Date(),'YYYY-MM-DD')
      this.hora=date.formatDate(new Date(),'HH:mm')

        },
    registroJob(){
      this.job.tipo=this.tipo
      if(this.lugar=='')
        return false
      this.job.lugar=this.lugar
      this.job.detalle=this.detalle
        this.$q.loading.show();
      this.$axios.post('trabajo',this.job).then(res=>{
        this.modalpunto=false
        this.job={}
        this.detalle=[]
        this.cargarMaterial()
        this.misjobs()
        this.$q.notify({
              icon:'info',
              message: 'registrado ',
              color: 'green'
            })
        this.$q.loading.hide();
      }).catch(error=>{
        this.$q.notify({
              icon:'info',
              message: error.message,
              color: 'red'
            })
            this.$q.loading.hide();
      })

    },

    cargalugar(){
      this.lugares=[]
      this.$axios.get('listLugar').then(res=>{
        this.lugares=res.data
      })

    },
    async geolocate() {
      this.ubicacion= [0, 0]
      console.log(navigator.geolocation)
      if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition((position) => {
          this.zoom=18;
          this.lat=position.coords.latitude
          this.lng=position.coords.longitude
        this.ubicacion=[this.lat,this.lng];
        this.center=this.ubicacion
        this.job.lat=this.lat.toFixed(8)
      this.job.lng=this.lng.toFixed(8)
      this.job.fecha=date.formatDate(new Date(),'YYYY-MM-DD')
      this.hora=date.formatDate(new Date(),'HH:mm')
        })
      }
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
      this.job.actividad= cadena
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

    clickclientes(c){
      // console.log(c)
      // this.center = [-17.970310, -67.111780]
      this.center = {lat:c.lat, lng:c.lng}
      this.zoom= 18
    },

    centro(){
      this.center = [-17.970310, -67.111780]
      this.zoom= 13
    },
    misjobs(){
      if(this.fecha=='' || this.fecha==undefined)
        return false

      this.$q.loading.show()
        this.jobs=[]
      this.$axios.post('listaTrabajos',{fecha:this.fecha}).then(res=>{
        console.log(res.data)
        this.jobs=res.data
        this.$q.loading.hide()
      })
    }
  },
}
</script>
