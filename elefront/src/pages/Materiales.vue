<template>
    <div class="q-pa-md">
      <q-btn
        label="Registrar Material"
        color="positive"
        @click="alert = true"
        icon="add_circle"
        class="q-mb-xs"
      />

      <q-dialog v-model="alert">
        <q-card style="max-width: 80%; width: 70%">
          <q-card-section class="bg-green-14 text-white">
            <div class="text-h7"><q-icon name="add_circle" /> REGISTRO MATERIALES ALMACEN</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
                  <q-input outlined v-model="dato.codigo" type="text" label="CODIGO " hint="Ingresar codigo" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" />
                  <q-input outlined v-model="dato.nombre" type="text" label="Nombre " hint="Ingresar nombre" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" />
                  <q-input outlined v-model="dato.unidad" type="text" label="Unidad Medida " hint="Ingresar unidad" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" />
                  <q-input outlined v-model="dato.minimo" type="number" label="Minimo stock" hint="minimo alerta"  />
                  <q-toggle v-model="dato.codificar" color="green" false-value="NO" true-value="SI" :label="dato.codificar+' CODIFICAR'"/>
              <div>
                <q-btn label="Crear" type="submit" color="positive" icon="add_circle" />
                <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>

      <q-table :filter="filter" title="LISTA DE MATERIALES ALMACEN" :data="data" :columns="columns" row-key="name" :rows-per-page-options="[50,100]">
        <template v-slot:top-right>
          <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body-cell-minimo="props">
            <q-td key="minimo" :props="props">
              <q-badge :color="props.row.minimo>props.row.stock?'red':'green'"  >{{ props.row.minimo }}
                </q-badge>
            </q-td>

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
            <div class="text-h7"> <q-icon name="edit"/> MODIFICAR DATOS MATERIAL</div>
          </q-card-section>
          <q-card-section class="q-pt-xs">
            <q-form @submit="onMod" class="q-gutter-md">
                <q-input outlined v-model="dato2.codigo" type="text" label="CODIGO " hint="Ingresar codigo" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" />
                  <q-input outlined v-model="dato2.nombre" type="text" label="Nombre " hint="Ingresar nombre" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" />
                  <q-input outlined v-model="dato2.unidad" type="text" label="Unidad Medida " hint="Ingresar unidad" lazy-rules :rules="[(val) => (val && val.length > 0) || 'Por favor ingresa datos']" />
                  <q-input outlined v-model="dato2.minimo" type="number" label="Minimo stock" hint="nimimo alerta"  />
                  <q-toggle v-model="dato2.codificar" color="green" false-value="NO" true-value="SI" :label="dato2.codificar+' CODIFICAR'"/>
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
  const { addToDate } = date
  export default {
    data() {
      return {
        alert: false,
        dialog_mod: false,
        dialog_del: false,
        filter:'',
        dato: {codificar:'NO'},
        model:'',
        dato2: {},
        options: [],
        props: [],
        modelpermiso:false,
        uni:{},
        columns: [
        {name: "codigo", align: "left", label: "CODIGO ", field: "codigo", sortable: true,},
        {name: "nombre", align: "left", label: "NOMBRE ", field: "nombre", sortable: true,},
        {name: "unidad", align: "left", label: "UNIDAD ", field: "unidad", sortable: true,},
        {name: "stock", align: "left", label: "STOCK ", field: "stock", sortable: true,},
          {name: "minimo", align: "left", label: "MINIMO", field: "minimo", sortable: true,},
          {name: "codificar", align: "left", label: "codificar", field: "codificar", sortable: true,},
          { name: "opcion", label: "OPCIÓN", field: "opcion", sortable: false },
        ],
        data: [],
      };
    },
    created() {
      // if (!this.$store.state.login.boolusuario){
      //    this.$router.replace({ path: '/' })
      // }

      this.misdatos();

    },
    methods: {

      misdatos() {
        this.$q.loading.show();
        this.$axios.get("material").then((res) => {
          console.log(res.data)
          this.data = res.data;
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
          this.dato={codificar:'NO'}
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
      onMod() {
        this.$q.loading.show();
        this.$axios.put("material/" + this.dato2.id, this.dato2).then((res) => {
          this.$q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Modificado correctamente",
          });
          this.dialog_mod = false;
          this.misdatos();
        });
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
        this.dato= {codificar:'NO'};
      },
    },
  };
  </script>
