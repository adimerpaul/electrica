import Mantenimiento from "pages/Mantenimiento";
import Denuncia from "pages/Denuncia";
import Login from "pages/Login";
import Mapa from "pages/Mapa";
import Reportedenuncia from "pages/Reportedenuncia";
import Alumbrado from "pages/Alumbrado";
import Informacion from "pages/Informacion";

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
      { path: 'informacion', component: Informacion},
      { path: 'login', component: Login },
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
