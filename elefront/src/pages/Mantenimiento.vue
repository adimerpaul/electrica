<template>
  <q-page>
   <!-- <l-map style="height: 50vh" :zoom="zoom" :center="center">
      <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
      <l-marker @click="frmmodalpunto(p)" v-for="p in puntos" :lat-lng="[p.lat,p.lng]" :key="p.id" >

        <l-icon><q-badge :color="p.estado=='ACTIVO'?'primary':'negative'"  class="q-pa-xs" :label="p.nroposte" /> </l-icon>
      </l-marker>

    </l-map>-->
    <div class="col-12">
      <l-map style="height: 50vh" :zoom="zoom" :center="center" >
          <l-tile-layer :url="styleMap?`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`:`https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}`"
                      layer-type="base"
                      name="OpenStreetMap" :attribution="attribution"></l-tile-layer>

        <l-marker v-for="m in puntos" :key="m.id" :lat-lng="[m.lat,m.lng]" @click="center=[m.lat,m.lng];punto=m;modalpunto=true ">        <l-icon
          icon-url="http://maps.google.com/mapfiles/ms/icons/red-dot.png"
        /></l-marker>

        <l-control position="topright" >
                      <q-btn @click="styleMap=!styleMap" icon="map" class="bg-primary text-white" dense round></q-btn>
                    </l-control>
    </l-map>
    </div>

   <!-- <gmap-map
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
     use the default slot to pass markers to it

    <gmap-marker
      v-for="m in puntos" :key="m.id"
      :position="{'lat':m.lat,'lng':m.lng}"
      :clickable="true"
      :draggable="false"
      :title="m.material"
      icon="p1.png"
      optimized:true
      @click="center={'lat':m.lat,'lng':m.lng};punto=m;frmmodalpunto(m); "
    >
    </gmap-marker>
    </gmap-map>-->
    <q-table :data="puntos"  :columns="colums" :filter="filter">
      <template v-slot:body-cell-observacion="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
           <div style="font-size: 0.8em;white-space: initial;" class="text-subtitle2">{{props.row.descripcion}}</div>
        </q-td>
      </template>
      <template v-slot:body-cell-potencia="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
          {{props.row.potencia}} W
        </q-td>
      </template>
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
        <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
    </q-table>

    <q-dialog
      v-model="modalpunto"
      full-width
    >
      <q-card>
        <q-card-section>
          <div class="text-h6">Nro poste {{punto.nroposte}}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form>
            <div class="row">
              <div class="col-4">
                <q-input dense outlined label="codigo" v-model="punto.codigo" />
              </div>
              <div class="col-4">
                <q-input dense outlined label="nroposte" v-model="punto.nroposte" />
              </div>
              <div class="col-4">
                <q-input dense outlined label="potencia" v-model="punto.potencia" />
              </div>
              <div class="col-12">
                <q-input type="textarea" dense outlined label="descripcion" v-model="punto.descripcion" />
              </div>
              <div class="col-12">
<!--                <q-input dense outlined label="estado" v-model="punto.estado" />-->
                <q-select @input="cambioestado()" dense outlined label="estado" :options="estados" v-model="punto.estado" />
              </div>
            </div>
<!--            <pre>{{punto}}</pre>-->
          </q-form>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat label="OK" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </q-page>
</template>

<script>

export default {
  data () {
    return {
      styleMap:true,
      modalpunto:false,
      center: {lat:-17.970310, lng:-67.111780},
      estados:['ACTIVO','MANTENIMIENTO'],
      estado:'ACTIVO',
      filter:'',
      colums:[
        {name:"nroposte",label:"nroposte",field:"nroposte"},
        {name:"observacion",label:"observacion",field:"observacion"},
        {name:"potencia",label:"potencia",field:"potencia"},
        {name:"material",label:"material",field:"material"},
        {name:"luminaria",label:"luminaria",field:"luminaria"},
        {name:"ubicacion",label:"ubicacion",field:"ubicacion"},
      ],
      puntos:[],
      punto:{},
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution:
        '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      zoom: 13,
      markerLatLng: [-17.970310, -67.111780]
    };
  },
  created() {
    if (!this.$store.state.login.boolmtto){
       this.$router.replace({ path: '/' })
    }
    this.mispuntos()
    //this.cargar()
  },
  methods:{

    frmmodalpunto(p){
      // console.log('a')
      this.modalpunto=true
      this.punto=p
    },
    clickclientes(c){
      // console.log(c)
      this.center = {'lat':c.lat,'lng': c.lng}
      this.zoom= 18
    },
    cambioestado(){
      console.log(this.punto)
      this.$axios.put('punto/'+this.punto.id,this.punto)
    },
    mispuntos(){
      this.$q.loading.show()
      this.$axios.get('listmtto').then(res=>{
        this.$q.loading.hide()
        console.log(res.data)
        this.puntos=res.data
        // this.puntos=res.data
        // console.log(this.puntos)
      })
    }
  },
}
</script>
