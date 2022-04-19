import Mantenimiento from "pages/Mantenimiento";
import Denuncia from "pages/Denuncia";
import Login from "pages/Login";

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Index.vue'),meta: {requiresAuth: true} },
      { path: 'mantenimiento', component: Mantenimiento ,meta: {requiresAuth: true}},
      { path: 'denuncia', component: Denuncia ,meta: {requiresAuth: true}},
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
