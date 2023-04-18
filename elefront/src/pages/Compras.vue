<template>
    <div class="q-pa-md">
      <q-btn
        label="Registro de Compra"
        color="positive"
        @click="alert = true"
        icon="add_circle"
        class="q-mb-xs"
      />

      <q-dialog v-model="alert">
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="bg-green-14 text-white">
            <div class="text-h7"><q-icon name="add_circle" /> REGISTRO COMPRA MATERIAL</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="registrar" @reset="onReset" class="q-gutter-md">
                        <div class="q-pd-md" v-if="mensaje!=''" style="color:red">{{mensaje}}</div>
                <div class="row">
                    <div class="col-4">
                        <q-input dense outlined v-model="dato.nrocompra" type="number" label="Nro Compra" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" required @keyup="validarCompra"/>
                    </div>
                    <div class="col-4">
                        <q-input dense outlined v-model="dato.fecha" type="date" label="Fecha" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" required/>
                    </div>
                    <div class="col-4"><q-select  dense v-model="tienda" :options="tiendas" label="Tienda/Proveedor" outlined required /></div>
                </div>
                <div class="row q-md-pd">
                    <div class="col-4"><q-select dense v-model="reg.material" :options="materiales" label="Material" outlined /></div>
                    <div class="col-3"><q-input dense v-model="reg.cantidad" type="number" label="Cantidad" outlined/></div>
                    <div class="col-3"><q-input dense v-model="reg.unitario" type="number" step="0.01" label="P Unitario" outlined/></div>
                    <div class="col-2"><q-btn dense color="green" icon="control_point" @click="agregarMaterial" /></div>

                </div>
                <div class="col-12">
                    <q-table
                        title="Lista de Compra "
                        :data="listado"
                        :columns="columna2"
                        row-key="name"
                    >
                    <template v-slot:body-cell-opcion="props">
                        <q-td key="opcion" :props="props">
                            <q-btn  dense color="red" icon="delete" @click="eliminar(props)"    />
                        </q-td>
                    </template>
                    <template v-slot:body-cell-unidad="props">
                      <q-td key="unidad" :props="props">
                          {{props.row.material.unidad }}
                      </q-td>
                  </template>
                  <template v-slot:body-cell-material="props">
                    <q-td key="material" :props="props">
                        {{props.row.material.nombre }}
                    </q-td>
                </template>
                <template v-slot:body-cell-codificar="props">
                    <q-td key="codificar" :props="props">
                        {{props.row.material.codificar }}
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

      <q-table :filter="filter" title="LISTA DE COMPRAS" :data="compras" :columns="columns" row-key="name" :rows-per-page-options="[50,100]">
        <template v-slot:top-right>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body-cell-opcion="props">
            <q-td key="opcion" :props="props">
              <q-btn dense round flat color="accent"
                @click="viewRow(props.row)"
                icon="local_mall"
              />

              <q-btn dense round flat color="red" @click="deleteRow(props)" icon="delete" ></q-btn>
            </q-td>

        </template>
      </q-table>



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
        <q-card>
          <q-card-section class="row items-center">
            <q-avatar icon="shopping_cart" color="red" text-color="white" />
            <span class="q-ml-sm">DETALLE DE COMPRA</span>
          </q-card-section>
          <q-card-section>
            <table>
              <tr><th>MATERIAL</th><th>CANTIDAD</th><th>PRECIO</th><th>SUBTOTAL</th></tr>
              <tr v-for="d in detalle" :key="d.id">
              <td>{{ d.material.nombre }}</td><td>{{ d.cantidad }}</td><td>{{ d.unitario }}</td><td>{{ d.total }}</td>
              </tr>
            </table>
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="Cancelar" color="primary" v-close-popup />
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
        alert: false,
        dialog_mod: false,
        dialog_del: false,
        dialogDetalle:false,
        listado:[],
        filter:'',
        dato: {},
        model:'',
        dato2: {},
        options: [],
        props: [],
        material:{},
        tienda:{},
        materiales:[],
        tiendas:[],
        compras:[],
        detalle:[],
        reg:{material:{label:''}},
        modelpermiso:false,
        mensaje:'',
        uni:{},
        columns: [
        {name: "codigo", align: "left", label: "NRO COMPRA ", field: "nrocompra", sortable: true,},
        {name: "fecha", align: "left", label: "FECHA", field: "fecha", sortable: true,},
        {name: "gestion", align: "left", label: "gestion", field: "gestion", sortable: true,},
        {name: "tienda", align: "left", label: "TIENDA/PROV", field: row=>row.tienda.nombre, sortable: true,},
        { name: "opcion", label: "OPCIÓN", field: "opcion", sortable: false },
          ],
        columna2:[
            {name:'opcion',label:'opcion',field:'opcion'},
            {name:'cantidad',label:'CANTIDAD',field:'cantidad'},
            {name:'unidad',label:'UNIDAD',field:'unidad'},
            {name:'material',label:'MATERIAL',field:'material'},
            {name:'unitario',label:'P UNIT',field:'unitario'},
            {name:'codificar',label:'Cod',field:'codificar'},
            {name:'total',label:'TOTAL',field:'total'},
        ],
        data: [],
      };
    },
    created() {
      // if (!this.$store.state.login.boolusuario){
      //    this.$router.replace({ path: '/' })
      // }

      //this.misdatos();
      this.mismateriales();
      this.mistiendas();
      this.misdatos();

    },
    methods: {
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
            console.log(comp.contenidos)
            this.detalle=comp.contenidos
            this.dialogDetalle=true
        },
        registrar(){
            if(this.listado.length==0)
            return false
            this.dato.contenido=this.listado
            this.dato.tienda_id=this.tienda.id
            this.$axios.post("compra",this.dato).then((res) => {
                console.log(res.data)
                this.misdatos()
                //return false
                this.$q.notify({
            color: "green-4",
            icon: "check",
            message: "Creado correctamente",
          });
          this.alert=false
          this.dato={}
            this.listado=[]

        })

        },
        eliminar(list){
            console.log(list)
            this.listado.splice(list.rowIndex, 1);

        },
        agregarMaterial(){
            console.log(this.listado)
            if(this.reg.cantidad==undefined || this.reg.cantidad<=0
            || this.reg.unitario==undefined || this.reg.unitario==''
            || this.reg.material.id==undefined)
            {
            console.log('error2 ing')
            return false
            }

            this.reg.total=parseFloat( this.reg.cantidad) * parseFloat( this.reg.unitario)
            this.listado.push(this.reg);

            this.reg={material:{label:''}}
        },

      mismateriales() {
        this.$q.loading.show();
        this.$axios.get("material").then((res) => {
          console.log(res.data)
          res.data.forEach(r => {
            r.label=r.nombre
          });
          this.materiales = res.data;
          this.$q.loading.hide();
          this.material=this.materiales[0]
        })
      },
      mistiendas() {
        this.$q.loading.show();
        this.$axios.get("tienda").then((res) => {
          console.log(res.data)
          res.data.forEach(r => {
            r.label=r.nombre
          });
          this.tiendas = res.data;
          this.$q.loading.hide();
          this.tienda={label:''}
        });
      },
      misdatos() {
        this.$q.loading.show();
        this.$axios.get("compra").then((res) => {
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
        this.$axios.delete("material/" + this.dato2.id)
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
