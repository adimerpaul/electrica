import Vue from 'vue'
import axios from 'axios'
// import Vue from 'vue';
import { LMap, LTileLayer, LMarker,LIcon } from 'vue2-leaflet';
import 'leaflet/dist/leaflet.css';

import L from 'leaflet';

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

Vue.prototype.$axios = axios
Vue.prototype.$axios.defaults.baseURL = process.env.API
