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
              <div class="col-3">
                <q-input dense outlined label="latitud" v-model="punto.lat" required type="number" step="0.000001"/>
              </div>
              <div class="col-3">
                <q-input dense outlined label="longitud" v-model="punto.lng" required type="number" step="0.000001"  />
              </div>
              <div class="col-3">
                <q-input dense outlined label="Nro Poste" v-model="punto.nroposte"  required />
              </div>
              <div class="col-3">
                <q-select dense outlined label="Potencia"  v-model="punto.potencia" :options="['400w','360w','300w','250w','240w','200w','196w','150w','136w','125w','120w','100w','85w','75w','70w','60w','58w','50w','40w',
                  ,'36w','30w','20w','24w','18w','15w','12w','0']" v-on:click="cargarcolor" />
                  <q-btn color="primary" icon="check"  @click="cargarcolor" />
              </div>
              <div class="col-3">
                <q-select dense outlined label="Material" v-model="punto.material" :options="['Madera','Metalico','Cemento','Empotrado','Ornamental','Cigarro','Riel','Colgado','Otro']" />
              </div>
              <div class="col-3">
                <q-input dense outlined label="Luminaria" v-model="punto.luminaria" required />
              </div>
              <div class="col-3">
                <q-input dense outlined label="Cant Luminarias" v-model="punto.cantidad" type="number" required/>
              </div>
              <div class="col-3">
                <q-input dense outlined label="Altura" v-model="punto.altura" type="number" required/>
              </div>
              <div class="col-3">
                <q-select dense outlined label="Distrito" v-model="punto.distrito" :options="['D1','D2','D3','D4','D5']" required/>
              </div>
              <div class="col-3">
                <q-select :options="colors" dense outlined label="Color" v-model="punto.color" required/>
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
              <div class="col-3">
                <q-input dense outlined label="latitud" v-model="punto.lat" required type="number" step="0.000001"/>
              </div>
              <div class="col-3">
                <q-input dense outlined label="longitud" v-model="punto.lng" required type="number" step="0.000001"  />
              </div>
              <div class="col-3">
                <q-input dense outlined label="Nro Poste" v-model="punto.nroposte"  required />
              </div>
              <div class="col-3">
                <q-select dense outlined label="Potencia"  v-model="punto.potencia" :options="['400w','360w','300w','250w','240w','200w','196w','150w','136w','125w','120w','100w','85w','75w','70w','60w','58w','50w','40w',
                  ,'36w','30w','20w','24w','18w','15w','12w','0']" v-on:click="cargarcolor" />
                  <q-btn color="primary" icon="check"  @click="cargarcolor" />
              </div>
              <div class="col-3">
                <q-select dense outlined label="Material" v-model="punto.material" :options="['Madera','Metalico','Cemento','Empotrado','Ornamental','Cigarro','Riel','Colgado','Otro']" />
              </div>
              <div class="col-3">
                <q-input dense outlined label="Luminaria" v-model="punto.luminaria" required />
              </div>
              <div class="col-3">
                <q-input dense outlined label="Cant Luminarias" v-model="punto.cantidad" type="number" required/>
              </div>
              <div class="col-3">
                <q-input dense outlined label="Altura" v-model="punto.altura" type="number" required/>
              </div>
              <div class="col-3">
                <q-select dense outlined label="Distrito" v-model="punto.distrito" :options="['D1','D2','D3','D4','D5']" required/>
              </div>
              <div class="col-3">
                <q-select :options="colors" dense outlined label="Color" v-model="punto.color" required/>
              </div>
              <div class="col-12">
                <q-input dense outlined label="Observacion" v-model="punto.observacion" required />
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
      <div class="col-6 "><q-select dense v-model="distrito" :options="distritos" label="Distritos" ourlined /></div>
      <div class="col-3"><q-btn color="green" icon="search" @click="onClick" /></div>
      <div class="col-3"><q-btn color="teal" icon="add_circle_outline" @click="punto={}; dialogRegistro=true" /></div>
    </div>
    <div>
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
 }">
    <!-- use the default slot to pass markers to it -->

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
    <q-table :data="puntos" :columns="colums" :filter="filter">
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
      dialogRegistro:false,

      mantenimiento:'',
        distritos:[
          {label:'Distrito 1',value:'D1'},
          {label:'Distrito 2',value:'D2'},
          {label:'Distrito 3',value:'D3'},
          {label:'Distrito 4',value:'D4'},
          {label:'Distrito 5',value:'D5'},
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
      poten:[
       {'potencia':'400w','colores':['l400.png','p400.png']},
       {'potencia':'360w','colores':['l360.png']},
       {'potencia':'300w','colores':['l300.png']},
       {'potencia':'250w','colores':['l250.png','p250.png']},
       {'potencia':'240w','colores':['l240.png']},
       {'potencia':'200w','colores':['l200.png']},
       {'potencia':'196w','colores':['l196.png']},
       {'potencia':'150w','colores':['l150.png','p150.png']},
       {'potencia':'136w','colores':['l136.png']},
       {'potencia':'125w','colores':['l125.png']},
       {'potencia':'120w','colores':['l120.png']},
       {'potencia':'100w','colores':['l100.png']},
       {'potencia':'85w','colores':['l85.png']},
       {'potencia':'75w','colores':['l75.png']},
       {'potencia':'70w','colores':['l70.png','p70.png']},
       {'potencia':'60w','colores':['l60.png']},
       {'potencia':'58w','colores':['l58.png','p58.png']},
       {'potencia':'50w','colores':['l50.png','p50.png']},
       {'potencia':'40w','colores':['l40.png']},
      {'potencia':'36w','colores':['l36.png']},
      {'potencia':'30w','colores':['l30.png']},
      {'potencia':'20w','colores':['l20.png']},
      {'potencia':'24w','colores':['l24.png']},
      {'potencia':'18w','colores':['l18.png']},
      {'potencia':'15w','colores':['l15.png']},
      {'potencia':'12w','colores':['l12.png']},
      {'potencia':'0','colores':['sn.png']}],
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
  },
  methods:{
      cargarcolor:function(event){
        console.log('sdfds')
        if(this.punto.potencia=='')
          this.colors=[]
          console.log(this.punto.potencia)

        this.poten.forEach(r=>{
          if(r.potencia==this.punto.potencia)
          {this.colors=r.colores
          this.punto.color=this.colors[0]}
        })
      },
      regPoste(){
      this.$axios.post('poste',this.punto).then(res=>{
        this.dialogRegistro=false
        this.mispuntos
      })

      },
updatePoste(){
  console.log(this.punto)
  //return false
    this.$axios.post('upPoste',this.punto).then(res=>{
    //this.mispuntos()
    this.modalpunto=false
  })

},
    onClick(){
      console.log(this.distrito.value)
      switch (this.distrito.value) {
        case 'D1': this.datos=this.d1;break;
        case 'D2': this.datos=this.d2;break;
        case 'D3': this.datos=this.d3;break;
        case 'D4': this.datos=this.d4;break;
        case 'D5': this.datos=this.d5;break;

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
      this.center = {lat:c.lat, lng:c.lng}
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
      this.$axios.get('poste').then(res=>{
        this.puntos=res.data
        res.data.forEach(r => {
          if(r.distrito=='D1') this.d1.push(r)
          if(r.distrito=='D2') this.d2.push(r)
          if(r.distrito=='D2 EXT') this.d2.push(r)
          if(r.distrito=='D3') this.d3.push(r)
          if(r.distrito=='D4') this.d4.push(r)
          if(r.distrito=='D4 EXT') this.d4.push(r)
          if(r.distrito=='D5') this.d5.push(r)
          if(r.distrito=='D5 EXT') this.d5.push(r)
        });
        this.$q.loading.hide()
         console.log(this.puntos)
      })
    }
  },

}
</script>




