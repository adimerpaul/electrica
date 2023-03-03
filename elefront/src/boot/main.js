import { createApp } from 'vue'
import VueGoogleMaps from '@fawmi/vue-google-maps'

const app = createApp(App);
app.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyCmleql2pcVM1MLcnwe0oBsAd0Hi8EQ1Uw',
        // language: 'de',
    },
}).mount('#app')
