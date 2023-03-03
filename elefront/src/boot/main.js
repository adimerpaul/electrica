import { createApp } from 'vue'
import VueGoogleMaps from '@fawmi/vue-google-maps'

const app = createApp(App);
app.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyAf47yQQOBeabQgDXNQo5Ba2MTbJNtS-JU',
        // language: 'de',
    },
}).mount('#app')
