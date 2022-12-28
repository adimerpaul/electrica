
<template>
<q-page  padding>
  <div class="col-6">
    <q-form
      @submit.prevent="consultar"
      class="q-gutter-md"
      >
      <div class="col-6"><q-input v-model="codigo" type="text" label="Codigo Material" autofocus dense outlined/></div>
      <div>
      </div>
    </q-form>
   </div>
   <template v-if="correcto">
    <table>
      <tr><th>Nro Compra</th><td>{{ info.compra.nrocompra }}</td></tr>
      <tr><th>Fecha Compra</th><td>{{ info.compra.fecha }}</td></tr>
      <tr><th>Proveedor/Tienda</th><td>{{ info.compra.tienda.nombre }}</td></tr>
      <tr><th>Codigo Material</th><td>{{ info.material.codigo }}</td></tr>
      <tr><th>Material</th><td>{{ info.material.nombre }}</td></tr>
      <tr><th>Cantidad Stock</th><td>{{ info.material.stock }}</td></tr>
      <tr><th>Cantidad Compra</th><td>{{ info.cantidad }}</td></tr>
      <tr><th>Numero</th><td>{{ info.num }}</td></tr>
    </table>
   </template>
</q-page>
</template>

<script>
export default {
  data() {
      return {
        info: {},
        correcto:false,
        codigo:''
      }
  },
  created() {
  },
  methods: {
    consultar(){
      if(this.codigo==undefined || this.codigo=='')
        return false
      this.info={}
      this.correcto=false
      this.$axios.get("recuperaInv/"+this.codigo).then((res) => {
        console.log(res.data)
        if(res.data!='')
        {this.info=res.data
          this.correcto=true
        }
      })
      this.codigo=''
    }
  }
}
</script>

<style>
table{
  width: 100%;

}
th{text-align: right;}
td{text-align: left;}
</style>
