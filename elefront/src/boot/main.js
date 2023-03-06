import { createApp } from 'vue'
import VueGoogleMaps from '@fawmi/vue-google-maps'

const app = createApp(App);
app.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyDtDisHrUQNc_iS4YcvBS5onSB3f0fcoHs',
        // language: 'de',
    },
}).mount('#app')
