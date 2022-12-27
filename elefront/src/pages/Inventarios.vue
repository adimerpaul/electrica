<template>
  <div class="q-pa-md">
    <div class="row">
      <div class="col-3"><q-input outlined v-model="nrocompra" type="text" label="NRO COMPRA" /></div>
      <div class="col-3"><q-select outlined  v-model="material" :options="materiales" label="MATERIALES" /></div>
      <div class="col-3"><q-btn color="green" icon="search" label="Buscar" @click="misdatos" /></div>
    </div>

    <q-table :filter="filter" title="LISTA DE INVENTARIO" :data="data" :columns="columns" row-key="name" :rows-per-page-options="[50,100]">
      <template v-slot:top-right>
        <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:body-cell-opcion="props">
          <q-td key="opcion" :props="props">
          </q-td>

      </template>
      <template v-slot:body-cell-nombre="props">
          <q-td key="nombre" :props="props">
            {{props.row.material.nombre }}
          </q-td>

      </template>
    </q-table>

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
      nrocompra:'',
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
      {name: "cantidad", align: "left", label: "cantidad ", field: "cantidad", sortable: true,},
      {name: "num", align: "left", label: "num ", field: "num", sortable: true,},
      {name: "estado", align: "left", label: "estado ", field: "estado", sortable: true,},
        { name: "opcion", label: "OPCIÓN", field: "opcion", sortable: false },
      ],
      materiales:[],
      material:{},
      data: [],
    };
  },
  created() {
    // if (!this.$store.state.login.boolusuario){
    //    this.$router.replace({ path: '/' })
    // }
    this.mismateriales();

    this.misdatos();

  },
  methods: {
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
    misdatos() {
      this.$q.loading.show();
      this.$axios.post("buscarInv",{compra:this.nrocompra,material_id:this.material.id}).then((res) => {
        console.log(res.data)
        this.data = res.data;
        this.$q.loading.hide();
      });
    },

  },
};
</script>
