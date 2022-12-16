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

        <q-item clickable to="usuarios" exact v-if="$store.state.login.boolusuario">
          <q-item-section avatar>
            <q-icon name="people" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Usuarios</q-item-label>
            <q-item-label caption>
              Gestion User
            </q-item-label>
          </q-item-section>
        </q-item>

        <q-item clickable to="apublico" exact >
          <q-item-section avatar>
            <q-icon name="engineering" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Gestion AP</q-item-label>
            <q-item-label caption>
              Gestion Postes
            </q-item-label>
          </q-item-section>
        </q-item>

        <q-item clickable to="/" exact >
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

        <q-item clickable to="/informacion" exact v-if="$store.state.login.boolgeneral">
          <q-item-section avatar>
            <q-icon name="map" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Datos del Alumbrado</q-item-label>
            <q-item-label caption>
              Puntos en el mapa
            </q-item-label>
          </q-item-section>
        </q-item>
        <q-item clickable to="/mantenimiento" exact v-if="$store.state.login.boolmtto">
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
        <q-item clickable to="/alumbrado" exact v-if="$store.state.login.booldistrito">
                <q-item-section avatar>
                  <q-icon name="wb_incandescent" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Alumbrado Publico</q-item-label>
                  <q-item-label caption>
                    Ubicacion
                  </q-item-label>
                </q-item-section>
              </q-item>
              <q-item clickable to="/reclamos" exact v-if="$store.state.login.booldistrito">
                <q-item-section avatar>
                  <q-icon name="handyman" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Reclamos Mapa</q-item-label>
                  <q-item-label caption>
                    Ubicacion Reclamo
                  </q-item-label>
                </q-item-section>
              </q-item>
              <q-item clickable to="/reportereclamo" exact v-if="$store.state.login.boolreporte">
                <q-item-section avatar>
                  <q-icon name="print" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Reporte Reclamos</q-item-label>
                  <q-item-label caption>
                    Reporte atencion
                  </q-item-label>
                </q-item-section>
              </q-item>
              <q-item clickable to="/cronograma" exact v-if="$store.state.login.boolcronograma">
                <q-item-section avatar>
                  <q-icon name="date_range" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Planificacion </q-item-label>
                  <q-item-label caption>
                    Actividad 
                  </q-item-label>
                </q-item-section>
              </q-item>
<!--
              <q-item clickable to="/prueba" exact>
                <q-item-section avatar>
                  <q-icon name="engineering" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Demo Reclamo</q-item-label>
                  <q-item-label caption>
                    Nuevo sistema
                  </q-item-label>
                </q-item-section>
              </q-item>

              <q-item clickable to="/prueba2" exact>
                <q-item-section avatar>
                  <q-icon name="engineering" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Demo Consulta</q-item-label>
                  <q-item-label caption>
                    Nuevo sistema
                  </q-item-label>
                </q-item-section>
              </q-item>
-->
        <q-expansion-item
          expand-separator
          icon="perm_identity"
          label="Denuncias"
          caption="Denuncias de contribuyente"
          v-if="$store.state.login.booldenuncia || $store.state.login.boolreporte"
        >
          <q-card>
            <q-card-section>
              <q-item clickable to="/denuncia" exact v-if="$store.state.login.booldenuncia">
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
              <q-item clickable to="/reportedenuncia" exact v-if="$store.state.login.boolreporte">
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
 console.log(token)
    if (token) {
      // console.log('a')
      this.$axios.defaults.headers.common['Authorization'] = 'Bearer '+token
      // axios.defaults.headers.common['Authorization'] = 'Bearer '+token
      this.$axios.post(process.env.API+'me').then(res=>{
         console.log(res.data);
        // return false;
        this.$store.state.user=res.data;
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
