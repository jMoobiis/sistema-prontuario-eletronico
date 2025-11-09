import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import PacientesView from '../views/PacientesView.vue'
import AtendimentosView from '../views/AtendimentosView.vue'
import PacienteFichaView from '../views/PacienteFichaView.vue'
import PacienteCreateView from '../views/PacienteCreateView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', component: HomeView },
    { path: '/pacientes', component: PacientesView },
    { path: '/pacientes/novo', component: PacienteCreateView },
    { path: '/pacientes/:id', component: PacienteFichaView },
    { path: '/atendimentos', component: AtendimentosView },
  ]
})

export default router
