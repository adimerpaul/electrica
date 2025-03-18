<template>
  <q-page>
    <div class="row">
      <div class="col-3 q-pa-xs"><q-input dense outlined v-model="ini" label="fecha ini" type="date"/></div>
      <div class="col-3 q-pa-xs"><q-input dense outlined v-model="fin" label="fecha fin" type="date"/></div>
      <div class="col-3 q-pa-xs"> <q-btn color="info"  icon="search" @click="getMtto"/>
      </div>
    </div>
    <div class="col-12">
      <l-map style="height: 50vh" :zoom="zoom" :center="center" >
          <l-tile-layer :url="styleMap?`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`:`https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}`"
                      layer-type="base"
                      name="OpenStreetMap" :attribution="attribution" :options="{ maxNativeZoom: 18, maxZoom: 20 }">
          </l-tile-layer>
          <l-geo-json
          :geojson="puntos"
          :options="options"
          :options-style="styleFunction"
          />


        <l-control position="topright" >
          <q-btn @click="geolocate" icon="my_location" class="bg-primary text-white" dense round></q-btn>
                      <q-btn @click="styleMap=!styleMap" icon="map" class="bg-primary text-white" dense round></q-btn>
                    </l-control>
    </l-map>
    </div>

    </q-page>
</template>

<script>

import {date} from "quasar";

export default {
  data () {
    return {
      filtertecnicos:[],
      modalpunto:false,
      styleMap: true,
      detalle:[],
      material:{},
      codigo:{},
      codigos:[],
      ini:date.formatDate(new Date(),'YYYY-MM-DD'),
      fin:date.formatDate(new Date(),'YYYY-MM-DD'),
      filter:'',
      url:'https://electrica.gamo.cf/#/consulta',

      puntos:[],
      punto:{},
      show: true,
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
  async created() {
    

    await this.geolocate()
    this.getMtto()
  },
  computed: {
    options() {
      return {
        onEachFeature: this.onEachFeatureFunction
      };
    },
    styleFunction() {
      const fillColor = 'red'; // important! need touch fillColor in computed for re-calculate when change fillColor
      return () => {
        return {
          weight: 2,
          color: "#ECEFF1",
          opacity: 1,
          fillColor: fillColor,
          fillOpacity: 1
        };
      };
    },
    onEachFeatureFunction() {
      if (!this.enableTooltip) {
        return () => {};
      }
      return (feature, layer) => {
        layer.bindTooltip(
          "<div>code:" +
            feature.properties.nroposte +
            "</div><div>pot: " +
            feature.properties.potencia +
            "</div>",
          { permanent: false, sticky: true }
        );
      };
    }
  },
  methods:{
    getMtto(){
      if(this.ini==undefined || this.fin== undefined)
        return false
      this.$axios.post('reporteMtto',{ini:this.ini,fin:this.fin}).then(res=>{
        console.log(res.data)
        this.puntos=res.data
      })

    },

    async geolocate() {
      this.ubicacion= [0, 0]
      console.log(navigator.geolocation)
      if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition((position) => {
        console.log(position)
        this.lat=position.coords.latitude
        this.lng=position.coords.longitude
         this.zoom=18
        this.ubicacion=[this.lat,this.lng]
        this.center=this.ubicacion
        })
      }
    },

    centro(){
      this.center = [-17.970310, -67.111780]
      this.zoom= 13
    },

  },
}
</script>
