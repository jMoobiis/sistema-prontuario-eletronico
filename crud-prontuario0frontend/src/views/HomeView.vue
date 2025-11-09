<template>
  <div class="dashboard-container">
    <h1 class="title">🏥 Sistema de Prontuário Eletrônico</h1>
    <p class="subtitle">Gerencie pacientes e atendimentos de forma simples.</p>

    <div class="card-grid">
      <router-link to="/pacientes" class="card">
        <h2>👥 Pacientes</h2>
        <p>{{ totalPacientes }} cadastrados</p>
      </router-link>

      <router-link to="/atendimentos" class="card">
        <h2>📋 Atendimentos</h2>
        <p>{{ totalAtendimentos }} realizados</p>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { pacienteService, atendimentoService } from '@/services/api'

const totalPacientes = ref(0)
const totalAtendimentos = ref(0)

const carregarTotais = async () => {
  const [pacs, atends] = await Promise.all([
    pacienteService.getAll(),
    atendimentoService.getAll()
  ])

  totalPacientes.value = pacs.data.length
  totalAtendimentos.value = atends.data.length
}

onMounted(carregarTotais)
</script>

<style scoped>

body {
  background-color: #f9fafb;
}
.dashboard-container {
  text-align: center;
  margin-top: 3rem;
}
.title {
  color: #4f46e5;
  margin-bottom: 0.5rem;
}
.subtitle {
  color: #6b7280;
  margin-bottom: 2rem;
}
.card-grid {
  display: flex;
  justify-content: center;
  gap: 2rem;
  flex-wrap: wrap;
}
.card {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  width: 250px;
  height: 150px;
  background-color: #ffffff;
  border: 1.5px solid rgba(79, 70, 229, 0.25); /* 👈 borda leve */
  border-radius: 12px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);   /* 👈 sombra sutil */
  transition: all 0.2s ease-in-out;
  cursor: pointer;
}
.card:hover {
  border-color: #4f46e5; /* cor roxa mais visível no hover */
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(79, 70, 229, 0.2);
}

.card,
.card:visited,
.card:hover,
.card:active {
  text-decoration: none; /* 👈 remove o sublinhado */
  color: inherit;        /* mantém a cor original do texto */
}

h2 {
  margin-bottom: 1rem;
}
</style>
