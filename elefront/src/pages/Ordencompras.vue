<template>
    <div class="q-pa-md">
      <q-btn
        label="Registro Orden Compra"
        color="positive"
        @click="alert = true"
        icon="add_circle"
        class="q-mb-xs"
      />

      <q-dialog v-model="alert">
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="bg-green-14 text-white">
            <div class="text-h7"><q-icon name="add_circle" /> REGISTRO ORDEN COMPRA</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="registrar" @reset="onReset" class="q-gutter-md">
                        <div class="q-pd-md" v-if="mensaje!=''" style="color:red">{{mensaje}}</div>
                <div class="row">
                    <div class="col-6">
                        <q-input dense outlined v-model="dato.norden"  label="Nro Orden Compra" required />
                    </div>
                    <div class="col-6">
                        <q-input dense outlined v-model="dato.fecha" type="date" label="Fecha" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa Fecha']" required/>
                    </div>

                    <div class="col-12">
                        <q-input dense outlined v-model="dato.responsable" label="Responsable" lazy-rules required list="respon"/>
                        <datalist id="respon">
                          <option v-for="r in responsables" :key="r.responsable">{{r.responsable}}</option>
                        </datalist>
                    </div>
                    <div class="col-6"><q-select use-input input-debounce="0" @filter="filterTienda" dense v-model="tienda" :options="tiendas" label="Tienda/Proveedor" outlined  /></div>
                    <div class="col-6"><b>REPRESENTANTE: </b> {{ tienda.representante }}<br><b>DIRECCION: </b> {{ tienda.direccion }}<br><b>TELEFONO: </b> {{ tienda.telefono }}</div>

                    <div class="col-2"><q-input dense outlined v-model="det.unidad" label="Unidad" lazy-rules  list="unidades"/>
                        <datalist id="unidades">
                          <option v-for="u in unidades" :key="u.unidad">{{u.unidad}}</option>
                        </datalist>
                    </div>
                    <div class="col-6"><q-input dense outlined v-model="det.material" label="Material" lazy-rules  list="materiales"/>
                        <datalist id="materiales">
                          <option v-for="m in materiales" :key="m.material">{{m.material}}</option>
                        </datalist>
                    </div>
                    <div class="col-3"><q-input dense v-model="det.cantidad" type="number" label="Cantidad" outlined /></div>
                    <div class="col-1"><q-btn dense color="green" icon="control_point" @click="agregarMaterial" /></div>
                  </div>

                <div class="col-12">
                    <q-table
                        title="Lista de Compra "
                        :data="detalle"
                        :columns="columna2"
                        row-key="name"
                    >
                    <template v-slot:body-cell-opcion="props">
                        <q-td key="opcion" :props="props">
                            <q-btn  dense color="red" icon="delete" @click="eliminar(props)"    />
                        </q-td>
                    </template>

                    </q-table>

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
        <div class="col-4 q-pa-xs"><q-input v-model="ini" type="date" label="Fecha Ini" dense outlined/></div>
        <div class="col-4 q-pa-xs"><q-input v-model="fin" type="date" label="Fecha Fin" dense outlined/></div>
      </div>
      <q-table :filter="filter" title="LISTA ORDEN COMPRAS" :data="compras" :columns="columns" row-key="name" :rows-per-page-options="[50,100]">
        <template v-slot:top-right>
          <div class="q-pa-xs"><q-btn color="info" icon="search" label=" Buscar" @click="misdatos" /></div>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body-cell-opcion="props">
            <q-td key="opcion" :props="props">
              <!--<q-btn dense round flat color="yellow"  @click="editDato(props.row)"  icon="edit" />-->
              <q-btn dense round flat color="accent"  @click="viewRow(props.row)"  icon="local_mall" />
            </q-td>
        </template>
      </q-table>
<br>
      <q-table :filter="filter" title="LISTA MATERIALES" :data="tabDetalle" :columns="colDetalle" row-key="name" :rows-per-page-options="[50,100]">
        <template v-slot:top-right>
          <div class="q-pa-xs"><q-btn color="info" icon="search" label=" Buscar" @click="getDetalle" /></div>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body-cell-opcion="props">
            <q-td key="opcion" :props="props">
              <q-btn dense round flat color="accent"  @click="ingresoDetalle(props.row)"  icon="shopping_cart" v-if="props.row.cantidad>props.row.entregado"/>
              <q-btn dense round flat color="cyan"  @click="logVer(props.row)"  icon="edit_note" v-if="props.row.logdetalle.length>0"/>
            </q-td>
        </template>
        <template v-slot:body-cell-estado="props">
            <q-td key="estado" :props="props">
              <q-badge :color="props.row.estado=='COMPLETADO'?'green':'red'"  :label="props.row.estado" />
            </q-td>
        </template>
      </q-table>

      <q-dialog v-model="dialogMod">
        <q-card>
          <q-card-section class="row items-center">
            <q-avatar icon="shopping_cart" color="red" text-color="white" />
            <span class="q-ml-sm">DETALLE DE COMPRA MODIFICAR</span>
          </q-card-section>
          <q-card-section>
            <div class="row">
              <div class="col-6"><q-input outlined v-model="compra.nrocompra" type="number" label="Nro Compra" /></div>
              <div class="col-6"><q-input outlined v-model="compra.fechacompra" type="date" label="Fecha Compra" /></div>
              <div class="col-6"><q-input outlined v-model="compra.nrovale" type="number" label="Nro Vale" /></div>
            </div>

          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="Modificar" color="yellow" @click="modificar" />
            <q-btn flat label="Cancelar" color="primary" v-close-popup />
          </q-card-actions>
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

      <q-dialog v-model="dialogDetalle">
        <q-card >
          <q-card-section class="row items-center">
            <q-avatar icon="shopping_cart" color="red" text-color="white" />
            <span class="q-ml-sm">DETALLE DE COMPRA</span>
          </q-card-section>
          <q-card-section>
            <table>
              <tr><th>UNIDAD</th><th>MATERIAL</th><th>CANTIDAD</th></tr>
              <tr v-for="d in detalle" :key="d.id">
                <td>{{ d.unidad }}</td><td>{{ d.material }}</td><td>{{ d.cantidad }}</td>
              </tr>
            </table>
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="Cancelar" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>

      <q-dialog v-model="dialog_ingreso">
        <q-card>
          <q-card-section class="row items-center">
            <q-avatar icon="shopping_cart" color="red" text-color="white" />
            <span class="q-ml-sm">RECOGER MATERIAL</span>
          </q-card-section>
            <q-form
              @submit="regIngreso"
              @reset="onReset"
              class="q-gutter-md"
            >
          <q-card-section>
            <div class="row">
              <div class="col-6 q-pa-xs"><q-input dense outlined v-model="ingreso.cantidad" type="number" label="Cantidad" required/></div>
              <div class="col-6 q-pa-xs"><q-input dense outlined v-model="ingreso.vale"  label="Nro Vale" /></div>
              <div class="col-12 q-pa-xs"><q-input dense outlined v-model="ingreso.obs"  label="Observacion" /></div>
            </div>
              <div>
              </div>
          </q-card-section>
          <q-card-actions align="right">
              <q-btn flat   label="Registrar" type="submit" color="green"/>
             <q-btn flat label="Cancelar" color="primary" v-close-popup />
          </q-card-actions>
            </q-form>
        </q-card>
      </q-dialog>

      <q-dialog v-model="dialog_ver" >
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="row items-center bg-primary text-white">
            <span class="q-ml-sm text-weight-bold">HISTORIAL DE INGRESO A ALMACEN</span>
          </q-card-section>
          <q-card-section>
            <q-table
              :data="retiros"
              :columns="colIng"
              row-key="name"
              dense
            />
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="Cancel" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </div>
  </template>

  <script>
  import { Console } from 'console';
import { date } from 'quasar'
  const { addToDate } = date
  export default {
    data() {
      return {
        ini:date.formatDate(Date.now(),'YYYY-01-01'),
        fin:date.formatDate(Date.now(),'YYYY-12-31'),
        alert: false,
        det:{'cantidad':1},
        detalle:[],
        materiales:[],
        responsables:[],
        unidades:[],
        dialog_mod: false,
        dialog_del: false,
        dialogDetalle:false,
        dialogMod:false,
        dialog_ingreso:false,
        dialog_ver:false,
        filter:'',
        dato: {'fecha':date.formatDate(Date.now(),'YYYY-MM-DD')},
        model:'',
        dato2: {},
        props: [],
        material:{},
        tienda:{},
        compra:{},
        tiendas:[],
        filtertiendas:[],
        compras:[],
        modelpermiso:false,
        mensaje:'',
        ingreso:{},
        uni:{},
        tabDetalle:[],
        calDetalle:{},
        columns: [
        { name: "opcion", label: "OPCIÓN", field: "opcion", sortable: false },
        {name: "norden", align: "left", label: "NRO ORDEN COMPRA ", field: "norden", sortable: true,},
        {name: "fecha", align: "left", label: "FECHA ORDEN", field: "fecha", sortable: true,},
        {name: "tienda", align: "left", label: "TIENDA/PROV", field: row=>row.tienda.nombre, sortable: true,},
        {name: "responsable", align: "left", label: "RESPONSABLE", field: 'responsable', sortable: true,},
          ],
        columna2:[
            {name:'opcion',label:'opcion',field:'opcion'},
            {name:'cantidad',label:'CANTIDAD',field:'cantidad'},
            {name:'unidad',label:'UNIDAD',field:'unidad'},
            {name:'material',label:'MATERIAL',field:'material'},
        ],
        colDetalle:[
            {name:'opcion',label:'opcion',field:'opcion'},
            {name:'cantidad',label:'CANTIDAD',field:'cantidad', sortable: true},
            {name:'entregado',label:'ENTREGADO',field:'entregado'},
            {name:'unidad',label:'UNIDAD',field:'unidad', sortable: true},
            {name:'material',label:'MATERIAL',field:'material' , sortable: true},
            {name:'estado',label:'ESTADO',field:'estado'},
            {name: "norden", align: "left", label: "NRO ORDEN COMPRA ", field: row=>row.ordencompra.norden, sortable: true},
            {name: "fecha", align: "left", label: "FECHA ORDEN", field: row=>row.ordencompra.fecha, sortable: true,},
        ],
        colIng:[
          {name:'fecha',label:'FECHA',field:'fecha',align:'center'},
          {name:'cantidad',label:'CANTIDAD',field:'cantidad',align:'center'},
          {name:'vale',label:'N VALE',field:'vale',align:'center'},
          {name:'obs',label:'OBSERVACION',field:'obs',align:'left'},
        ],
        data: [],
        retiros:[]
      };
    },
    created() {
      // if (!this.$store.state.login.boolusuario){
      //    this.$router.replace({ path: '/' })
      // }

      //this.misdatos();
      this.getUnid()
      this.getResp()
      this.getMat()
      this.mistiendas()
      this.misdatos()
      this.getDetalle()

    },
    methods: {
      logVer(log){
        this.retiros=log.logdetalle
        this.dialog_ver=true
      },
      regIngreso(){
        if(this.ingreso.cantidad <= 0){
              this.$q.notify({
              icon:'info',
              message: 'Debe Ingresar Cantidad ',
              color: 'red'
            })
            return false
        }
        if(this.ingreso.cantidad > (parseInt(this.calDetalle.cantidad) - parseInt(this.calDetalle.entregado))){
              this.$q.notify({
              icon:'info',
              message: 'No debe exeder la cantidad',
              color: 'red'
            })
            return false
        }

        this.ingreso.ordendetalle_id=this.calDetalle.id
        this.$axios.post("ordendetalle",this.ingreso).then((res) => {
          this.$q.notify({
              icon:'info',
              message: 'Registrado',
              color: 'green'
            })
            this.dialog_ingreso=false
            this.getDetalle()
        })

      },
      ingresoDetalle(det){
        console.log(det)
        this.calDetalle=det
        this.ingreso={cantidad:1}
        this.dialog_ingreso=true
      },
      getDetalle(){
        this.$axios.post("listDetalle",{'ini':this.ini,'fin':this.fin}).then((res) => {
          console.log(res.data)
          this.tabDetalle=res.data
        })

      },
      getMat(){
        this.$axios.post("listMaterial").then((res) => {
          this.materiales=res.data
        })
      },
      getResp(){
        this.$axios.post("listResp").then((res) => {
          this.responsables=res.data
        })
      },
      getUnid(){
        this.$axios.post("listUnid").then((res) => {
          this.unidades=res.data
        })
      },
      editDato(r){
        this.compra=r
        this.dialogMod=true
      },
      modificar(){
        this.$axios.put("compra/"+this.compra.id,this.compra).then((res) => {
          this.compra={}
          this.dialogMod=false
          this.misdatos()
        })

      },
      filterTienda (val, update) {
        if (val === '') {
          update(() => {
            this.tiendas = this.filtertiendas

            // here you have access to "ref" which
            // is the Vue reference of the QSelect
          })
          return
        }

        update(() => {
          const needle = val.toLowerCase()
          this.tiendas = this.filtertiendas.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
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
        validarCompra(){
            this.$axios.get("valCompra/"+this.dato.nrocompra).then((res) => {
                if(res.data.length>0){
                    this.mensaje='La compra ya existe'
                }
                else{
                    this.mensaje=''
                }
            })
        },
        viewRow(comp){
            //console.log(comp.contenidos)
            this.detalle=comp.ordendetalle
            this.dialogDetalle=true
        },
        registrar(){
            if(this.detalle.length==0){
              this.$q.notify({
              icon:'info',
              message: 'Debe Ingresar material ',
              color: 'red'
            })
            return false
            }
            if(this.tienda.id==undefined){
              this.$q.notify({
          icon:'info',
          message: 'Debe Seleccionar Proveedor',
          color: 'red'
        })
              return false
            }
            if(this.dato.fecha=='' || this.dato.fecha==undefined){
              this.$q.notify({
          icon:'info',
          message: 'Debe ingresar fecha',
          color: 'red'
        })
              return false
            }
          this.$q.loading.show();
            this.dato.detalle=this.detalle
            this.dato.tienda_id=this.tienda.id

            this.$axios.post("ordencompra",this.dato).then((res) => {
                console.log(res.data)
                this.misdatos()
                //return false
                this.$q.notify({
            color: "green-4",
            icon: "check",
            message: "Creado correctamente",
          })
          this.alert=false
          this.dato={'fecha':date.formatDate(Date.now(),'YYYY-MM-DD')}
          this.detalle=[]
          this.getMat()
          this.getResp()
          this.getUnid()
          this.$q.loading.hide()
        })

        },
        eliminar(list){
            console.log(list)
            this.detalle.splice(list.rowIndex, 1);

        },
        agregarMaterial(){
            console.log(this.listado)
            if(this.det.cantidad==undefined || this.det.cantidad<=0 )
            {
            console.log('error2 ing')
            return false
            }
            if(this.det.unidad==undefined || this.det.unidad=='' )
            {
            console.log('error2 ing')
            return false
            }
            if(this.det.material==undefined || this.det.material=='' )
            {
            console.log('error2 ing')
            return false
            }


            this.detalle.push(this.det)
            this.det={'cantidad':1}
        },

      mistiendas() {
        this.$q.loading.show();
        this.$axios.get("tienda").then((res) => {
          console.log(res.data)
          res.data.forEach(r => {
            r.label=r.nombre
          });
          this.tiendas = res.data;
          this.filtertiendas=this.tiendas
          this.$q.loading.hide();
          this.tienda={label:''}
        });
      },
      misdatos() {
        this.$q.loading.show();
        this.$axios.post("listOrden",{ini:this.ini,fin:this.fin}).then((res) => {
          console.log(res.data)
          this.compras = res.data;
          this.$q.loading.hide();
        });
      },
      editRow(item) {
        this.dato2 = item.row
        this.dialog_mod = true;
      },
      deleteRow(item) {
        this.dato2 = item.row;
        this.dialog_del = true;
      },
      onSubmit() {
        this.$q.loading.show();
        this.$axios.post( "material", this.dato).then((res) => {
          // console.log(res.data)
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Creado correctamente",
          });
          this.dato={}
          this.alert = false;
          this.misdatos();
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

      onDel() {
        this.$q.loading.show();
        this.$axios.delete("compra/" + this.dato2.id)
          .then((res) => {
            this.$q.notify({
              color: "green-4",
              textColor: "white",
              icon: "cloud_done",
              message: "Eliminado correctamente",
            });
            this.dialog_del = false;
            this.misdatos();
          }).catch(err=>{
          this.$q.loading.hide()
          this.$q.notify({
            message:err.response.data.message,
            icon:'error',
            color:'red'
          })
        })
      },
      onReset() {
        this.dato= {};
      },
    },
  };
  </script>
