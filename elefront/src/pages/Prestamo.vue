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
              <q-btn color="accent" icon="keyboard_return" dense @click="devolver(props.row)" v-if="props.row.estado=='PRESTAMO'"/>
              <q-btn color="primary" icon="list" dense @click="verDatos(props.row)" v-if="props.row.estado=='DEVUELTO'"/>
            </q-td>
          </template>
          <template v-slot:body-cell-foto="props" >
            <q-td :props="props">
              <q-btn color="accent" icon="preview" dense @click="verimagen=props.row.foto; dialogVer=true"/>
            </q-td>
          </template>
      </q-table>

      <q-dialog v-model="dialogDev" >
        <q-card style="width: 700px; max-width: 80vw;">
          <q-card-section class="row items-center bg-accent text-white">
            <span class="q-ml-sm">DEVOLUCION DE HERRAMIENTA {{ prestamo2.material}}: {{ prestamo2.codigo}}</span>
          </q-card-section>
          <q-form
            @submit="onSubmit"
            class="q-gutter-md"
          >
          <q-card-section>
            <div class="row">
              <div class="col-6 q-pa-xs"><q-input v-model="dev.fechadev" type="date" label="Fecha" outlined dense requiered/></div>
              <div class="col-12 q-pa-xs"><q-input v-model="dev.observacion" type="text" label="Observacion" outlined dense /></div>
            </div>
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="Cancel"  color="primary" v-close-popup />
            <q-btn flat label="Registrar Devolucion" type="submit" color="green" />
          </q-card-actions> 
          </q-form>

        </q-card>
      </q-dialog>

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
            <div class="col-6 q-pa-xs"><q-input v-model="prestamo.dias" type="number" label="Dias Prestamo" outlined dense required/></div>
            <div class="col-6 q-pa-xs"><q-select v-model="boxtool" :options="boxtools" label="Herramientas" @input="cargarTool(boxtool)" outlined dense /></div>
            <div class="col-6 q-pa-xs"><q-select v-model="tool" :options="tools" option-label="codigo" label="Codigo" outlined dense /></div>
            <div class="col-12 q-pa-xs"><q-input v-model="prestamo.observacion" type="text" label="Observacion" outlined dense /></div>
            <div class="col-12">           
              <label for="">Imagen / FOTO</label><br>
              <q-uploader
                accept=".jpg, .png, .jpeg"
                @added="getImg"
                auto-upload
                max-files="1"
                label="Subir foto"
                flat
                max-file-size="5000000"
                @rejected="onRejected"
                bordered
              />
          </div>
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
        <q-card style="width: 700px; max-width: 80vw;">
          <q-card-section>
            <q-img
              :src="url+'../prestamos/'+verimagen"
              
            />
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="cerrar" color="primary" v-close-popup />
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
          prestamo:{'fecha':date.formatDate(Date.now(),'YYYY-MM-DD'),'tiempo':1},
          dialogForm:false,
          dialogImg:false,
          dialogMod:false,
          dialogPrestamo:false,
          dialogVer:false,
          dialogDev:false,
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
          prestamo2:{},
          columns:[
            {label:'OP',name:'op',field:'op'},
            {label:'FECHA',name:'fecha',field:'fecha', sortable:true},
            {label:'ESTADO',name:'estado',field:'estado', sortable:true},
            {label:'DESTINO',name:'destino',field:'destino', sortable:true},
            {label:'UNIDAD',name:'unidad',field:'unidad', sortable:true},
            {label:'FECHA DEV',name:'fecha Dev',field:'retorno'},
            {label:'CODIGO',name:'codigo',field:'codigo'},
            {label:'MATERIAL',name:'material',field:'material'},
            {label:'TECNICO',name:'tecnico',field:row=>row.tecnico.nombre, sortable:true},
            {label:'FOTO',name:'foto',field:'foto', sortable:true},
            {label:'OBS',name:'observacion',field:'observacion'}
    
          ],
          prestamos:[],
          dev:{}

      };
    },
      created() {
        this.getPrestamo()
        this.cargarTecnicos()
        this.getbox()
        //this.cargarvecino()
      },
      methods:{
        verDatos(pr){
          console.log(pr)
          this.$q.dialog({
        title: 'Datos Devolucion',
        message: '<b>FECHA: </b>'+pr.prestamodetalle[0].fechadev+'<br><b>Obs: </b>'+pr.prestamodetalle[0].observacion,
        html: true,
        persistent: false
      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
        },
        onSubmit(){
          this.dev.prestamo_id=this.prestamo2.id
          this.$axios.post("prestamodetalle",this.dev).then((res) => {
            this.dialogDev=false
            this.getPrestamo()
            this.getbox()
        })
        },
        devolver(pr){
          this.prestamo2=pr
          this.dev={fechadev:date.formatDate(Date.now(),'YYYY-MM-DD')}
          this.dialogDev=true
        },
        getImg(event){
      //Asignamos la imagen a  nuestra data
      // console.log(event.target)
      let dialog = this.$q.dialog({
        message: 'Subiendo... 0%',
      })
      let percentage = 0
      const fd = new FormData()
      fd.append('file', event[0])
        this.$axios.post('uploadImg', fd,
          {
            headers: { 'Content-Type': 'multipart/form-data' },
            onUploadProgress: (progressEvent) => {
              percentage = Math.round((progressEvent.loaded / progressEvent.total) * 100)
              dialog.update({
                message: `Subiendo... ${percentage}%`
              })
              if (percentage>=100){
                dialog.hide()
              }
            }
          })
          .then(res => {
            console.log(res.data)
            this.imagen=res.data
            this.loading=false
          })
          .catch(err => reject(err))

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
           if(this.imagen==''){
            return false
           }
           if(this.tool.id== undefined){
            return false
           }
           this.prestamo.tecnico_id=this.tecnico.id
           this.prestamo.foto=this.imagen
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
            this.dialogPrestamo=false
            this.imagen=''

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
        onRejected (rejectedEntries) {
          this.$q.notify({
            type: 'negative',
            message: `${rejectedEntries.length} el archivo paso las restricciones de validación`
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
    
    