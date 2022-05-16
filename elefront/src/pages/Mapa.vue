<template>
<q-page class="q-pa-none">
  <GmapMap
    :center="{lat:-17.970310, lng:-67.111780}"
    :zoom="13"
    map-type-id="terrain"
    style="width: 100%; height: 550px"
  >
<!--    <GmapMarker-->
<!--      :key="index"-->
<!--      v-for="(m, index) in markers"-->
<!--      :position="m.position"-->
<!--      :clickable="true"-->
<!--      :draggable="false"-->
<!--      class="bg-green"-->
<!--      @click="center=m.position"-->
<!--      :options="options"-->

<!--    ></GmapMarker>-->
    <GmapMarker
      :position="{lat:-17.970310, lng:-67.111780}"
      label="a"
      :options="{style:'color-red',}"
      :clickable="true"
      :draggable="true"
      class="bg-red text-bold"
      :icon1= '{
          url: "https://toppng.com/uploads/preview/map-marker-icon-600x-map-marker-11562939743ayfahlvygl.png",
          scaledSize: {width: 20, height: 20},
          labelOrigin: {x: 20, y: 20}
      }'
    />
  </GmapMap>
  {{center}}
</q-page>
</template>

<script>
import GmapCustomMarker from 'vue2-gmap-custom-marker';

export default {
  name: `Mapa`,
  components: {
    // 'gmap-custom-marker': GmapCustomMarker
  },
  data(){
    return{
      center: {
        lat: 10.0,
        lng: 10.0
      },
      options: { styles: ['color:green'] },
      markers: [],
      marker: {
        lat: -17.970310,
        lng: -67.111780
      }
    }
  },
  created() {
    this.mispuntos()
  },
  methods:{
    getOptions() {
      return {
        zoomControl: true,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false,
        disableDefaultUi: false
      };
    },
    mispuntos(){
      this.$q.loading.show()
      var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
      var icons = {
        parking: {
          icon: iconBase + 'parking_lot_maps.png'
        },
        library: {
          icon: iconBase + 'library_maps.png'
        },
        info: {
          icon: iconBase + 'info-i_maps.png'
        }
      };

      this.$axios.get('punto').then(res=>{
        this.markers=[]
        res.data.forEach(r=>{
          r.position={lat:r.lat,lng:r.lng}
          r.icon=icons['info'].icon
          this.markers.push(r)
        })
        this.$q.loading.hide()
        // console.log(this.puntos)
      })
    }
  }

}
</script>

<style scoped>

</style>
