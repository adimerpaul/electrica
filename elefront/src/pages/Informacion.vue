<template>
  <q-page>
      <iframe src="https://www.google.com/maps/d/embed?mid=1p-L6PAC7LgRAPOhdxNBScFUvJfL3iAU&ehbc=2E312F" width="100%" height="480"></iframe>
      <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1OXQlfLi5c9u-UVoyMmRKXUQ9Tom3Kb4&ehbc=2E312F" width="100%" height="480"></iframe>
      <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1uhvCU2-Vex489UDhTOdwqlUQuYNy7h0&ehbc=2E312F" width="100%" height="480"></iframe>
  </q-page>
</template>



<script>

export default {
  data () {
    return {
      accessToken: 'pk.eyJ1IjoiYWxleGxvcGV6ZyIsImEiOiJjbGYxazg1YmkwNGlkM3hudmpkZWVlczVuIn0.b6sTYHtmkdGnfqUsQzYprw',
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


  },

}
</script>




