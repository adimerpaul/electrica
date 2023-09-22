<template>
    <q-page padding>
      <center class="text-h5">REGISTRO DE PRESTAMO</center>
      <div class="row">
        <div class="col-md-5 q-pa-xs"><q-input v-model="ini" outlined dense type="date" label="Fecha Ini" /></div>
        <div class="col-md-5 q-pa-xs"><q-input v-model="fin" outlined dense type="date" label="Fecha Fin" /></div>
        <div class="col-md-2 q-pa-xs"><q-btn color="info" icon="search" label="Buscar" @click="getPrestamo" /></div>
      </div>
      <div class="col-12">
        <q-table
          :data="prestamos"
          :columns="columns"
          row-key="name"
          :filter="filter"
        >
        <template v-slot:top-right>
          <q-btn color="green" icon="history_edu" label="Registro" @click="dialogPrestamo=true" dense/>
            <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
          <template v-slot:body-cell-op="props" >
            <q-td :props="props">
              <q-btn color="accent" icon="download" dense @click="descargar(props.row)"/>
            </q-td>
          </template>
          <template v-slot:body-cell-foto="props" >
            <q-td :props="props">
              <q-btn color="accent" icon="preview" dense @click="verimagen=props.row.foto; dialogVer=true"/>
            </q-td>
          </template>
      </q-table>
      <q-dialog v-model="dialogPrestamo" >
        <q-card style="width: 700px; max-width: 80vw;">
          <q-card-section class="row items-center bg-green text-white text-bold">
            <span class="q-ml-sm">REGISTRO DE PRESTAMO</span>
          </q-card-section>
          <q-form
            @submit="regPrestamo"
            class="q-gutter-md"
          >
          <q-card-section>
            <div class="row">
            <div class="col-6 q-pa-xs"><q-select v-model="tecnico" :options="tecnicos" label="TECNICO" outlined dense /></div>
            <div class="col-6 q-pa-xs"><q-input v-model="prestamo.destino" type="text" label="Destino" outlined dense required /></div>
            <div class="col-6 q-pa-xs"><q-input v-model="prestamo.fecha" type="date" label="Fecha" outlined dense required/></div>
            <div class="col-6 q-pa-xs"><q-input v-model="prestamo.tiempo" type="number" label="Dias Prestamo" outlined dense required/></div>
            <div class="col-6 q-pa-xs"><q-select v-model="boxtool" :options="boxtools" label="Herramientas" @input="cargarTool(boxtool)" outlined dense /></div>
            <div class="col-6 q-pa-xs"><q-select v-model="tool" :options="tools" option-label="codigo" label="Codigo" outlined dense /></div>
            <div class="col-12 q-pa-xs"><q-input v-model="prestamo.observacion" type="text" label="Observacion" outlined dense /></div>
            <div class="col-12">           
              <label for="">Imagen / FOTO</label><br>
            <input type="file" @change="getImg" class="archi">
            <div v-if="porcentaje >0" class="red">Subiendo ...{{porcentaje}}%</div></div>
          </div>
          </q-card-section>

            <div>
          <q-card-actions align="right">
            <q-btn flat label="CANCELAR" color="primary" v-close-popup dense/>
              <q-btn flat label="REGISTRAR" type="submit" color="green" dense/>
          </q-card-actions>
            </div>
          </q-form>

        </q-card>
      </q-dialog>
      <q-dialog v-model="dialogVer" >
        <q-card>
          <q-card-section>
            <q-img
              :src="url+'../prestamos/'+verimagen"
              :ratio="16/9"
              spinner-color="primary"
              spinner-size="82px"
            />
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="Cancel" color="primary" v-close-popup />
            <q-btn flat label="Turn on Wifi" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>
      </div>    
    </q-page>
    </template>
    <script>
    
    import {Notify, date} from "quasar"
    import moment from "moment" 

    export default {
      data () {
        return {
          url:process.env.API,
          verimagen:'',
          prestamo:{'fecha':moment().format('YYYY-MM-DD'),'tiempo':1},
          dialogForm:false,
          dialogImg:false,
          dialogMod:false,
          dialogPrestamo:false,
          dialogVer:false,
          modif:{},
          filter:'',
          datos:{fecha:date.formatDate(Date.now(),'YYYY-MM-DD')},
          ini: moment().add(-30,'days').format('YYYY-MM-DD'),
          fin: moment().format('YYYY-MM-DD'),
          tecnicos:[],
          filtertecnicos:[],
          tecnico:{},
          imagen:'',
          porcentaje:0,
          vecinos:[],
          boxtools:[],
          boxtool:{},
          nameFile:'',
          tool:{},
          tools:[],
          columns:[
            {label:'OP',name:'op',field:'op'},
            {label:'FECHA',name:'fecha',field:'fecha', sortable:true},
            {label:'DESTINO',name:'destino',field:'destino', sortable:true},
            {label:'UNIDAD',name:'unidad',field:'unidad', sortable:true},
            {label:'FECHA DEV',name:'fecha Dev',field:'retorno'},
            {label:'CODIGO',name:'codigo',field:'codigo'},
            {label:'MATERIAL',name:'material',field:'material'},
            {label:'TECNICO',name:'tecnico',field:row=>row.tecnico.name, sortable:true},
            {label:'FOTO',name:'foto',field:'foto', sortable:true},
            {label:'OBS',name:'observacion',field:'observacion'}
    
          ],
          prestamos:[]

      };
    },
      created() {
        this.getPrestamo()
        this.cargarTecnicos()
        this.getbox()
        //this.cargarvecino()
      },
      methods:{
        getImg(event){
      //Asignamos la imagen a  nuestra data
      // console.log(event.target)
      this.imagen = event.target.files[0];
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
      fd.append('file', this.imagen)

      this.$axios.post('uploadImg', fd, {
          headers: { 'Content-Type': 'multipart/form-data' },
          onUploadProgress: (progressEvent) => {
            this.porcentaje = Math.round((progressEvent.loaded / progressEvent.total) * 100)
          }
        }).then(res => {
          this.nameFile=res.data
           //console.log(res.data)
        })

    },
        cargarTool(bt){
          console.log(bt)
          this.tools=bt.toolsactivo
          this.tool=this.tools[0]
        },
        regPrestamo(){
          if(this.tecnico.id==undefined)
           {
            return false
           }
           if(this.nameFile==''){
            return false
           }
           if(this.tool.id== undefined){
            return false
           }
           this.prestamo.tecnico_id=this.tecnico.id
           this.prestamo.foto=this.nameFile
           this.prestamo.tool_id=this.tool.id
           this.prestamo.codigo=this.tool.codigo
           this.prestamo.material=this.tool.nombre
          this.$q.loading.show()    
          this.$axios.post("prestamo",this.prestamo).then((res) => {
            this.$q.notify({
          message: 'Prestamo registrado',
          color: 'green',
          icon:'info'
        })
            this.getPrestamo()
            this.getbox()
            
            this.$q.loading.hide()    

          })

        },
        getbox(){
          this.boxtools=[]
          this.$axios.get("listBox").then((res) => {
            console.log(res.data)
            res.data.forEach(r => {
              r.label=r.nombre
              this.boxtools.push(r)
            });
            this.boxtool=this.boxtools[0]
            this.tools=this.boxtool.toolsactivo
            this.tool=this.tools[0]
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
    
        getPrestamo(){
          if(this.ini==undefined || this.fin==undefined)
            return false

          this.$q.loading.show()    
          this.$axios.post("listPrestamo",{ini:this.ini,fin:this.fin}).then((res) => {
            console.log(res.data)
            this.prestamos=res.data
          this.$q.loading.hide()
    
          })
    
        },
        cargarTecnicos(){
          this.$axios.get("tecnico").then((res) => {
            console.log(res.data)
            res.data.forEach(r => {
              r.label=r.nombre
            })
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
    
    