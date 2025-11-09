import { createApp } from 'vue'
import App from './App.vue'
import router from './router' // importa o router

const app = createApp(App)
app.use(router) // registra o router no app
app.mount('#app') // monta o app na div #app
