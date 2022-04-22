<template>
  <q-page>
    <l-map style="height: 50vh" :zoom="zoom" :center="center">
      <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
      <l-marker @click="frmmodalpunto(p)" v-for="p in puntos" :lat-lng="[p.lat,p.lng]" :key="p.id" >
<!--        <l-icon><q-badge  :class="c.tipo=='PEDIDO'?'bg-green-5  text-italic':c.tipo=='PARADO'?'bg-yellow-5  text-italic':c.tipo=='NO PEDIDO'?'bg-red-5 text-italic':''"  class="q-pa-none" color="info" >{{c.Cod_Aut}}</q-badge></l-icon>-->
        <l-icon><q-badge :class="p.color=='AZUL CLARO'?'bg-light-blue-6':p.color=='AZUL OSCURO'?'bg-indigo-8':p.color=='AZUL OSCURO'?'bg-green-9':'bg-red'"   label="" /> <div style="color: white;
            font-size: 15px;
            font-family: 'Arial Black';
            opacity: 0.5;
            text-shadow: 2px 2px 0 #000;">{{p.codigo}}</div> </l-icon>
      </l-marker>
    </l-map>
    <q-table :data="puntos" hide-header :columns="colums" :filter="filter">
      <template v-slot:body-cell-descripcion="props">
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
                <q-input dense outlined label="codigo" v-model="punto.codigo" />
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
                <q-input dense outlined label="fecha" v-model="punto.fecha" />
              </div>
              <div class="col-6">
                <q-input dense outlined label="cantidad" v-model="punto.cantidad" />
              </div>
              <div class="col-6">
                <q-input dense outlined label="destino" v-model="punto.destino" />
              </div>
              <div class="col-6">
                <q-input dense outlined label="dirigente" v-model="punto.dirigente" />
              </div>
              <div class="col-6">
                <q-input dense outlined label="celular" v-model="punto.celular" />
              </div>
              <div class="col-6">
                <q-input dense outlined label="distrito" v-model="punto.distrito" />
              </div>
              <div class="col-12">
                <q-input dense outlined label="tecnico" v-model="punto.tecnico" />
              </div>
              <div class="col-12">
                <q-input dense outlined label="descripcion" v-model="punto.descripcion" />
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

export default {
  data () {
    return {
      modalpunto:false,
      estados:['ACTIVO','MANTENIMIENTO'],
      estado:'ACTIVO',
      filter:'',
      colums:[
        {name:"nroposte",label:"nroposte",field:"nroposte"},
        {name:"descripcion",label:"descripcion",field:"descripcion"},
        {name:"potencia",label:"potencia",field:"potencia"},
        {name:"ubicacion",label:"ubicacion",field:"ubicacion"},
      ],
      puntos:[],
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
    this.mispuntos()
  },
  methods:{
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
      this.$axios.put('punto/'+this.punto.id,this.punto)
    },
    mispuntos(){
      this.$q.loading.show()
      this.$axios.get('punto').then(res=>{
        this.puntos=res.data
        this.$q.loading.hide()
        // console.log(this.puntos)
      })
    }
  },
}
</script>
