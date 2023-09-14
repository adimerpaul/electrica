<template>
    <div class="q-pa-md">
      <center class="text-h5">ALMACEN DE HERRAMIENTAS</center>


      
      <q-table dense :filter="filter" title="ALMACEN" :data="boxtools" :columns="columns" row-key="name" :rows-per-page-options="[0,50,100]">
        <template v-slot:top-right>
            <div class="q-pa-xs"> <q-btn label="Registro" color="positive" @click="box={tipo:'SI'}; dialogReg = true" icon="add_circle"  class="q-mb-xs" /></div>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Buscar">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body-cell-opcion="props">
          <q-td key="opcion" :props="props">
            <q-btn dense round flat color="yellow" @click="editRow(props)" icon="edit" />

            <q-btn dense round flat color="red" @click="deleteRow(props)" icon="delete" />
          </q-td>
      </template>
      </q-table>
<br>
      <q-table dense :filter="filter" title="HERRAMIENTAS" :data="tools" :columns="coltool" row-key="name" :rows-per-page-options="[0,50,100]">
        <template v-slot:top-right>
            <div class="q-pa-xs"> <q-btn label="Registro" color="positive" @click="tool={}; dialogTool = true" icon="add_circle"  class="q-mb-xs" /></div>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Buscar">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body-cell-opcion="props">
          <q-td key="opcion" :props="props">
            <q-btn dense round flat color="yellow" @click="editTool(props)" icon="edit" />
            <q-btn dense round flat color="red" @click="deleteTool(props)" icon="delete" />
          </q-td>
      </template>
      </q-table>

      <q-dialog v-model="dialogReg">
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="bg-green-14 text-white">
            <div class="text-h7"><q-icon name="add_circle" /> REGISTRO NUEVO</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
  
                   <div class="q-pa-xs "><q-input v-model="box.nombre" type="text" label="Nombre Herramienta" outlined dense /></div>
                   <div class="q-pa-xs "><q-toggle v-model="box.tipo" color="green" true-value="SI" false-value="NO" :label="box.tipo +' TIENE CODIGO'"/></div>


              <div>
                <q-btn label="Registrar" type="submit" color="positive" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>

      <q-dialog v-model="dialog_mod">
        <q-card style="max-width: 80%; width: 50%">
          <q-card-section class="bg-warning text-white">
            <div class="text-h7"> <q-icon name="edit"/> MODIFICAR DATOS </div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="onMod" class="q-gutter-md">
                  <div class="row">
                   <div class="q-pa-xs "><q-input v-model="box2.nombre" type="text" label="Nombre Herramienta" outlined dense /></div>
                   <div class="q-pa-xs "><q-toggle v-model="box2.tipo" color="green" true-value="SI" false-value="NO" :label="box2.tipo +' TIENE CODIGO'"/></div>

                </div>
              <div>
                <q-btn label="Modificar" type="submit" color="yellow" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
 
      <q-dialog v-model="dialogTool">
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="bg-green-14 text-white">
            <div class="text-h7"><q-icon name="add_circle" /> REGISTRO NUEVO</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="registro" @reset="onReset" class="q-gutter-md">
                    <div><q-select dense v-model="boxtool" :options="boxtools" label="Herramienta" filled /></div>
                    <div v-if="boxtool.id!=undefined" class="row">
                        <div class="q-pa-xs col-6"><q-input dense outlined v-model="tool.codigo" type="text" label="CODIGO" /></div>
                        <div class="q-pa-xs col-6" v-if="boxtool.tipo=='NO'"><q-input dense outlined v-model="tool.cantidad" type="number" label="CANTIDAD" /></div>
                        <div class="q-pa-xs col-12" v-if="boxtool.tipo=='SI'"><q-input dense outlined v-model="tool.observacion" type="text" label="OBSERVACION" /></div>
                    </div>
              <div>
                <q-btn label="Registrar" type="submit" color="positive" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>

      <q-dialog v-model="dialog_modtool">
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="bg-warning text-white">
            <div class="text-h7"><q-icon name="add_circle" /> MODIFICACION HERRAMIENTA</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="onModTool" @reset="onReset" class="q-gutter-md">
                    
                  <div class="q-pa-xs col-6"><q-input dense outlined v-model="tool2.codigo" type="text" label="CODIGO" /></div>
                  <div class="q-pa-xs col-12" ><q-input dense outlined v-model="tool2.observacion" type="text" label="OBSERVACION" /></div>
              <div>
                <q-btn label="Modificar" type="submit" color="yellow" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
      <div id="myelement" class="hidden"></div>

    </div>
  </template>

  <script>
  import { date } from 'quasar'
import xlsx from "json-as-xlsx"
import {Printd} from "printd";
import moment from 'moment';
  const { addToDate } = date
  export default {
    data() {
      return {
        dialogReg:false,
        dialogTool:false,
        box:{},
        tool:{},
        cEstado:'',
        jvDialog:false,
        dialog_mod: false,
        dialog_modtool: false,
        dialog_del: false,
        dialog_asig: false,
        dialog_fec: false,
        dialog_estado: false,
        filter:'',
        tipo:{},
        dato: {},
        boxtools:[],
        boxtool:{label:''},
        tool2:{},
        box2:{},
        jfilter:[],
        ini:date.formatDate(new Date(), "YYYY-01-01"),
        fin:date.formatDate(new Date(), "YYYY-12-31"),
        tools:[],
        model:'',
        dato2: {},
        options: [],
        props: [],
        modelpermiso:false,
        uni:{},
        columns: [
            {name: "opcion", label: "OPCION", field: "opcion", sortable: false },
            {name: "nombre", align: "left", label: "NOMBRE", field: 'nombre', sortable: true,},
            {name: "stock", align: "left", label: "STOCK", field: 'stock', sortable: true,},
            {name: "disponible", align: "left", label: "DISPONIBLE", field: 'disponible', sortable: true,},
            {name: "tipo", align: "left", label: "CODIGO", field: "tipo", sortable: true,},
        ],
        coltool: [
            {name: "opcion", label: "OPCION", field: "opcion", sortable: false },
            {name: "nombre", align: "left", label: "NOMBRE", field: 'nombre', sortable: true,},
            {name: "cantidad", align: "left", label: "cantidad", field: 'cantidad', sortable: true,},
            {name: "codigo", align: "left", label: "codigo", field: 'codigo', sortable: true,},
            {name: "saldo", align: "left", label: "saldo", field: 'saldo', sortable: true,},
            {name: "estado", align: "left", label: "ESTADO", field: "estado", sortable: true,},
        ],
        data: [],

      }

    },
    created() {
      // if (!this.$store.state.login.boolusuario){
      //    this.$router.replace({ path: '/' })
      // }

      this.buscar()
      this.getTools()

    },
    methods: {


      buscar() {
        this.$q.loading.show();
        this.boxtools=[]
        this.$axios.get("boxtool").then((res) => {
          res.data.forEach(r => {
            r.label=r.nombre
            this.boxtools.push(r)
          })
          this.$q.loading.hide()
        });
      },

      getTools() {
        this.$q.loading.show();
        this.$axios.get("tool").then((res) => {
 
          this.tools=res.data
          this.$q.loading.hide()
        });
      },

      editRow(item) {
        this.box2=item.row
        this.dialog_mod = true;
      },

      editTool(tool) {
        this.tool2=tool.row
        this.boxtool=this.tool2.boxtool
        this.boxtool.label=this.boxtool.nombre
        this.dialog_modtool = true;
      },

      deleteRow(item) {
        this.$q.dialog({
        title: 'SEGURO',
        message: 'Desea Eliminar registro?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.$q.loading.show();
        this.$axios.delete("boxtool/" + item.row.id)
          .then((res) => {
            this.$q.notify({
              color: "green-4",
              textColor: "white",
              icon: "cloud_done",
              message: "Eliminado correctamente",
            });
            this.buscar()
          this.$q.loading.hide()
        })
      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })

      },
      deleteTool(tool) {
        this.$q.dialog({
        title: 'SEGURO',
        message: 'Desea Eliminar registro?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.$q.loading.show();
        this.$axios.delete("tool/" + tool)
          .then((res) => {
            this.$q.notify({
              color: "green-4",
              textColor: "white",
              icon: "cloud_done",
              message: "Eliminado correctamente",
            });
            this.tool()
          this.$q.loading.hide()
        })
      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })

      },
      onSubmit() {
        this.$q.loading.show();
        this.$axios.post("boxtool", this.box).then((res) => {
          // console.log(res.data)
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Registrado correctamente",
          });
          this.box={tipo:'NO'}
          this.dialogReg = false;
          this.buscar();
          this.$q.loading.hide()
        })
      },
      registro(){
        this.$q.loading.show();
        this.tool.boxtool_id=this.boxtool.id
        this.$axios.post("tool", this.tool).then((res) => {
          // console.log(res.data)
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Registrado correctamente",
          });
          this.tool={}
          this.dialogTool = false
          this.buscar()
          this.getTools()
          this.$q.loading.hide()
        })
      },
      onMod() {
        this.$q.loading.show();
        this.$axios.put("boxtool/" + this.box2.id, this.box2).then((res) => {
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Modificado correctamente",
          });
          this.dialog_mod = false;
          this.buscar();
        });
      },
      onModTool() {
        this.$q.loading.show();
        this.$axios.put("tool/" + this.tool2.id, this.tool2).then((res) => {
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Modificado correctamente",
          });
          this.dialog_modtool = false;
          this.buscar();
        });
      },
      onDel() {

      },
      onReset() {
        this.dato={};
      },
    },
  };
  </script>
