import Vue from 'vue'
import axios from 'axios'
// import Vue from 'vue';
import { LMap, LTileLayer, LMarker,LIcon } from 'vue2-leaflet';
import 'leaflet/dist/leaflet.css';

import L from 'leaflet';
import store from "src/store";
// import store from './../store'
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png')
});


Vue.component('l-map', LMap);
Vue.component('l-tile-layer', LTileLayer);
Vue.component('l-marker', LMarker);
Vue.component('l-icon', LIcon);


// const token = localStorage.getItem('tokenelec')
// // console.log(token)
// if (token) {
//   // console.log('a')
//   // api.defaults.headers.common['Authorization'] = 'Bearer '+token
//   // axios.defaults.headers.common['Authorization'] = 'Bearer '+token
//   // axios.post(process.env.API+'me').then(res=>{
//   //   // console.log(res.data);
//   //   // return false;
//   //   // store.state.user=res.data;
//   //   store.commit('login/auth_success', {token:token,user:res.data})
//   // })
//   // .catch(err=>{
//   //   // console.error('aas')
//   //   // Vue.prototype.$store.dispatch('login/salir')
//   //   localStorage.removeItem('tokenelec')
//   //
//   // })
// }
Vue.prototype.$axios = axios

Vue.prototype.$axios.defaults.baseURL = process.env.API
