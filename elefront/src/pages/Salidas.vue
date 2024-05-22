<template>
  <q-page padding>
    <div class="text-h5 text-center">SALIDA DE MATERIAL</div>
    <div><q-btn color="green" icon="check" label="Registro" @click="detalle=[]; salida={}; dialogReg=true" /></div>
    <div class="row">
      <div class="col-4 q-pa-xs"><q-input dense v-model="fecha" type="date" label="Fecha" outlined/></div>
      <div class="col-4"><q-btn color="green" icon="search" label="Buscar" @click="generarList" /></div>
      <div class="col-4"><q-btn color="info" icon="print" label="Impresion" @click="printDia" /></div>
      <div class="col-4 q-pa-xs"><q-input dense v-model="fecha2" type="date" label="Fecha2" outlined/></div>
      <div class="col-4"><q-btn color="accent" icon="print" label="Reporte" @click="printReport" /></div>
    </div>
    <div class="col-12">
      <q-table
        title="DATOS DE SALIDA"
        :data="salidas"
        :columns="colsalida"
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
      <template v-slot:body-cell-elementos="props">
              <q-td key="elementos" :props="props">
                <div v-for=" mat in props.row.elementos" :key="mat.id">
                  {{ mat.cantidad }} {{ mat.material }} - {{ mat.inventario.num }} {{ mat.inventario.letra }}
                </div>

              </q-td>
            </template>
            <template v-slot:body-cell-opcion="props">
              <q-td key="opcion" :props="props">
                <q-btn color="info" icon="print" dense @click="printSalida(props.row)" />
                <q-btn color="yellow" icon="edit" dense @click="modificar(props.row)" />
              </q-td>
            </template>
      </q-table>
    </div>
    <q-dialog v-model="dialogReg" full-width>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="shopping_cart" color="primary" text-color="white" dense size="40px"/>
          <span class="q-ml-sm">REGISTRO SALIDA DE MATERIAL</span>
        </q-card-section>
        <q-card-section>
          <div class="row">
          <div class="col-md-6 col-xs-12"><q-select use-input @filter="filterTec" v-model="tecnico" :options="tecnicos" label="Tecnicos" outlined dense /></div>
          <div class="col-md-6 col-xs-12"><q-input v-model="salida.destino" type="text" label="Destino" outlined dense /></div>
          <div class="col-md-6 col-xs-12"><q-input v-model="salida.motivo" type="text" label="Motivo" outlined dense/></div>
          <div class="col-md-6 col-xs-12"><q-select v-model="salida.carro" :options="carros" label="Carro" outlined dense/></div>

          <div class="col-6" :style="'background-color: '+ material.color">
            <q-select outlined dense use-input @filter="filterMat" v-model="material" :options="materiales" label="Material" >

            <template v-slot:option="scope">
                <q-item v-bind="scope.itemProps" >
                  <q-item-section :style="'background-color: '+ scope.opt.color" @click="material=scope.opt;">
                    <q-item-label :style="scope.opt.style" >{{ scope.opt.label }}</q-item-label>
                  </q-item-section>
                </q-item>
              </template>
            </q-select></div>
          <div class="col-4"><q-input dense outlined v-model="cantidad" type="number" label="Cantidad" /></div>
          <div class="col-2"><q-btn color="green" icon="add_circle_outline" @click="onclick" /></div>
          <!--
          <div class="col-12">
            <q-form
              @submit.prevent="regList"
            >
                <q-input outlined   v-model="codigo" type="text" label="Codigo Material"  dense autofocus/>
            </q-form>
          </div>-->
        </div>
          <div class="col-12">
            <q-table
              title="Detalle"
              :data="detalle"
              :columns="columna"
              row-key="name"
              dense
            >

            <template v-slot:body-cell-opcion="props">
              <q-td key="opcion" :props="props">
                  <q-btn color="red" icon="delete" dense @click="quitarLista(props)"/>
              </q-td>
            </template>
            <template v-slot:body-cell-material="props">
              <q-td key="material" :props="props">
                {{ props.row.material.nombre }}
              </q-td>
            </template>
            </q-table>
          </div>
          <div class="row">
            <div class="col-4"><q-btn dense color="red" icon="cancel" label="CANCELAR"  v-close-popup /></div>
            <div class="col-4"><q-btn dense color="green" icon="check" label="REGISTRAR" @click="registroSalida" /></div>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="dialogMod" full-width>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="shopping_cart" color="primary" text-color="white" dense size="40px"/>
          <span class="q-ml-sm">MODIFICAR SALIDA DE MATERIAL</span>
        </q-card-section>
        <q-card-section>
          <div class="row">
          <div class="col-md-6 col-xs-12"><q-input v-model="salida2.destino" type="text" label="Destino" outlined dense /></div>
          <div class="col-md-6 col-xs-12"><q-input v-model="salida2.motivo" type="text" label="Motivo" outlined dense/></div>

        </div>
      </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="col-4"><q-btn dense color="red" icon="cancel" label="CANCELAR"  v-close-popup /></div>
            <div class="col-4"><q-btn dense color="yellow" icon="edit" label="MODIFICAR" @click="modSalida" /></div>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  <div id="myelement" class="hidden"></div>

  </q-page>
</template>
<script>
import { METHODS } from 'http';
import { Notify } from 'quasar';
import { date } from 'quasar'
import {Printd} from "printd";

export default {
  data() {
    return {
      filter:'',
      salida:{},
      salida2:{},
      cantidad:1,
      material:{label:''},
      fecha:date.formatDate(new Date(),'YYYY-MM-DD'),
      fecha2:date.formatDate(new Date(),'YYYY-MM-DD'),
      salidas:[],
      materiales:[],
      filterMateriales:[],
      tecnico:{},
      filtertecnicos:[],
      tecnicos:[],
      detalle:[],
      columna:[
        {name:'cant',label:'Cantidad',field:'cant'},
        {name:'codigo',label:'codigo',field:'codigo'},
        {name:'material',label:'material',field:'material'},
        {name:'estado',label:'estado',field:'estado'},
        {name:'opcion',label:'opcion',field:'opcion'},
      ],
      colsalida:[
        {name:'tecnico',label:'TECNICO',field:row=>row.tecnico.name},
        {name:'carro',label:'CARRO',field:'carro'},
        {name:'destino',label:'DESTINO',field:'destino'},
        {name:'motivo',label:'MOTIVO',field:'motivo'},
        {name:'elementos',label:'MATERIALES',field:'elementos'},
        {name:'opcion',label:'OPCION',field:'opcion'},

      ],
      codigo:'',
      carros:['','CARRO VALDE 1','CARRO VALDE 2','CARRO VALDE 3','CARRO VALDE AZUL','CAMIONETA ROJA','CAMIONETA ROJA II','CAMIONETA BLANCA','CARRO DE TRAFICO','C Dependencia','C Parques y Plazas','C Posteadores','Linea Piloto','CARRO VALDE 5'],
      dialogReg:false,
      dialogMod:false,
    }
  },
  created() {
    this.getMaterial()
    this.cargarTecnicos()
    this.generarList()
  },
  methods: {
    printReport(){
      this.$axios.post("reporteMaterial",{ini:this.fecha,fin:this.fecha2}).then((res) => {
        console.log(res.data)
        if(res.data.length>0)
        {
          let cadena="<style>\
      *{font-size:10px;}\
      .table1{\
        width:100%;\
        border-collapse: collapse;\
      }\
      table, th, td {\
        border: 1px solid;\
      }\
      .titulo{\
      text-align:center;\
      font-weight:bold;\
      }\
      </style>\
      <div id='myelement'>\
      <div class='titulo'>SALIDA DE MATERIAL DE "+this.fecha+" AL "+this.fecha2+"</div> <br>\
      <table class='table1'><tr><th>CODIGO</th> <th>CANTIDAD</th><th>MATERIAL</th><th>STOCK_ALMACEN</th><tr>"

      res.data.forEach(r => {

        cadena+='<tr><td>'+r.codigo+'</td><td>'+r.cantidad+'</td><td>'+r.material+'</td><td>'+r.almacen+'</td></tr> '

      })
      cadena+="</table></div>"

      document.getElementById('myelement').innerHTML = cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )
        }
      })

    },
    onclick(){
      if(this.material.id==undefined){
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: "DEBE REGISTRAR MATERIAL"
        })
        return false
      }
      if(this.cantidad<=0 || this.cantidad=='' || this.cantidad==undefined){
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: "DEBE Ingresar cantidad correcta"
        })
        return false
      }
      if(this.material.stock < this.cantidad){
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: "No ay Suficiente Material",
        });
        return false
      }
      this.material.cantidad=this.cantidad
      this.$q.loading.show()
      this.$axios.post("inventDisponible",this.material).then((res) => {
        console.log(res.data)
          if( this.material.codificar=='SI'){
            res.data.forEach(r => {
              let valida=this.detalle.find(d=> d.codigo==r.codigo)
                if(valida==undefined){
                r.cant=1
              this.detalle.push(r)
            }
            })
          }
          else{

              let valida=this.detalle.find(d=> d.codigo===res.data.codigo)
              console.log(valida)
                if(valida==undefined){
                  res.data.cant=this.cantidad
                  this.detalle.push(res.data)
                }
                else{
                  if((valida.cant + parseInt(this.cantidad)) <= this.material.stock)
                  {
                    valida.cant =parseInt(valida.cant) + parseInt(this.cantidad)
                  }
                }

        }
        this.$q.loading.hide()

          console.log(this.detalle)

      }).catch(error=>{
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: error.message
        })
      })

    },
    filterMat (val, update) {
        if (val === '') {
          update(() => {
            this.materiales = this.filterMateriales

            // here you have access to "ref" which
            // is the Vue reference of the QSelect
          })
          return
        }

        update(() => {
          const needle = val.toLowerCase()
          this.materiales = this.filterMateriales.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
        })
      },
    getMaterial(){
      this.materiales=[]
      this.$axios.get("listmaterial").then((res) => {
        res.data.forEach(r => {
          r.label=r.nombre
          this.materiales.push(r)
        })
        this.filterMateriales=this.materiales
      })

    },
    generarList(){
      if(this.fecha==undefined || this.fecha=='')
        return false
      this.$axios.post("listsalida",{fecha:this.fecha}).then((res) => {
        this.salidas=res.data
      })

    },
    registroSalida(){
      if(this.detalle.length==0){
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: "DEBE REGISTRAR MATERIAL",
        });
        return false
      }
      if(this.tecnico.id==undefined){
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: "Debe Seleccioanr tecnico",
        });
        return false
      }
      this.salida.user_id=this.$store.state.login.user.id
      this.salida.tecnico=this.tecnico.id
      this.salida.detalle=this.detalle
      this.$q.loading.show()

      this.$axios.post("salida",this.salida).then((res) => {
        this.$q.notify({
          color: "green-4",
          icon: "info",
          message: "Regsitrado",
        });
        console.log(res.data)
        this.salida={}
        this.tecnico={label:''}
        this.detalle=[]
        this.generarList()
        this.dialogReg=false
        this.$q.loading.hide()
      }).catch(error=>{
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: error.message,
        });
        this.$q.loading.hide()

      })

    },
    modificar(registro){
      this.salida2=registro
      this.dialogMod=true
    },
    modSalida(){
      this.$q.loading.show()

      this.$axios.put("salida/"+this.salida2.id,this.salida2).then((res) => {
        this.$q.notify({
          color: "green-4",
          icon: "info",
          message: "Modificado",
        });
        console.log(res.data)
        this.salida2={}
        this.dialogMod=false
    this.generarList()
        this.$q.loading.hide()
      }).catch(error=>{
        this.$q.notify({
          color: "red-4",
          icon: "info",
          message: error.message,
        });
        this.$q.loading.hide()

      })

    },
    quitarLista(props){
      console.log(props)
      this.detalle.splice(props.rowIndex,1);
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
    printSalida(salida){
      let contenido=''
      salida.elementos.forEach(r => {
        contenido+='<tr><td>'+r.cantidad+'</td><td>'+r.material+'</td><td>'+r.inventario.codigo+'</td></tr>'
      });
      let cadena="<style>\
      table{\
        width:100%;\
      }\
      .titulo{\
      text-align:center;\
      font-weight:bold;\
      }\
      </style>\
      <div id='myelement'>\
        <table style='padding:5px'><tr><td>\
      <div class='titulo'>SALIDA DE ALMACEN</div>\
      <div><b>FECHA: </b>"+salida.fecha+" "+salida.hora+"</div>\
      <div><b>TECNICO: </b>"+salida.tecnico.name+"</div>\
      <div><b>CARRO: </b>"+salida.carro+"</div>\
      <div><b>DESTINO: </b>"+salida.destino+"</div>\
      <div><b>MOTIVO: </b>"+salida.motivo+"</div><hr>\
      <div class='titulo'><b>DETALLE </b></div>\
      <table style='padding:10px'><tr><th>CANTIDAD</th><th>MATERIAL</th><th>CODIGO</th></tr>"+contenido+"</table>\
        </td><td>\
          <div class='titulo'>SALIDA DE ALMACEN</div>\
      <div><b>FECHA: </b>"+salida.fecha+" "+salida.hora+"</div>\
      <div><b>TECNICO: </b>"+salida.tecnico.name+"</div>\
      <div><b>CARRO: </b>"+salida.carro+"</div>\
      <div><b>DESTINO: </b>"+salida.destino+"</div>\
      <div><b>MOTIVO: </b>"+salida.motivo+"</div><hr>\
      <div class='titulo'><b>DETALLE </b></div>\
      <table style='padding:10px'><tr><th>CANTIDAD</th><th>MATERIAL</th><th>CODIGO</th></tr>"+contenido+"</table></td></tr>"


      cadena+=`</table></div>
      `
      document.getElementById('myelement').innerHTML = cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )
    },
    printDia(){
      let contenido=''

      let cadena="<style>\
      *{font-size:10px;}\
      table{\
        width:100%;\
      }\
      .titulo{\
      text-align:center;\
      font-weight:bold;\
      }\
      </style>\
      <div id='myelement'>\
      <div class='titulo'>SALIDA DE ALMACEN</div>"
      this.salidas.forEach(r => {
        cadena+="<div><b>FECHA: </b>"+r.fecha+" "+r.hora+"</div>\
      <div><b>TECNICO: </b>"+r.tecnico.name+"</div>\
      <div><b>CARRO: </b>"+r.carro+"</div>\
      <div><b>DESTINO: </b>"+r.destino+"</div>\
      <div><b>MOTIVO: </b>"+r.motivo+"</div><hr>\
      <div class='titulo'><b>DETALLE </b></div>\
      <table style='padding:5px'><tr><th>CANTIDAD</th><th>MATERIAL</th><th>CODIGO</th><th>ORDEN</th></tr>"
        r.elementos.forEach(e => {

        cadena+='<tr><td>'+e.cantidad+'</td><td>'+e.material+'</td><td>'+e.inventario.codigo+'</td><td>'+e.inventario.tiporden+' '+e.inventario.norden+'</td></tr> '

        })
        cadena+='</table><hr style=" border: 1px solid red;">'
      })
      cadena+="</div>"

      document.getElementById('myelement').innerHTML = cadena
      const d = new Printd()
      d.print( document.getElementById('myelement') )
    },
    regList(){
      this.$axios.get("consultaInv/"+this.codigo).then((res) => {
        console.log(res.data)
        if(res.data!=''){
          let valida=this.detalle.find(d=> d.codigo===this.codigo)
          console.log(valida)
          if(valida==undefined){
            res.data.cant=1
            this.detalle.push(res.data)
          }
            else{
              if(valida.cant>=res.data.cantidad)
              {

              }
              else{
                valida.cant+=1
              }
            console.log(valida)

        }
          console.log(this.detalle)
        }
        this.codigo=''
      })

    }
  }

}
</script>
