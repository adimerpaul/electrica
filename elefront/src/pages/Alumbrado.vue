<template>
  <q-page>
    <!--<l-map style="height: 50vh" :zoom="zoom" :center="center">
      <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
      <l-marker @click="frmmodalpunto(p)" v-for="p in postes" :lat-lng="[p.lat,p.lng]" :key="p.id" >
        <l-icon><q-badge dense color="red"   label="" />
       </l-icon>
      </l-marker>
    </l-map>-->
    <div class="row col-12">
      <div class="col-md-3 col-xs-8"><q-select dense v-model="distrito" :options="distritos" label="Distritos" ourlined /></div>
      <div class="col-2"><q-btn color="green" icon="search" @click="mispuntos" /></div>

      <div class="col-md-3 col-xs-10"><q-input dense outlined v-model="numeroposte" type="text" label="Numero Poste"/></div>
      <div class="col-1"><q-btn color="info" icon="place" @click="searchPlace" /></div>
    </div>
    <div class="col-12">
      <l-map style="height: 50vh" :zoom="zoom" :center="center"  @click="cargarpunto" >
          <l-tile-layer :url="styleMap?`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`:`http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}`"
                      layer-type="base"
                      name="OpenStreetMap" :attribution="attribution" :options="{ maxNativeZoom: 18, maxZoom: 20 }"></l-tile-layer>
                      <l-marker :lat-lng="[lat,lng]" draggable  @moveend="updateCoordinates" @click="cargarUbicacion" title="Usted esta Aqui">
        <l-icon icon-url="img/pinyw.png" />

      </l-marker>
        <l-marker v-for="m in datos" :key="m.id" :lat-lng="[m.lat,m.lng]" @click="center=[m.lat,m.lng];punto=m;modalpunto=true; recmtto='';">

          <l-icon>
          <img :src="'img/'+m.color" style="width: 20px;height: 20px;"/>
          <div class="headline">
            {{ m.nroposte }}
          </div>
          </l-icon>
          </l-marker>
        <l-control position="topright" >
        <q-btn @click="geolocate" icon="my_location" class="bg-primary text-white" dense round></q-btn>

                      <q-btn @click="styleMap=!styleMap" icon="map" class="bg-primary text-white" dense round></q-btn>
                    </l-control>
    </l-map>
    </div>
    <!--    <div>
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
     use the default slot to pass markers to it --   <gmap-marker
      v-for="m in datos" :key="m.id"
      :position="{'lat':m.lat,'lng':m.lng}"
      :clickable="true"
      :icon="'img/'+m.color"
      :draggable="false"
      :title="m.material"
      :optimized=true
      @click="center={'lat':m.lat,'lng':m.lng};punto=m;modalpunto=true "
    >
    </gmap-marker>
    </gmap-map>
  </div>-->

    <q-table :data="datos" :columns="colums" :filter="filter">

      <template v-slot:body-cell-potencia="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
          {{props.row.potencia}}
        </q-td>
      </template>
      <template v-slot:body-cell-nroposte="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
          {{props.row.nroposte}}
        </q-td>
      </template>
      <template v-slot:body-cell-material="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
           <div style="font-size: 0.8em;white-space: initial;" class="text-subtitle2">{{props.row.material}}</div>
        </q-td>
      </template>
      <template v-slot:body-cell-observacion="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
           <div style="font-size: 0.8em;white-space: initial;" class="text-subtitle2">{{props.row.observacion}}</div>
        </q-td>
      </template>
      <template v-slot:body-cell-ubicacion="props">
        <q-td :props="props">
          <q-btn :color="props.row.estado=='ACTIVO'?'primary':'negative'" @click="clickclientes(props.row)"  icon="place" size="xs" />
        </q-td>
      </template>
      <template v-slot:top-right>
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
          <div class="text-h6">Nro poste {{punto.nroposte}} latlng ({{punto.lat}},{{punto.lng}})</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
            <div class="row">

              <div class="col-4">
                <q-input dense outlined label="Nro Poste" v-model="punto.nroposte" readonly />
              </div>
              <div class="col-4">
                <q-input dense outlined label="Potencia" v-model="punto.potencia" readonly/>
              </div>
              <div class="col-4">
                <q-input dense outlined label="Material" v-model="punto.material" readonly/>
              </div>
              <div class="col-4">
                <q-input dense outlined label="Luminaria" v-model="punto.luminaria" readonly/>
              </div>
              <div class="col-4">
                <q-input dense outlined label="Cant Luminarias" v-model="punto.cantidad" readonly/>
              </div>

              <div class="col-12">
                <q-input dense outlined label="Observacion" v-model="punto.observacion" readonly/>
              </div>

            </div>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat label="Mantenimiento" color="accent" @click="registroMtto"/>
          <q-btn flat label="cerrar" color="green" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </q-page>
</template>



<script>
import {date} from "quasar"

export default {
  data () {
    return {
      datos:[],
      iconSize: [32, 37],
      iconAnchor: [16, 37],
      prueba:'',
      styleMap:true,
      numeroposte:'',
      recmtto:'',
        distritos:[
          {label:'Distrito 1',value:'D1'},
          {label:'Distrito 2',value:'D2'},
          {label:'Distrito 2 Ext',value:'D2 EXT'},
          {label:'Distrito 3',value:'D3'},
          {label:'Distrito 3 Ext',value:'D3 EXT'},
          {label:'Distrito 4',value:'D4'},
          {label:'Distrito 4 Ext',value:'D4 EXT'},
          {label:'Distrito 5',value:'D5'},
          {label:'Distrito 5 Ext',value:'D5 EXT'},
        ],
      distrito:{label:'Distrito 1',value:'D1'},
      modalpunto:false,
      plan:{},
      estados:['ACTIVO','MANTENIMIENTO'],
      postes:[],
      d1:[],
      d1e:[],
      d2:[],
      d2e:[],
      d3:[],
      d3e:[],
      d4:[],
      d4e:[],
      d5:[],
      d5e:[],
      lat:0,
      lng:0,
      estado:'ACTIVO',
      filter:'',
      colums:[
        {name:"id",label:"id",field:"id"},
        {name:"nroposte",label:"nroposte",field:"nroposte"},
        {name:"potencia",label:"potencia",field:"potencia"},
        {name:"material",label:"material",field:"material"},
        {name:"observacion",label:"observacion",field:"observacion"},
        {name:"ubicacion",label:"ubicacion",field:"ubicacion"},
      ],
      puntos:[],
      map:null,
      punto:{},
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution:
        '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      zoom: 16,
      center: [-17.970310, -67.111780],
      markerLatLng: [-17.970310, -67.111780]
    };
  },
  created() {
    if (!this.$store.state.login.booldistrito){
       this.$router.replace({ path: '/' })
    }
   // this.mispuntos()
    //this.cargar
  },
  methods:{
    async geolocate() {
      this.ubicacion= [0, 0]
      console.log(navigator.geolocation)
      if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition((position) => {
          this.lat=position.coords.latitude
          this.lng=position.coords.longitude
          this.zoom=18;
        this.ubicacion=[this.lat,this.lng];
        this.center=this.ubicacion
        this.$axios.post('calcularArea',{lat:this.lat,lng:this.lng,distancia:200}).then(res=>{
          console.log(res.data)
          this.datos=res.data
         })
        })
      }
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
        cargarUbicacion(){
          this.datos=[]
      this.$axios.post('calcularArea',{lat:this.lat,lng:this.lng,distancia:200}).then(res=>{
          console.log(res.data)
          this.datos=res.data
      })
    },
    searchPlace(){
      if(this.numeroposte=='' || this.numeroposte==undefined)
      {
        this.datos=[]
        return false
      }
      this.datos=[]
      this.$axios.get('buscarPoste/'+this.numeroposte).then(res=>{
        console.log(res.data)
        if(res.data.length>0){

          this.datos.push(res.data[0])
          this.ubicacion={lat:res.data[0].lat,lng:res.data[0].lng};
          this.center=this.ubicacion
          this.zoom=18;
        }
      })
    },
    registroMtto(){
        this.$q.dialog({
        title: 'Enviar a Mantenimiento',
        message: 'Esta Seguro?',
        cancel: true,
        persistent: false
      }).onOk(() => {
        // console.log('>>>> OK')
        this.plan.fecha=date.formatDate(new Date(),'YYYY-MM-DD')
        this.plan.hora=date.formatDate(new Date(),'HH:mm')
        this.plan.punto=this.punto
        this.plan.reclamo='MANTENIMIENTO '
        this.$axios.post('matto',this.plan).then(res=>{
        this.modalpunto=false
        this.recmtto=''
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


      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })

    },
    async onClick(){
      console.log(this.distrito.value)
      switch (this.distrito.value) {
        case 'D1': this.datos=this.d1;break;
        case 'D1 EXT': this.datos=this.d1e;break;
        case 'D2': this.datos=this.d2;break;
        case 'D2 EXT': this.datos=this.d2e;break;
        case 'D3': this.datos=this.d3;break;
        case 'D3 EXT': this.datos=this.d3e;break;
        case 'D4': this.datos=this.d4;break;
        case 'D4 EXT': this.datos=this.d4e;break;
        case 'D5': this.datos=this.d5;break;
        case 'D5 EXT': this.datos=this.d5e;break;

        default:
          this.datos=[]
          break;
      }
    },

    frmmodalpunto(p){
      // console.log('a')
      this.modalpunto=true
      this.punto=p
      this.recmtto=''
    },
  async  clickclientes(c){
      // console.log(c)
      this.center = [c.lat, c.lng]
      this.zoom= 18
    },
    cambioestado(){
      console.log(this.punto)
      this.$axios.put('poste/'+this.punto.id,this.punto)
    },
    mispuntos(){
      this.postes=[]
      this.$q.loading.show()
      this.$axios.post('listaposte',{'distrito':this.distrito.value}).then(res=>{
        this.datos=res.data
        /*res.data.forEach(r => {
          if(r.distrito=='D1') this.d1.push(r)
          if(r.distrito=='D1 EXT') this.d1e.push(r)
          if(r.distrito=='D2') this.d2.push(r)
          if(r.distrito=='D2 EXT') this.d2e.push(r)
          if(r.distrito=='D3') this.d3.push(r)
          if(r.distrito=='D3 EXT') this.d3e.push(r)
          if(r.distrito=='D4') this.d4.push(r)
          if(r.distrito=='D4 EXT') this.d4e.push(r)
          if(r.distrito=='D5') this.d5.push(r)
          if(r.distrito=='D5 EXT') this.d5e.push(r)
        })*/
        this.$q.loading.hide()
         console.log(this.datos)
      })
    }},
    computed: {
    dynamicSize () {
      return [this.iconSize, this.iconSize * 1.15];
    },
    dynamicAnchor () {
      return [this.iconSize / 2, this.iconSize * 1.15];
    }
  }

}
</script>




