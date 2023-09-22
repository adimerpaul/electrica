import Mantenimiento from "pages/Mantenimiento";
import Denuncia from "pages/Denuncia";
import Login from "pages/Login";
import Mapa from "pages/Mapa";
import Reportedenuncia from "pages/Reportedenuncia";
import Alumbrado from "pages/Alumbrado";
import Informacion from "pages/Informacion";
import prueba from "pages/prueba";
import prueba2 from "pages/prueba2";
import Registroreclamo from "pages/Registroreclamo";
import Reclamo from "pages/Reclamo";
import User from "pages/User";
import Tecnicos from "pages/Tecnicos";
import Apublico from "pages/Apublico";
import Reportereclamo from "pages/Reportereclamo";
import Reporteposte from "pages/Reporteposte";
import Inspeccion from "pages/Inspeccion";
import Cronograma from "pages/Cronograma";
import Juntavecinal from "pages/Juntavecinal";
import Materiales from "pages/Materiales";
import Inventarios from "pages/Inventarios";
import Datomaterial from "pages/Datomaterial";
import Compras from "pages/Compras";
import Tiendas from "pages/Tiendas";
import Salidas from "pages/Salidas";
import Devoluciontecnico from "pages/Devoluciontecnico";
import Dependencias from "pages/Dependencias";
import ReporteJob from "pages/ReporteJob";
import Acta from "pages/Acta";
import ReporteMaterial from "pages/ReporteMaterial";
import ListaActa from "pages/ListaActa";
import Agenda from "pages/Agenda";
import Resumenreporte from "pages/Resumenreporte";
import Herramientas from "pages/Herramientas";
import Ordencompras from "pages/Ordencompras";
import Prestamo from "pages/Prestamo";
import Home from "pages/Home";

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Home.vue'),meta: {requiresAuth: true} },
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
      { path: 'reporteposte', component: Reporteposte ,meta: {requiresAuth: true}},
      { path: 'inspeccion', component: Inspeccion ,meta: {requiresAuth: true}},
      { path: 'cronograma', component: Cronograma ,meta: {requiresAuth: true}},
      { path: 'juntavecinal', component: Juntavecinal ,meta: {requiresAuth: true}},
      { path: 'materiales', component: Materiales ,meta: {requiresAuth: true}},
      { path: 'compras', component: Compras ,meta: {requiresAuth: true}},
      { path: 'tiendas', component: Tiendas ,meta: {requiresAuth: true}},
      { path: 'inventarios', component: Inventarios ,meta: {requiresAuth: true}},
      { path: 'materialinfo', component: Datomaterial ,meta: {requiresAuth: true}},
      { path: 'salidas', component: Salidas ,meta: {requiresAuth: true}},
      { path: 'home', component: Home ,meta: {requiresAuth: true}},
      { path: 'devolucion', component: Devoluciontecnico ,meta: {requiresAuth: true}},
      { path: 'dependencias', component: Dependencias ,meta: {requiresAuth: true}},
      { path: 'reportes', component: ReporteJob ,meta: {requiresAuth: true}},
      { path: 'acta', component: Acta ,meta: {requiresAuth: true}},
      { path: 'agenda', component: Agenda ,meta: {requiresAuth: true}},
      { path: 'reportematerial', component: ReporteMaterial ,meta: {requiresAuth: true}},
      { path: 'resumenreporte', component: Resumenreporte ,meta: {requiresAuth: true}},
      { path: 'herramientas', component: Herramientas ,meta: {requiresAuth: true}},
      { path: 'ordencompras', component: Ordencompras ,meta: {requiresAuth: true}},
      { path: 'prestamo', component: Prestamo ,meta: {requiresAuth: true}},
      { path: 'informacion', component: Informacion},
      { path: 'listaActa', component: ListaActa},
      { path: 'login', component: Login },
    ]
  },
  {path:'/reclamoalumbrado',component:prueba},
  {path:'/registroreclamo',component:Registroreclamo},
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
