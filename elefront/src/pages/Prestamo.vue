<template>
    <q-page padding>
      <center class="text-h5">REGISTRO DE PRESTAMO</center>
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
      </q-table>
    
      </div>
    
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
            {label:'FECHA',name:'fecha',field:'fecha', sortable:true},
            {label:'LUGAR',name:'lugar',field:'lugar', sortable:true},
            {label:'DISTRITO',name:'distrito',field:'distrito', sortable:true},
            {label:'TIPO',name:'tipo',field:'tipo'},
            {label:'LUMINARIA',name:'luminaria',field:'luminaria'},
            {label:'CANTIDAD',name:'cantidad',field:'cantidad'},
            {label:'TECNICO',name:'tecnico',field:'tecnico', sortable:true},
            {label:'VECINO',name:'vecino',field:'vecino', sortable:true},
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
        //this.cargarvecino()
      },
      methods:{
    
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
    
    