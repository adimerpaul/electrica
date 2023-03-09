<template>
  <q-page>
    <div style="width: 100%;" class="col-12">
    <iframe src="https://www.google.com/maps/d/embed?mid=1p-L6PAC7LgRAPOhdxNBScFUvJfL3iAU&ehbc=2E312F" width="100%" height="480"></iframe>
    </div>
    <div style="width: 100%;" class="col-12">
      <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1OXQlfLi5c9u-UVoyMmRKXUQ9Tom3Kb4&ehbc=2E312F" width="100%" height="480"></iframe>
    </div>
    <div style="width: 100%;" class="col-12">
      <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1uhvCU2-Vex489UDhTOdwqlUQuYNy7h0&ehbc=2E312F" width="100%" height="480"></iframe>
    </div>
  </q-page>
</template>



<script>
import { QBadge } from 'quasar';
import { components } from "gmap-vue";

export default {
  data () {
    return {

      modalpunto:false,

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
      d6:[],
      d6e:[],

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
      markerLatLng: [-17.970310, -67.111780]
    };
  },
  created() {
      //console.log(this.$store.state.login.boolpuntos)

  },
  methods:{
    cargar(){
  // The location of Uluru
  let uluru = { lat: -17.970310, lng: -67.111780 };
  // The map, centered at Uluru
  this.map = new google.maps.Map(document.getElementById("map"), {
    zoom: 4,
    center: uluru,
  });
  // The marker, positioned at Uluru
  const marker = new google.maps.Marker({
    position: uluru,
    map: this.map,
  });

    },
    frmmodalpunto(p){
      // console.log('a')
      this.modalpunto=true
      this.punto=p
    },
    clickclientes(c){
      // console.log(c)
      this.center = {lat:c.lat, lng:c.lng}
      this.zoom= 18
    },
    cambioestado(){
      console.log(this.punto)
      this.$axios.put('poste/'+this.punto.id,this.punto)
    },
    mispuntos(){
      this.postes=[]
      this.$q.loading.show()
      this.$axios.get('poste').then(res=>{
        this.puntos=res.data
        res.data.forEach(r => {
          if(r.distrito=='D2') this.d2.push({lat:r.lat,lng:r.lng,id:r.id,label:'r.id'})
          if(r.distrito=='D2 EXT') this.d2e.push({lat:r.lat,lng:r.lng,id:r.id,label:'r.id'})
          if(r.distrito=='D4') this.d4.push({lat:r.lat,lng:r.lng,id:r.id,label:'r.id'})
          if(r.distrito=='D4 EXT') this.d4e.push({lat:r.lat,lng:r.lng,id:r.id,label:'r.id'})
          if(r.distrito=='D5') this.d5.push({lat:r.lat,lng:r.lng,id:r.id,label:'r.id'})
          if(r.distrito=='D5 EXT') this.d5e.push({lat:r.lat,lng:r.lng,id:r.id,label:'r.id'})
        });
        this.$q.loading.hide()
         console.log(this.puntos)
      })
    }
  },

}
</script>




