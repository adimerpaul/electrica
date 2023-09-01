<template>
  <q-page>

    <q-dialog v-model="dialogRegistro" full-width>
      <q-card>
        <q-card-section>
          <div class="text-h6">REGISTRO DE ALUMBRADO </div>
        </q-card-section>

        <q-form
        @submit.prevent="regPoste"
        >
        <q-card-section class="q-pt-none">
            <div class="row">
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="latitud" v-model="punto.lat"  />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="longitud" v-model="punto.lng"  />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="Nro Poste" v-model="punto.nroposte"  required />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-select dense outlined label="Potencia"  v-model="punto.potencia"
                :options="potencias" @change="cargarcolor()" />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-select dense outlined label="Material" v-model="punto.material" :options="['Madera','Metalico','Cemento','Empotrado','Ornamental','Cigarro','Riel','Colgado','Otro']" />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="Luminaria" v-model="punto.luminaria" required />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="Cant Luminarias" v-model="punto.cantidad" type="number" required/>
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="Altura" v-model="punto.altura" type="number" required/>
              </div>
              <div class="col-md-3 col-xs-6">
                <q-select dense outlined label="Distrito" v-model="punto.distrito" :options="['D1','D2','D3','D4','D5']" required/>
              </div>

              <div class="col-12">
                <q-input dense outlined label="Observacion" v-model="punto.observacion" required />
              </div>

            </div>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat label="Registrar" color="accent" type="submit" />
          <q-btn flat label="cerrar" color="green" v-close-popup />
        </q-card-actions>
      </q-form>
      </q-card>
    </q-dialog>

    <q-dialog v-model="modalpunto" full-width>
      <q-card>
        <q-card-section>
          <div class="text-h6">Nro poste {{punto.nroposte}} </div>
        </q-card-section>

        <q-form
        @submit.prevent="updatePoste"
        >
        <q-card-section class="q-pt-none">
            <div class="row">
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="latitud" v-model="punto.lat"  />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="longitud" v-model="punto.lng"   />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="Nro Poste" v-model="punto.nroposte"  required />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-select dense outlined label="Potencia"  v-model="punto.potencia" :options="potencias" @change.native="cargarcolor" />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-select dense outlined label="Material" v-model="punto.material" :options="['Madera','Metalico','Cemento','Empotrado','Ornamental','Cigarro','Riel','Colgado','Otro']" />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="Luminaria" v-model="punto.luminaria" required />
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="Cant Luminarias" v-model="punto.cantidad" type="number" required/>
              </div>
              <div class="col-md-3 col-xs-6">
                <q-input dense outlined label="Altura" v-model="punto.altura" type="number" required/>
              </div>
              <div class="col-md-3 col-xs-6">
                <q-select dense outlined label="Distrito" v-model="punto.distrito" :options="['D1','D2','D3','D4','D5']" required/>
              </div>

              <div class="col-12">
                <q-input dense outlined label="Observacion" v-model="punto.observacion"  />
              </div>

            </div>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat label="Actualizar" color="accent" type="submit" />
          <q-btn flat label="cerrar" color="green" v-close-popup />
        </q-card-actions>
      </q-form>
      </q-card>
    </q-dialog>

    <div class="row col-12">
      <div class="col-md-3 col-xs-8"><q-select dense v-model="distrito" :options="distritos" label="Distritos" ourlined /></div>
      <div class="col-2"><q-btn color="green" icon="search" @click="mispuntos" /></div>
      <div class="col-2"><q-btn color="teal" icon="add_circle_outline" @click="punto={}; dialogRegistro=true" /></div>
      <div class="col-md-3 col-xs-10"><q-input dense outlined v-model="numeroposte" type="text" label="Numero Poste"/></div>
      <div class="col-1"><q-btn color="info" icon="place" @click="searchPlace" /></div>
    </div>
    <div class="col-12">
      <l-map style="height: 50vh" :zoom="zoom" :center="center" @click="cargarpunto">
          <l-tile-layer :url="styleMap?`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`:`http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}`"
                      layer-type="base"
                      name="OpenStreetMap" :attribution="attribution" :options="{ maxNativeZoom: 18, maxZoom: 20 }"></l-tile-layer>
        <l-marker :lat-lng="[lat,lng]" draggable  @moveend="updateCoordinates" @click="registropunto()"
         title="Usted esta Aqui"
        >
        <l-icon
          icon-url="http://maps.google.com/mapfiles/ms/icons/purple-pushpin.png"
        />
      </l-marker>
        <l-marker v-for="m in datos" :key="m.id" :lat-lng="[m.lat,m.lng]" @click="center=[m.lat,m.lng];punto=m;modalpunto=true ">
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

  <!--
    <div>
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
 }">
     use the default slot to pass markers to it
    <gmap-marker
    :position="ubicacion"
    :draggable="true"
    :clickable="true"
    @drag="updateCoordinates"
    @click="registropunto()"
    title="Usted esta Aqui"
    :optimized=true
    icon="http://maps.google.com/mapfiles/ms/icons/purple-pushpin.png"
  >
  </gmap-marker>
    <gmap-marker
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
  </div>
-->
    <q-table :data="datos" :columns="colums" :filter="filter">
      <template v-slot:top-right>
        <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:body-cell-potencia="props" >
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
    </q-table>
    </q-page>
  </template>

<script>
import {date} from "quasar";

export default {
  data () {
    return {
      datos:[],
      numeroposte:'',
      styleMap:true,
      lat:0,
      lng:0,
      dialogRegistro:false,
      potencia:'',
      mantenimiento:'',
        distritos:[
          {label:'Distrito 1',value:'D1'},
          {label:'Distrito 1 Ext',value:'D1 EXT'},
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

      kmlLayers:[{url:'colegio.kml'}],
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
      colors:[],
      potencias:['1000w','600w','400w','360w','300w','250w','240w','200w','196w','150w',
      '144w','136w','125w','120w','100w','85w','75w','70w',
      '60w','58w','50w','40w','38w',
      '36w','30w','20w','24w','18w','15w','12w','10w','0'],
      poten:[
      {'potencia':'1000w','colores':'l1000.png'},
       {'potencia':'600w','colores':'l600.png'},
       {'potencia':'400w','colores':'l400.png'},
       {'potencia':'360w','colores':'l360.png'},
       {'potenci  a':'300w','colores':'l300.png'},
       {'potencia':'250w','colores':'l250.png'},
       {'potencia':'240w','colores':'l240.png'},
       {'potencia':'200w','colores':'l200.png'},
       {'potencia':'196w','colores':'l196.png'},
       {'potencia':'150w','colores':'l150.png'},
       {'potencia':'144w','colores':'l144.png'},
       {'potencia':'136w','colores':'l136.png'},
       {'potencia':'125w','colores':'l125.png'},
       {'potencia':'120w','colores':'l120.png'},
       {'potencia':'100w','colores':'l100.png'},
       {'potencia':'85w','colores':'l85.png'},
       {'potencia':'75w','colores':'l75.png'},
       {'potencia':'70w','colores':'l70.png'},
       {'potencia':'60w','colores':'l60.png'},
       {'potencia':'58w','colores':'l58.png'},
       {'potencia':'50w','colores':'l50.png'},
       {'potencia':'40w','colores':'l40.png'},
       {'potencia':'38w','colores':'l38.png'},
      {'potencia':'36w','colores':'l36.png'},
      {'potencia':'30w','colores':'l30.png'},
      {'potencia':'20w','colores':'l20.png'},
      {'potencia':'24w','colores':'l24.png'},
      {'potencia':'18w','colores':'l18.png'},
      {'potencia':'15w','colores':'l15.png'},
      {'potencia':'12w','colores':'l12.png'},
      {'potencia':'10w','colores':'l10.png'},
      {'potencia':'0','colores':'sn.png'}],
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
      center: {lat:-17.970310, lng:-67.111780},
      markerLatLng: [-17.970310, -67.111780],
      ubicacion:{lat:-17.970310, lng:-67.111780},
      marker:{},
    };
  },
  created() {

   // this.mispuntos()
    this.geolocate()
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
    searchPlace(){
      if(this.numeroposte=='' || this.numeroposte==undefined)
      {
        this.datos=[]
        return false
      }
      this.datos=[]
      this.$axios.get('buscarPoste/'+this.numeroposte).then(res=>{
        console.log(res.data)
        //if(res.data.length>0){

          this.datos=res.data
          //this.ubicacion={lat:res.data[0].lat,lng:res.data[0].lng};
          //this.center=this.ubicacion
          //this.zoom=18;
       // }
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
    handleMarkerDrag(e) {
      this.ubicacion = { lat: e.latLng.lat(), lng: e.latLng.lng() };
    },
    updateCoordinates(l) {
            console.log(l.target._latlng)
            this.lat= l.target._latlng.lat
            this.lng= l.target._latlng.lng
            this.ubicacion = [this.lat,this.lng];
            this.center=this.ubicacion


        },
    registropunto(){
      //alert(this.ubicacion.lat)

      this.punto={}
      this.punto.lat=this.lat.toFixed(10)
      this.punto.lng=this.lng.toFixed(10)

      this.dialogRegistro=true
    },

    verpuntos(){
      //alert(this.ubicacion.lat)
      console.log('dblcl')
      this.$axios.post('calcularArea',{lat:this.lat,lng:this.lng,distancia:200}).then(res=>{
          console.log(res.data)
          this.datos=res.data
      })
    },

      cargarcolor(){
        console.log('sdfds')
        if(this.punto.potencia=='')
          this.punto.color=[]
          //console.log(this.punto.potencia)

        this.poten.forEach(r=>{
          if(r.potencia==this.punto.potencia)
          {this.punto.color=r.colores}
        })
      },
      regPoste(){
        const regex = /[-]?[0-9]+[.,][0-9]*/i;

        if (!regex.test(this.punto.lat) || isNaN(this.punto.lat)) {
          this.$q.notify({
                    icon:'info',
                    message: 'Error Lat ',
                    color: 'red'
                  })
          return false
        }

        if (!regex.test(this.punto.lng) || isNaN(this.punto.lng)) {

          this.$q.notify({
                    icon:'info',
                    message: 'Error lng ',
                    color: 'red'
                  })
          return false
        }
        this.poten.forEach(r=>{
          if(r.potencia==this.punto.potencia)
          {
          this.punto.color=r.colores}
        })
        console.log(this.punto)
        this.$q.loading.show();

      this.$axios.post('poste',this.punto).then(res=>{
        this.dialogRegistro=false
        this.$q.notify({
              icon:'info',
              message: 'Punto registrado ',
              color: 'green'
            })
        this.punto={}
        this.$q.loading.hide();

        this.mispuntos
      })

      },
updatePoste(){
  console.log(this.punto)
  //return false
  const regex = /[-]?[0-9]+[.,][0-9]*/i;

  if (!regex.test(this.punto.lat) || isNaN(this.punto.lat)) {
    this.$q.notify({
              icon:'info',
              message: 'Error Lat ',
              color: 'red'
            })
    return false
  }

  if (!regex.test(this.punto.lng) || isNaN(this.punto.lng)) {
    this.$q.notify({
              icon:'info',
              message: 'Error lng ',
              color: 'red'
            })
    return false
  }
  this.poten.forEach(r=>{
          if(r.potencia==this.punto.potencia)
          {this.punto.color=r.colores}
        })
    this.$axios.post('upPoste',this.punto).then(res=>{
    //this.mispuntos()
    this.modalpunto=false
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
    },
    clickclientes(c){
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
      this.puntos=[]
      this.$q.loading.show()
      this.$axios.post('listaposte',{'distrito':this.distrito.value}).then(res=>{
        this.datos=res.data
        //this.puntos=res.data
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
        });*/
        this.$q.loading.hide()
         console.log(this.puntos)
      })
    }
  },

}
</script>




