<template>
  <q-page>
    <!--<l-map style="height: 50vh" :zoom="zoom" :center="center">
      <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
      <l-marker @click="frmmodalpunto(p)" v-for="p in postes" :lat-lng="[p.lat,p.lng]" :key="p.id" >
        <l-icon><q-badge dense color="red"   label="" />
       </l-icon>
      </l-marker>
    </l-map>-->
    <GmapMap
    :center="center"
    :zoom="15"
    map-type-id="roadmap"
    style="width: 100%; height: 500px"
>
  <GmapMarker
          :key="index"
          v-for="(m, index) in postes"
          :position="m"
          :clickable="true"
          :draggable="false"
          @click="center={lat:m.lat,lng:m.lng}"
  />
</GmapMap>
    <q-table :data="puntos" :columns="colums" :filter="filter">

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
          <div class="text-h6">Nro poste {{punto.nroposte}}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form>
            <div class="row">
              <div class="col-12">
                <!--                <q-input dense outlined label="estado" v-model="punto.estado" />-->
                <q-select @input="cambioestado()" dense outlined label="estado" :options="estados" v-model="punto.estado" />
              </div>

              <div class="col-6">
                <q-input dense outlined label="nroposte" v-model="punto.nroposte" />
              </div>
              <div class="col-6">
                <q-input dense outlined label="potencia" v-model="punto.potencia" />
              </div>
              <div class="col-6">
                <q-input dense outlined label="material" v-model="punto.material" />
              </div>
              <div class="col-6">
                <q-input dense outlined label="lat" v-model="punto.lat" />
              </div>
              <div class="col-6">
                <q-input dense outlined label="lng" v-model="punto.lng" />
              </div>

              <div class="col-6">
                <q-input dense outlined label="cantidad" v-model="punto.cantidad" />
              </div>

              <div class="col-6">
                <q-input dense outlined label="junta" v-model="punto.junta" />
              </div>

              <div class="col-6">
                <q-input dense outlined label="distrito" v-model="punto.distrito" />
              </div>

              <div class="col-12">
                <q-input dense outlined label="observacion" v-model="punto.observacion" />
              </div>
            </div>
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
import { QBadge } from 'quasar';



export default {
  data () {
    return {
      modalpunto:false,
      estados:['ACTIVO','MANTENIMIENTO'],
      postes:[],
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

    this.mispuntos()
    this.cargar
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
      this.$axios.get('poste').then(res=>{
        this.puntos=res.data
        res.data.forEach(r => {
          this.postes.push({lat:r.lat,lng:r.lng,id:r.id})
        });
        this.$q.loading.hide()
         console.log(this.puntos)
      })
    }
  },
}
</script>




