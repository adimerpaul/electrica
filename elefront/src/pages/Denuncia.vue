<template>
  <q-page>
    <l-map style="height: 50vh" :zoom="zoom" :center="center">
      <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
      <l-marker @click="frmmodalpunto(p)" v-for="p in puntostabla" :lat-lng="[p.lat,p.lng]" :key="p.id" >
        <!--        <l-icon><q-badge  :class="c.tipo=='PEDIDO'?'bg-green-5  text-italic':c.tipo=='PARADO'?'bg-yellow-5  text-italic':c.tipo=='NO PEDIDO'?'bg-red-5 text-italic':''"  class="q-pa-none" color="info" >{{c.Cod_Aut}}</q-badge></l-icon>-->
        <l-icon><q-badge :color="p.estado=='ATENDIDO'?'info':'negative'"  class="q-pa-xs" label="" /> <div style="color: white;
            font-size: 15px;
            font-family: 'Arial Black';
            opacity: 0.8;
            text-shadow: 2px 2px 0 #000;">{{p.nroposte}}</div> </l-icon>
      </l-marker>
    </l-map>
    <q-table :data="puntostabla" hide-header :columns="colums" :filter="filter">
      <template v-slot:body-cell-nombre="props">
        <q-td :props="props" @click="frmmodalpunto(props.row)">
          <div style="font-size: 0.8em;white-space: initial;" class="text-subtitle2">{{props.row.nombre}}-{{props.row.telefono}}</div>
        </q-td>
      </template>
<!--      <template v-slot:body-cell-potencia="props">-->
<!--        <q-td :props="props" @click="frmmodalpunto(props.row)">-->
<!--          {{props.row.potencia}} W-->
<!--        </q-td>-->
<!--      </template>-->
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
        <q-btn icon="refresh" color="info" @click="mispuntos"/>
        <q-btn icon="adjust" color="negative" @click="centro"/>
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
          <q-form @submit="updatedenuncia">
            <div class="row">
              <div class="col-12">
                <q-input dense outlined :disable="true" label="nombre" v-model="punto.nombre" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="ci" v-model="punto.ci" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="telefono" v-model="punto.telefono" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="zona" v-model="punto.zona" />
              </div>
              <div class="col-6">
                <q-input dense outlined :disable="true" type="textarea" label="direccion" v-model="punto.direccion" />
              </div>
              <div class="col-6">
                <q-input dense outlined :disable="true" type="textarea" label="reclamo" v-model="punto.reclamo" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="lat" v-model="punto.lat" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="lng" v-model="punto.lng" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="fecha" v-model="punto.fecha" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="hora" v-model="punto.hora" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="estado" v-model="punto.estado" />
              </div>
              <div class="col-12 flex flex-center">
                <q-btn label="generar ruta" class="full-width" color="primary" icon="map" type="a" target="_blank" :href="'https://www.google.com.bo/maps/place/'+punto.lat+','+punto.lng+'/@'+punto.lat+','+punto.lng+',17z/data=!3m1!4b1!4m6!3m5!1s0x0:0xeda9371aeb8c1574!7e2!8m2!3d-17.981432!4d-67.1061122?hl=es'"/>
              </div>
              <div class="col-12">
                <q-input required dense outlined label="tecnico" v-model="punto.tecnico" />
              </div>
              <div class="col-12">
                <q-input required dense outlined type="textarea" label="actividad" v-model="punto.actividad" />
              </div>
              <div class="col-12 row">
                <div class="col-3">
                  <div class="text-h6">LAMPARA W</div>
                  <q-input outlined dense v-model="punto.l70" type="text" label="70W" />
                  <q-input outlined dense v-model="punto.l150" type="text" label="150W" />
                  <q-input outlined dense v-model="punto.l250" type="text" label="250W" />
                  <q-btn dense color="yellow" icon="check" @click="regActividad" />
                </div>
                <div class="col-3">

                <div class="text-h6">REACTOR W</div>
                  <q-input outlined dense v-model="punto.r70" type="text" label="70W" />
                  <q-input outlined dense v-model="punto.r150" type="text" label="150W" />
                  <q-input outlined dense v-model="punto.r250" type="text" label="250W" />
                </div>
                <div class="col-3">

                <div class="text-h6">FOTOCELULA</div>
                  <q-input outlined dense v-model="punto.foto" type="text" label="Foto" />
                  <q-input outlined dense v-model="punto.base" type="text" label="Base" />
                </div>
                <div class="col-3">

                <div class="text-h6">VARIOS</div>
                  <q-input outlined dense v-model="punto.ignitor" type="text" label="IGNITOR" />
                  <q-input outlined dense v-model="punto.cable" type="number" step="0.01" label="CABLE M/L" />
                  <div class="row">
                    <q-toggle false-value="NO"  :label="`Contactor ${punto.contactor}`" true-value="SI" color="green" v-model="punto.contactor" />
                  <q-toggle false-value="NO"  :label="`Termico ${punto.termico}`" true-value="SI" color="green" v-model="punto.termico" />
                </div>
                  <q-input outlined dense v-model="punto.soquet" type="text" label="SOQUET" />
                </div>


              </div>
              <div class="col-6">
                <q-input dense outlined type="date" label="fechaman" v-model="punto.fechaman" />
              </div>
              <div class="col-6">
                <q-input dense outlined type="time" label="fechaman" v-model="punto.horaman" />
              </div>
              <div class="col-12 flex flex-center">
                <q-btn type="submit" label="Realizar mantenimiento" class="full-width" color="positive" icon="construction"/>
              </div>
<!--              <div class="col-12">-->
<!--                &lt;!&ndash;                <q-input dense outlined label="estado" v-model="punto.estado" />&ndash;&gt;-->
<!--                <q-select @input="cambioestado()" dense outlined label="estado" :options="estados" v-model="punto.estado" />-->
<!--              </div>-->
            </div>
            <!--            <pre>{{punto}}</pre>-->
          </q-form>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat label="OK" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="modalatendido" full-width>
      <q-card>
        <q-card-section>
          <div class="text-h6">Nro poste {{punto.nroposte}}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form @submit="updatedenuncia">
            <div class="row">
              <div class="col-12">
                <q-input dense outlined :disable="true" label="nombre" v-model="punto.nombre" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="ci" v-model="punto.ci" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="telefono" v-model="punto.telefono" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="zona" v-model="punto.zona" />
              </div>
              <div class="col-6">
                <q-input dense outlined :disable="true" type="textarea" label="direccion" v-model="punto.direccion" />
              </div>
              <div class="col-6">
                <q-input dense outlined :disable="true" type="textarea" label="reclamo" v-model="punto.reclamo" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="lat" v-model="punto.lat" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="lng" v-model="punto.lng" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="fecha" v-model="punto.fecha" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="hora" v-model="punto.hora" />
              </div>
              <div class="col-4">
                <q-input dense outlined :disable="true" label="estado" v-model="punto.estado" />
              </div>
              <div class="col-12 flex flex-center">
                <q-btn label="generar ruta" class="full-width" color="primary" icon="map" type="a" target="_blank" :href="'https://www.google.com.bo/maps/place/'+punto.lat+','+punto.lng+'/@'+punto.lat+','+punto.lng+',17z/data=!3m1!4b1!4m6!3m5!1s0x0:0xeda9371aeb8c1574!7e2!8m2!3d-17.981432!4d-67.1061122?hl=es'"/>
              </div>
              <div class="col-12">
                <q-input required dense outlined label="tecnico" v-model="punto.tecnico" />
              </div>
              <div class="col-12">
                <q-input required dense outlined type="textarea" label="actividad" v-model="punto.actividad" />
              </div>
              <div class="col-12">
                <div class="col-3">
                  <div class="text-h6">LAMPARA W</div>
                  <q-input v-model="punto.l70" type="text" label="70" />
                  <q-input v-model="punto.l150" type="text" label="150" />
                  <q-input v-model="punto.l250" type="text" label="250" />
                </div>
              </div>
              <div class="col-6">
                <q-input dense outlined type="date" label="fechaman" v-model="punto.fechaman" />
              </div>
              <div class="col-6">
                <q-input dense outlined type="time" label="fechaman" v-model="punto.horaman" />
              </div>
<!--              <div class="col-12 flex flex-center">-->
<!--                <q-btn type="submit" label="Realizar mantenimiento" class="full-width" color="positive" icon="construction"/>-->
<!--              </div>-->
              <!--              <div class="col-12">-->
              <!--                &lt;!&ndash;                <q-input dense outlined label="estado" v-model="punto.estado" />&ndash;&gt;-->
              <!--                <q-select @input="cambioestado()" dense outlined label="estado" :options="estados" v-model="punto.estado" />-->
              <!--              </div>-->
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

import {date} from "quasar";

export default {
  data () {
    return {
      modalpunto:false,
      modalatendido:false,
      estados:['ACTIVO','MANTENIMIENTO'],
      estado:'ACTIVO',
      filter:'',
      colums:[
        {name:"nroposte",label:"nroposte",field:"nroposte"},
        {name:"nombre",label:"nombre",field:"nombre"},
        // {name:"potencia",label:"potencia",field:"potencia"},
        {name:"ubicacion",label:"ubicacion",field:"ubicacion"},
      ],
      puntos:[],
      puntostabla:[],
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
    regActividad(){
      this.punto.actividad=''
      if(this.punto.l70!=''&&  this.punto.l70!=undefined && this.punto.l70!=null){ this.punto.actividad+='LAMPARA 70W COD '+ this.punto.l70 +',' }
      if(this.punto.l150!=''&&  this.punto.l150!=undefined && this.punto.l150!=null){ this.punto.actividad+='LAMPARA 150W COD '+ this.punto.l150 +',' }
      if(this.punto.l250!=''&&  this.punto.l250!=undefined && this.punto.l250!=null){ this.punto.actividad+='LAMPARA 250W COD '+ this.punto.l250+','  }
      if(this.punto.r70!=''&&  this.punto.r70!=undefined && this.punto.r70!=null){ this.punto.actividad+=' REACTOR 70W COD '+ this.punto.r70 +',' }
      if(this.punto.r150!=''&&  this.punto.r150!=undefined && this.punto.r150!=null){ this.punto.actividad+=' REACTOR 150W COD '+ this.punto.r150+','  }
      if(this.punto.r250!=''&&  this.punto.r250!=undefined && this.punto.r250!=null){ this.punto.actividad+=' REACTOR 250W COD '+ this.punto.r250 +',' }
      if(this.punto.foto!=''&&  this.punto.foto!=undefined && this.punto.foto!=null){ this.punto.actividad+=' FOTOCELULA FOTO  '+ this.punto.foto +',' }
      if(this.punto.base!=''&&  this.punto.base!=undefined && this.punto.base!=null){ this.punto.actividad+=' FOTOCELULA BASE  '+ this.punto.base+','  }

      if(this.punto.ignitor!=''&&  this.punto.ignitor!=undefined && this.punto.ignitor!=null){ this.punto.actividad+=' IGNITOR COD '+ this.punto.ignitor +',' }
      if(this.punto.cable!=''&&  this.punto.cable!=undefined && this.punto.cable!=null){ this.punto.actividad+=' CABLE M/L '+ this.punto.cable +',' }
      if(this.punto.contactor!=''&&  this.punto.contactor!=undefined && this.punto.contactor!=null && this.punto.contactor!='NO'){ this.punto.actividad+=' SI CONTACTOR,' }
      if(this.punto.termico!=''&&  this.punto.termico!=undefined && this.punto.termico!=null && this.punto.termico!='NO'){ this.punto.actividad+=' SI TERMICO,' }
      if(this.punto.soquet!=''&&  this.punto.soquet!=undefined && this.punto.soquet!=null){ this.punto.actividad+=' soquet COD '+ this.punto.soquet +',' }
    },
    updatedenuncia(){
      this.$q.loading.show()
      this.punto.supervisor=this.$store.state.login.user.name
      this.punto.estado='ATENDIDO'
      console.log(this.punto.id)
      this.modalpunto=false
      this.$axios.put('denuncia/1',this.punto).then(res=>{
        console.log(res.data)
        this.mispuntos()
      })
    },
    frmmodalpunto(p){
      // console.log(p)
      if (p.estado=='EN ESPERA'){
        this.modalpunto=true
      }else{
        this.modalatendido=true
      }

      this.punto=p
    },
    clickclientes(c){
      // console.log(c)
      // this.center = [-17.970310, -67.111780]
      this.center = [c.lat, c.lng]
      this.zoom= 18
    },
    // cambioestado(){
    //   // console.log(this.punto)
    //   this.$axios.put('punto/'+this.punto.id,this.punto)
    // },
    centro(){
      this.center = [-17.970310, -67.111780]
      this.zoom= 13
    },
    mispuntos(){
      this.$q.loading.show()
      this.$axios.get('denuncia').then(res=>{
        this.puntos=[]
        this.puntostabla=[]
        res.data.forEach(r=>{
          // let d=r
          r.fechaman=date.formatDate(new Date(),'YYYY-MM-DD')
          r.horaman=date.formatDate(new Date(),'HH:mm')
          if (r.estado=='EN ESPERA'){
            this.puntostabla.push(r)
          }
          this.puntos.push(r)
        })
        this.$q.loading.hide()
        // console.log(this.puntos)
      })
    }
  },
}
</script>
