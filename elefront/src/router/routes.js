import Mantenimiento from "pages/Mantenimiento";
import Denuncia from "pages/Denuncia";
import Login from "pages/Login";
import Mapa from "pages/Mapa";
import Reportedenuncia from "pages/Reportedenuncia";
import Alumbrado from "pages/Alumbrado";
import Informacion from "pages/Informacion";
import prueba from "pages/prueba";
import prueba2 from "pages/prueba2";
import Reclamo from "pages/Reclamo";
import User from "pages/User";
import Tecnicos from "pages/Tecnicos";
import Apublico from "pages/Apublico";
import Reportereclamo from "pages/Reportereclamo";
import Inspeccion from "pages/Inspeccion";
import Cronograma from "pages/Cronograma";
import Juntavecinal from "pages/Juntavecinal";
import Materiales from "pages/Materiales";
import Inventarios from "pages/Inventarios";
import Datomaterial from "pages/Datomaterial";
import Compras from "pages/Compras";
import Tiendas from "pages/Tiendas";

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Index.vue'),meta: {requiresAuth: true} },
      { path: 'mantenimiento', component: Mantenimiento ,meta: {requiresAuth: true}},
      { path: 'denuncia', component: Denuncia ,meta: {requiresAuth: true}},
      { path: 'mapa', component: Mapa ,meta: {requiresAuth: true}},
      { path: 'reportedenuncia', component: Reportedenuncia ,meta: {requiresAuth: true}},
      { path: 'alumbrado', component: Alumbrado ,meta: {requiresAuth: true}},
      { path: 'reclamos/:cedula', component: Reclamo ,meta: {requiresAuth: true}},
      { path: 'reclamos', component: Reclamo ,meta: {requiresAuth: true}},
      { path: 'usuarios', component: User ,meta: {requiresAuth: true}},
      { path: 'tecnicos', component: Tecnicos ,meta: {requiresAuth: true}},
      { path: 'apublico', component: Apublico ,meta: {requiresAuth: true}},
      { path: 'reportereclamo', component: Reportereclamo ,meta: {requiresAuth: true}},
      { path: 'inspeccion', component: Inspeccion ,meta: {requiresAuth: true}},
      { path: 'cronograma', component: Cronograma ,meta: {requiresAuth: true}},
      { path: 'juntavecinal', component: Juntavecinal ,meta: {requiresAuth: true}},
      { path: 'materiales', component: Materiales ,meta: {requiresAuth: true}},
      { path: 'compras', component: Compras ,meta: {requiresAuth: true}},
      { path: 'tiendas', component: Tiendas ,meta: {requiresAuth: true}},
      { path: 'inventarios', component: Inventarios ,meta: {requiresAuth: true}},
      { path: 'materialinfo', component: Datomaterial ,meta: {requiresAuth: true}},
      { path: 'informacion', component: Informacion},
      { path: 'login', component: Login },
    ]
  },
  {path:'/reclamoalumbrado',component:prueba},
  {path:'/consulta/:cedula',component:prueba2},
  {path:'/consulta',component:prueba2},

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
