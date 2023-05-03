<template>
    <div class="q-pa-md">
      <q-btn
        label="Registro"
        color="positive"
        @click="alert = true"
        icon="add_circle"
        class="q-mb-xs"
      />
      <q-btn
        label="Registrar Junta V"
        color="accent"
        @click="junta2={}; jvDialog = true"
        icon="add_circle"
        class="q-mb-xs"
      />

      <q-dialog v-model="jvDialog">
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="bg-green-14 text-white">
            <div class="text-h7"><q-icon name="add_circle" /> REGISTRO DE Junta Vecinal</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="regJV" @reset="onReset" class="q-gutter-md">
                  <q-input outlined v-model="junta2.nombre" type="text" label="Nombre " hint="Ingresar nombre" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" />

                  <q-select v-model="junta2.distrito" :options="['D1','D2','D3','D4','D5','D6']" label="Distrito" outlined />
                  <q-select v-model="junta2.zona" :options="['ZONA NORTE','ZONA ESTE','ZONA CENTRAL','ZONA OESTE','ZONA SUD','ZONA NOR ESTE','ZONA NOR OESTE','ZONA SUD ESTE','ZONA SUD OESTE']"  label="Zona" outlined />

                  <q-input outlined v-model="junta2.representante" type="text" label="Representante" hint="nombre completo"  />

                  <q-input outlined v-model="junta2.celular" label="Celular" hint="numero de contacto"  />

              <div>
                <q-btn label="Crear" type="submit" color="positive" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>

      <q-dialog v-model="alert">
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="bg-green-14 text-white">
            <div class="text-h7"><q-icon name="add_circle" /> REGISTRO NUEVO</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
                <div class="row">
                    <div class="col-6"><q-select use-input input-debounce="0"  @filter="filterFn" v-model="junta" :options="juntas" label="Junta Vecinal" outlined >
                        <template v-slot:no-option>
                                <q-item>
                          <q-item-section class="text-grey">
                            No results
                          </q-item-section>
                        </q-item>
                      </template>
                    </q-select>
                  </div>
                    <div class="col-6"><q-input outlined v-model="junta.distrito" label="Distrito"  /></div>


                    <div class="col-6"><q-input outlined v-model="junta.celular" type="text"  label="Celular"  /></div>
                    <div class="col-6"><q-input outlined v-model="junta.zona"  label="Zona" /></div>

                    <div class="col-6"><q-input outlined v-model="dato.fecha" label="Fecha" type="date" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" /></div>
                    <div class="col-6"><q-select v-model="tipo" :options="lista" label="Actividad" outlined required/></div>
                    <div class="col-6"><q-input outlined v-model="dato.cantidad" label="Cantidad" type='number' lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" /></div>
                    <div class="col-6"><q-input outlined v-model="dato.descripcion" label="Descripcion"  /></div>

                </div>
              <div>
                <q-btn label="Crear" type="submit" color="positive" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
        <div class="row">
            <div class="col-3"><q-input dense outlined v-model="ini" type="date" label="Fecha Ini" /></div>
            <div class="col-3"><q-input dense outlined v-model="fin" type="date" label="Fecha Fin" /></div>
            <div class="col-2"><q-btn dense color="green" icon="search" label="Buscar" @click="Buscar" /></div>
            <div class="col-2"><q-btn dense color="info" icon="file_download" label="Excel Report" @click="impresion" /></div>
            <div class="col-2"><q-btn dense color="accent" icon="file_download" label="Excel Plan" @click="exportExcel" /></div>
        </div>
      <q-table :filter="filter" title="LISTA Cronograma" :data="data" :columns="columns" row-key="name" :rows-per-page-options="[50,100]">
        <template v-slot:top-right>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body-cell-opcion="props">
            <q-td key="opcion" :props="props">
              <q-btn dense round flat color="yellow"
                @click="editRow(props)"
                icon="edit"
              />

              <q-btn
                dense
                round
                flat
                color="red"
                @click="deleteRow(props)"
                icon="delete"
              ></q-btn>
            </q-td>

        </template>
      </q-table>

      <q-dialog v-model="dialog_mod">
        <q-card style="max-width: 80%; width: 50%">
          <q-card-section class="bg-warning text-white">
            <div class="text-h7"> <q-icon name="edit"/> MODIFICAR DATOS </div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="onMod" class="q-gutter-md">
                <div class="row">
                  <div class="col-6"><q-select use-input input-debounce="0"  @filter="filterFn" v-model="junta" :options="juntas" label="Junta Vecinal" outlined >
                        <template v-slot:no-option>
                                <q-item>
                          <q-item-section class="text-grey">
                            No results
                          </q-item-section>
                        </q-item>
                      </template>
                    </q-select>
                  </div>
                    <div class="col-6"><q-input outlined v-model="junta.distrito" label="Distrito"  /></div>


                    <div class="col-6"><q-input outlined v-model="junta.celular" type="text"  label="Celular"  /></div>
                    <div class="col-6"><q-input outlined v-model="junta.zona"  label="Zona" /></div>

                    <div class="col-6"><q-input outlined v-model="dato2.fecha" label="Fecha" type="date" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" /></div>
                    <div class="col-6"><q-select v-model="tipo" :options="lista" label="Actividad" outlined required/></div>
                    <div class="col-6"><q-input outlined v-model="dato2.cantidad" label="Cantidad" type='number' lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" /></div>
                    <div class="col-6"><q-input outlined v-model="dato2.descripcion" label="Descripcion"  /></div>
                </div>
              <div>
                <q-btn label="Modificar" type="submit" color="positive" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>


      <q-dialog v-model="dialog_del">
        <q-card>
          <q-card-section class="row items-center">
            <q-avatar icon="clear" color="red" text-color="white" />
            <span class="q-ml-sm">Seguro de eliminar Registro.</span>
          </q-card-section>

          <q-card-actions align="right">
            <q-btn flat label="Eliminar" color="deep-orange" @click="onDel" />
            <q-btn flat label="Cancelar" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>


    </div>
  </template>

  <script>
  import { date } from 'quasar'
import xlsx from "json-as-xlsx"

  const { addToDate } = date
  export default {
    data() {
      return {
        alert: false,
        junta2:{},
        jvDialog:false,
        dialog_mod: false,
        dialog_del: false,
        filter:'',
        tipo:{},
        dato: {},
        juntas:[],
        jfilter:[],
        junta:{},
        ini:date.formatDate(new Date(), "YYYY-MM-DD"),
        fin:date.formatDate(new Date(), "YYYY-MM-DD"),
        lista:[
            {label:'Mantenimiento',codigo:'160002',actividad:'PROVISION DE MATERIAL ELECTRICO (MANTENIMIENTO Y EQUIPO DE ALUMBRADO)', tipo:'Mantenimiento'},
            {label:'Instalaciones Nuevas',codigo:'160006',actividad:'MI ALUMBRADO PUBLICO VECINAL', tipo:'Instalaciones nuevas'},
            {label:'Instalacion de Postes',codigo:'160005',actividad:'RENOVACION DE POSTES DE ILUMINACION PUBLICA', tipo:'Instalacion de Postes'},
        ],
        model:'',
        dato2: {},
        options: [],
        props: [],
        modelpermiso:false,
        uni:{},
        columns: [
            {name: "codigo", align: "left", label: "Codigo ", field: "codigo", sortable: true,},
            {name: "actividad", align: "left", label: "actividad ", field: "actividad", sortable: true,},
            {name: "distrito", align: "left", label: "distrito ", field: row=>row.junta.distrito, sortable: true,},
            {name: "junta", align: "left", label: "junta ", field: row=>row.junta.nombre, sortable: true,},
            {name: "zona", align: "left", label: "zona ", field: row=>row.junta.zona, sortable: true,},
            {name: "tipo", align: "left", label: "tipo ", field: "tipo", sortable: true,},
            {name: "cantidad", align: "left", label: "cantidad ", field: "cantidad", sortable: true,},
            {name: "fecha", align: "left", label: "fecha ", field: "fecha", sortable: true,},
            {name: "celular", align: "left", label: "celular ", field: "celular", sortable: true,},
            {name: "opcion", label: "OPCIÓN", field: "opcion", sortable: false },
        ],
        data: [],

      }

    },
    created() {
      // if (!this.$store.state.login.boolusuario){
      //    this.$router.replace({ path: '/' })
      // }

      this.Buscar();
      this.listJunta();
        this.tipo=this.lista[0]
    },
    methods: {
      regJV() {
        this.$q.loading.show();
        this.$axios.post( "junta", this.junta2).then((res) => {
          // console.log(res.data)
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "JV Agregado",
          });
          this.junta2={}
          this.jvDialog = false;
          this.listJunta();
        }).catch(err=>{
          console.log(err.response.data);
          this.$q.notify({
            message:err.response.data.message,
            icon:'close',
            color:'red'
          })
          this.$q.loading.hide()
        })
      },
      filterFn (val, update) {
        if (val === '') {
          update(() => {
            this.juntas = this.jfilter
          })
          return
        }

        update(() => {
          const needle = val.toLowerCase()
          this.juntas = this.jfilter.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
        })
      },

      Buscar() {
        this.$q.loading.show();
        this.$axios.post("listCrono",{ini:this.ini,fin:this.fin}).then((res) => {
          console.log(res.data)
          this.data = res.data;
          this.$q.loading.hide();
        });
      },

      listJunta() {
        this.juntas=[]
        this.$q.loading.show();
        this.$axios.get("junta").then((res) => {
          //console.log(res.data)
          res.data.forEach(r => {
            r.label=r.nombre+' ' +r.distrito
            this.juntas.push(r)
          });
          this.$q.loading.hide();
          this.jfilter=this.juntas
          this.junta={label:''}
        });
      },

  impresion() {
        this.$q.loading.show();
        this.$axios.post("datoImp",{ini:this.ini,fin:this.fin}).then((res) => {
        let dataimp = [
  {
    sheet: "Cronograma",
    columns: [
      { label: "CAT PROD", value: "codigo" }, // Top level data
      { label: "NOMBRE DE LA ACTIVIDAD", value: "actividad" }, // Top level data
      { label: "DISTRITO", value: "distrito" }, // Top level data
      { label: "BENEFICIARIO", value: "junta" }, // Top level data
      { label: "UBICACION", value: "zona" }, // Top level data
      { label: "SUPERVISOR", value: "" }, // Top level data
      { label: "AVANCE FISICO DEL PERIODO", value: "valor1" }, // Top level data
      { label: "AVANCE FISICO ACUMULADO", value: "valor2" }, // Top level data
      { label: "CANTIDAD", value: "total" }, // Top level data
      { label: "UNIDAD", value: "unidad" }, // Top level data
      { label: "DESCRIPCION", value: "tipo" }, // Top level data
      { label: "OBSERVACION", value: "detalle" }, // Top level data
    ],
    content: res.data
  },

]

let settings = {
  fileName: "Cronograma", // Name of the resulting spreadsheet
  extraLength: 6, // A bigger number means that columns will be wider
  writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
}

xlsx(dataimp, settings) // Will download the excel file

          console.log(res.data)
          this.$q.loading.hide();
        });
      },
      exportExcel() {
        this.$q.loading.show();
        let dataimp = [
  {
    sheet: "Cronograma",
    columns: [
      { label: "DISTRITO", value: row=>row.junta.distrito }, // Top level data
      { label: "BENEFICIARIO", value: row=>row.junta.nombre }, // Top level data
      { label: "CELULAR", value: row=>row.junta.celular }, // Top level data
      { label: "UBICACION", value: row=>row.junta.zona }, // Top level data
      { label: "CANTIDAD", value: "cantidad" }, // Top level data
      { label: "FECHA", value: "fecha" }, // Top level data
      { label: "DESCRIPCION", value: "tipo" }, // Top level data
      { label: "OBSERVACION", value: "descripcion" }, // Top level data
    ],
    content: this.data
  },

]

let settings = {
  fileName: "Planificacion", // Name of the resulting spreadsheet
  extraLength: 6, // A bigger number means that columns will be wider
  writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
}

xlsx(dataimp, settings) // Will download the excel file

          this.$q.loading.hide();
},
      editRow(item) {
        this.dato2 = item.row
        this.junta=this.dato2.junta
        this.junta.label = this.junta.nombre +' ' +this.junta.distrito
        this.tipo=this.lista.find(t=>{return t.tipo==this.dato2.tipo})
        this.dialog_mod = true;
      },
      deleteRow(item) {
        this.dato2 = item.row;
        this.dialog_del = true;
      },
      onSubmit() {
        this.$q.loading.show();
        this.dato.codigo=this.tipo.codigo
        this.dato.actividad=this.tipo.actividad
        this.dato.tipo=this.tipo.tipo
        this.dato.junta_id=this.junta.id
        this.dato.celular=this.junta.celular
        this.$axios.post( "cronograma", this.dato).then((res) => {
          // console.log(res.data)
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Creado correctamente",
          });
          this.dato={}
          this.alert = false;
          this.Buscar();
          this.$q.loading.hide()
        })
      },
      onMod() {
        this.$q.loading.show();
        this.dato2.codigo=this.tipo.codigo
        this.dato2.actividad=this.tipo.actividad
        this.dato2.tipo=this.tipo.tipo
        this.$axios.put("cronograma/" + this.dato2.id, this.dato2).then((res) => {
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Modificado correctamente",
          });
          this.dialog_mod = false;
          this.Buscar();
        });
      },
      onDel() {
        this.$q.loading.show();
        this.$axios.delete("cronograma/" + this.dato2.id)
          .then((res) => {
            this.$q.notify({
              color: "green-4",
              textColor: "white",
              icon: "cloud_done",
              message: "Eliminado correctamente",
            });
            this.dialog_del = false;
            this.buscar();
          this.$q.loading.hide()
        })
      },
      onReset() {
        this.dato={};
      },
    },
  };
  </script>
