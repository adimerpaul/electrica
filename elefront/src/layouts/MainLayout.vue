<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
        />

        <q-toolbar-title>
          <q-img src="logo.png" style="width: 30px"/>
          {{$store.getters["login/user"].name}}
        </q-toolbar-title>
        <div>
<!--          Quasar v{{ $q.version }}-->
          <q-btn color="negative" icon="logout" @click="logout" label="Salir"  />
        </div>
      </q-toolbar>
    </q-header>
    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      content-class="bg-grey-1"
    >
      <q-list>
        <q-item-label header class="text-grey-8">
          Opciones de menu
        </q-item-label>
        <q-item clickable to="/" exact>
          <q-item-section avatar>
            <q-icon name="list" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Puntos</q-item-label>
            <q-item-label caption>
              Detalles de todos los puntos
            </q-item-label>
          </q-item-section>
        </q-item>
        <q-item clickable to="/mantenimiento" exact>
          <q-item-section avatar>
            <q-icon name="my_location" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Mantenimiento</q-item-label>
            <q-item-label caption>
              Puntos en mantenimiento
            </q-item-label>
          </q-item-section>
        </q-item>

        <q-expansion-item
          expand-separator
          icon="perm_identity"
          label="Denuncias"
          caption="Denuncias de contribuyente"
        >
          <q-card>
            <q-card-section>
              <q-item clickable to="/denuncia" exact>
                <q-item-section avatar>
                  <q-icon name="cell_tower" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Denuncias</q-item-label>
                  <q-item-label caption>
                    Denuncias realizadas
                  </q-item-label>
                </q-item-section>
              </q-item>
              <q-item clickable to="/reportedenuncia" exact>
                <q-item-section avatar>
                  <q-icon name="print" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Reporte denuncias</q-item-label>
                  <q-item-label caption>
                    Reporte denuncias
                  </q-item-label>
                </q-item-section>
              </q-item>
            </q-card-section>

          </q-card>
        </q-expansion-item>

        <q-item v-if="$store.getters['login/isLoggedIn']" clickable @click="logout">
          <q-item-section avatar>
            <q-icon name="logout" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Salir</q-item-label>
            <q-item-label caption>
              Salir del sistema
            </q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>


export default {
  data () {
    return {
      leftDrawerOpen: false
    }
  },
  created() {
    const token = localStorage.getItem('tokenelec')
// console.log(token)
    if (token) {
      // console.log('a')
      this.$axios.defaults.headers.common['Authorization'] = 'Bearer '+token
      // axios.defaults.headers.common['Authorization'] = 'Bearer '+token
      this.$axios.post(process.env.API+'me').then(res=>{
        // console.log(res.data);
        // return false;
        // store.state.user=res.data;
        this.$store.commit('login/auth_success', {token:token,user:res.data})
      })
      .catch(err=>{
        // console.error('aas')
        this.$store.commit('login/salir')
        localStorage.removeItem('tokenelec')

      })
    }
  },
  methods:{
    logout () {
      this.$q.loading.show()
      this.$store.dispatch('login/logout')
        .then(() => {
          this.$q.loading.hide()
          this.$router.push('/login')
        })
    },
  }
}
</script>
