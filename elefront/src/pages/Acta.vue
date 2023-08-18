<template>
<q-page padding>
  <center class="text-h5">ACTAS DE ENTREGA</center>
  <div class="row">
    <div class="col-md-3 q-pa-xs"><q-input v-model="ini" outlined dense type="date" label="Fecha Ini" /></div>
    <div class="col-md-3 q-pa-xs"><q-input v-model="fin" outlined dense type="date" label="Fecha Fin" /></div>
    <div class="col-md-3 q-pa-xs"><q-btn color="info" icon="search" label="Buscar" @click="searchActa" /></div>
  </div>
  <div class="col-12">
    <q-table
      :data="actas"
      :columns="columns"
      row-key="name"
      :filter="filter"
    >
    <template v-slot:top-right>
        <q-btn color="green" icon="assignment" label="Registrar" @click="dialogForm=true" v-if="$store.state.login.boolregacta" />
        <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:body-cell-op="props" >
        <q-td :props="props">
          <q-btn color="yellow" icon="edit" dense @click="cargarActa(props.row)" v-if="$store.state.login.booleditacta"/>
          <q-btn color="red" icon="delete" dense @click="eliminar(props.row)" v-if="$store.state.login.booleditacta"/>
          <q-btn color="info" icon="image" dense @click="modif=props.row; dialogImg=true" v-if="$store.state.login.booleditacta"/>
          <q-btn color="accent" icon="download" dense @click="descargar(props.row)"/>
        </q-td>
      </template>
  </q-table>

  </div>
  <q-dialog v-model="dialogForm" >
    <q-card style="width: 700px; max-width: 80vw;">
      <q-card-section class="row items-center">
        <span class="q-ml-sm text-h5">REGISTRO DE ACTA</span>
      </q-card-section>
      <q-form
        @submit.prevent="registrarActa"
        class="q-gutter-md"
      >

      <q-card-section>
        <div class="row">
          <div class="col-md-6 col-xs-12">
            <q-input v-model="datos.fecha" type="date" label="Fecha" outlined dense required/>
          </div>
          <div class="col-md-6 col-xs-12">
            <q-select v-model="datos.distrito" :options="distritos" label="Distrito" outlined dense required/>
          </div>
          <div class="col-md-6 col-xs-12">
                <q-input style="text-transform: uppercase" outlined dense label="Lugar" list="lugares"  v-model="datos.lugar" required/>
                    <datalist id="lugares">
                      <option v-for="r in lugares" :key="r.lugar">{{r.lugar}}</option>
                    </datalist>
          </div>
          <div class="col-md-6 col-xs-12">
            <q-select v-model="datos.tipo" :options="tipos" label="Tipo" outlined dense  required/>
          </div>
          <div class="col-md-6 col-xs-12">
            <q-input v-model="datos.cantidad" type="number" label="Cantidad" outlined dense required/>
          </div>
          <div class="col-md-6 col-xs-12">
            <q-input v-model="datos.luminaria" type="text" label="Luminaria" outlined dense />
          </div>
          <div class="col-md-6 col-xs-12"><q-select use-input @filter="filterTec" v-model="tecnico" :options="tecnicos" label="Tecnicos" outlined dense /></div>
          <div class="col-md-6 col-xs-12">
                <q-input style="text-transform: uppercase" outlined dense label="Vecino/Representate" list="vecinos"  v-model="datos.vecino" />
                    <datalist id="vecinos">
                      <option v-for="r in vecinos" :key="r.vecino">{{r.vecino}}</option>
                    </datalist>
          </div>
          <div class="col-md-6 col-xs-12">
            <q-input v-model="datos.observacion" type="text" label="Observacion" outlined dense/>
          </div>
          <div class="col-6 " v-if="datos.distrito!=undefined && datos.distrito!=''">
            <label for="">ARCHIVO Imagen / PDF</label><br>
            <input type="file" @change="getArch" class="archi">
            <div v-if="porcentaje >0" class="red">Subiendo ...{{porcentaje}}%</div>
          </div>
        </div>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn outlined dense label="Cancel" color="red" v-close-popup />
        <q-btn outlined dense label="registrar" color="green" type="submit" />
      </q-card-actions>
    </q-form>

    </q-card>
  </q-dialog>

  <q-dialog v-model="dialogImg" >
    <q-card style="width: 700px; max-width: 80vw;">
      <q-card-section class="row items-center">
        <span class="q-ml-sm">Modificacion Archivo</span>
      </q-card-section>
      <q-form
        @submit.prevent="updateActa"
        class="q-gutter-md"
      >

      <q-card-section >
        <div class="row">
          <div class="col-6 " >
            <label for="">ARCHIVO Imagen / PDF</label><br>
            <input type="file" @change="getArch" >
            <div v-if="porcentaje >0" class="red">Subiendo ...{{porcentaje}}%</div>
          </div>
        </div>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn outlined dense label="Cancel" color="red" v-close-popup />
        <q-btn outlined dense label="Modificar" color="yellow" type="submit" />
      </q-card-actions>
    </q-form>

    </q-card>
  </q-dialog>

  <q-dialog v-model="dialogMod" >
    <q-card style="width: 700px; max-width: 80vw;">
      <q-card-section class="row items-center">
        <span class="q-ml-sm">MODIFICACION DE FORMULARIO ACTA</span>
      </q-card-section>
      <q-form
        @submit.prevent="modificarActa"
        class="q-gutter-md"
      >

      <q-card-section>
        <div class="row">
          <div class="col-md-6 col-xs-12">
            <q-input v-model="modif.fecha" type="date" label="Fecha" outlined dense required/>
          </div>
          <div class="col-md-6 col-xs-12">
            <q-select v-model="modif.distrito" :options="distritos" label="Distrito" outlined dense required/>
          </div>
          <div class="col-md-6 col-xs-12">
                <q-input style="text-transform: uppercase" outlined dense label="Lugar" list="lugares"  v-model="modif.lugar" required/>
                    <datalist id="lugares">
                      <option v-for="r in lugares" :key="r.lugar">{{r.lugar}}</option>
                    </datalist>
          </div>
          <div class="col-md-6 col-xs-12">
            <q-select v-model="modif.tipo" :options="tipos" label="Tipo" outlined dense  required/>
          </div>
          <div class="col-md-6 col-xs-12">
            <q-input v-model="modif.cantidad" type="number" label="Cantidad" outlined dense required/>
          </div>
          <div class="col-md-6 col-xs-12">
            <q-input v-model="modif.luminaria" type="text" label="Luminaria" outlined dense />
          </div>
          <div class="col-md-6 col-xs-12"><q-select use-input @filter="filterTec" v-model="tecnico" :options="tecnicos" label="Tecnicos" outlined dense /></div>
          <div class="col-md-6 col-xs-12">
                <q-input style="text-transform: uppercase" outlined dense label="Vecino/Representate" list="vecinos"  v-model="modif.vecino" />
                    <datalist id="vecinos">
                      <option v-for="r in vecinos" :key="r.vecino">{{r.vecino}}</option>
                    </datalist>
          </div>
          <div class="col-md-6 col-xs-12">
            <q-input v-model="modif.observacion" type="text" label="Observacion" outlined dense/>
          </div>
        </div>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn outlined dense label="Cancel" color="red" v-close-popup />
        <q-btn outlined dense label="Modificar" color="yellow" type="submit" />
      </q-card-actions>
    </q-form>

    </q-card>
  </q-dialog>
</q-page>
</template>
<script>

import {date} from "quasar"

export default {
  data () {
    return {
      url:process.env.API,
      dialogForm:false,
      dialogImg:false,
      dialogMod:false,
      modif:{},
      filter:'',
      datos:{fecha:date.formatDate(Date.now(),'YYYY-MM-DD')},
      ini: date.formatDate(Date.now(),'YYYY-MM-DD'),
      fin: date.formatDate(Date.now(),'YYYY-MM-DD'),
      distritos:['D1','D2','D3','D4','D5','D6'],
      lugares:[],
      tecnicos:[],
      filtertecnicos:[],
      tecnico:{},
      archivo:'',
      porcentaje:0,
      vecinos:[],
      tipos:['NUEVAS INSTALACIONES','CAMBIO DE POTENCIA','MANTENIMIENTO','OTRO'],
      columns:[
        {label:'OP',name:'op',field:'op'},
        {label:'FECHA',name:'fecha',field:'fecha'},
        {label:'LUGAR',name:'lugar',field:'lugar'},
        {label:'DISTRITO',name:'distrito',field:'distrito'},
        {label:'TIPO',name:'tipo',field:'tipo'},
        {label:'LUMINARIA',name:'luminaria',field:'luminaria'},
        {label:'CANTIDAD',name:'cantidad',field:'cantidad'},
        {label:'TECNICO',name:'tecnico',field:'tecnico'},
        {label:'VECINO',name:'vecino',field:'vecino'},
        {label:'OBS',name:'observacion',field:'observacion'}

      ],
      actas:[]
      /*$table->date('fecha');
            $table->string('lugar'); // jv, urb , direccion, unidad edificio
            $table->string('distrito'); //1 al 5
            $table->string('tipo'); //nuevas, cambio de potencia, mantenimiento
            $table->string('luminaria'); //led, sodio, etc
            $table->integer('cantidad'); //
            $table->string('tecnico'); // nombre del tecnico acargo
            $table->string('observacion'); // otro detalle
            $table->string('archivo');*/
  };
},
  created() {
    this.searchActa()
    this.cargarTecnicos()
    this.cargalugar()
    //this.cargarvecino()
  },
  methods:{
    eliminar(acta){
      this.$q.dialog({
        title: 'Eliminar Acta',
        message: 'Esta Seguro de Eliminar Acta?',
        cancel: true,
        persistent: true
      }).onOk(() => {
      this.$q.loading.show();

        this.$axios.delete('acta/'+acta.id).then(res=>{
            this.$q.notify({
            color: 'red-4',
            textColor: 'white',
            icon: 'delete',
            message: 'Registrado eliminado'
          })
          this.searchActa()
        })
      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    },
    cargarActa(acta){
      this.modif=acta
      this.tecnico=acta.user
      this.tecnico.label=this.tecnico.name
      this.dialogMod=true
    },
    descargar(act){
        // console.log(lib)
        var fileName=this.url+'../archivos/'+act.archivo;
        window.open(fileName,  'download');
    },
    registrarActa(){

      this.$q.loading.show();
      this.datos.archivo=this.nameFile

      this.datos.tecnico=this.tecnico.name
      this.datos.user_id=this.tecnico.id
      this.$axios.post('acta', this.datos).then(res=>{
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'cloud_done',
          message: 'Registrado correctamente'
        })
        this.dialogForm=false
        this.datos={fecha:date.formatDate(Date.now(),'YYYY-MM-DD')}
        this.nameFile=''
        this.tecnico={label:''}
        this.porcentaje=0
        this.searchActa()
        this.cargalugar()
      })
    },
    modificarActa(){

      this.$q.loading.show();

      this.modif.tecnico=this.tecnico.name
      this.modif.user_id=this.tecnico.id
      this.$axios.put('acta/'+this.modif.id, this.modif).then(res=>{
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'cloud_done',
          message: 'Modificado correctamente'
        })
        this.dialogMod=false
        this.datos={fecha:date.formatDate(Date.now(),'YYYY-MM-DD')}
        this.nameFile=''
        this.searchActa()
        this.cargalugar()
      })
      },
    updateActa(){
      this.$q.loading.show();
      if(this.nameFile=='')
      return false
      this.modif.archivo=this.nameFile

      this.$axios.post('uparchivo', this.modif).then(res=>{
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'cloud_done',
          message: 'Agregado correctamente'
        })
        this.dialogImg=false
        this.datos={fecha:date.formatDate(Date.now(),'YYYY-MM-DD')}
        this.nameFile=''
        this.porcentaje=0
        this.searchActa()
      })
    },
    getArch(event){
      //Asignamos la imagen a  nuestra data
      // console.log(event.target)
      this.archivo = event.target.files[0];
      this.porcentaje = 0
      let id=''
      let dist=''
      if(this.datos.distrito!=undefined && this.datos.distrito!='')
       dist=this.datos.distrito
       else{
       dist=this.modif.distrito
       id=this.modif.id
      }
      const fd = new FormData()
      fd.append('file', this.archivo)
      fd.append('distrito', dist)
      fd.append('id', id)

      this.$axios.post('upload', fd, {
          headers: { 'Content-Type': 'multipart/form-data' },
          onUploadProgress: (progressEvent) => {
            this.porcentaje = Math.round((progressEvent.loaded / progressEvent.total) * 100)
          }
        }).then(res => {
          this.nameFile=res.data
           //console.log(res.data)
        })

    },
    cargalugar(){
      this.lugares=[]
      this.$axios.get('lugaresActa').then(res=>{
        this.lugares=res.data
      })
    },
    cargavecino(){
      this.vecinos=[]
      this.$axios.get('vecinosActa').then(res=>{
        this.vecinos=res.data
      })
    },
    searchActa(){
      if(this.ini==undefined || this.fin==undefined)
        return false
      this.$q.loading.show()

      this.$axios.post("listActa",{ini:this.ini,fin:this.fin}).then((res) => {
        console.log(res.data)
        this.actas=res.data
      this.$q.loading.hide()

      })

    },
    cargarTecnicos(){
      this.$axios.get("listUser").then((res) => {
        res.data.forEach(r => {
          r.label=r.name
        });
        this.tecnicos=res.data
        this.tecnico={label:''}
        this.filtertecnicos=this.tecnicos
      })
    },
    filterTec (val, update) {
        if (val === '') {
          update(() => {
            this.tecnicos = this.filtertecnicos

            // here you have access to "ref" which
            // is the Vue reference of the QSelect
          })
          return
        }

        update(() => {
          const needle = val.toLowerCase()
          this.tecnicos = this.filtertecnicos.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
        })
      },
  }
}
</script>
<style>
.archi{
  margin-right: 20px;
  border: none;
  background: #084cdf;
  padding: 10px 20px;
  border-radius: 10px;
  color: #fff;
  cursor: pointer;
  transition: background .2s ease-in-out;
}
</style>

