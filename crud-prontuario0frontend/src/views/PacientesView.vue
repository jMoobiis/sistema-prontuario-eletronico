<template>
  <div class="container">

    <router-link to="/" class="btn-home">🏠 Voltar ao Início</router-link>

    <h1 class="title">👥 Pacientes</h1>

    <div class="actions">
      <input
        v-model="busca"
        placeholder="🔍 Buscar por nome ou CPF"
        class="search"
      />
      <router-link to="/pacientes/novo" class="btn-primary">+ Novo Paciente</router-link>
    </div>

    <div v-if="pacientesFiltrados.length" class="pacientes-list">
      <div
        v-for="p in pacientesFiltrados"
        :key="p.id"
        class="paciente-card"
      >
        <div class="info">
          <h2>{{ p.nome }} {{ p.sobrenome }}</h2>
          <p>📞 {{ p.telefone || '—' }}</p>
          <p>🧾 {{ mascararCPF(p.cpf) }}</p>
        </div>
        <router-link :to="`/pacientes/${p.id}`" class="btn-link">📄 Ver ficha</router-link>
      </div>
    </div>

    <p v-else class="empty">Nenhum paciente cadastrado.</p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { pacienteService } from '@/services/api'

const pacientes = ref([])
const busca = ref('')

const mascararCPF = (cpf) => {
  if (!cpf) return ''
  const apenasNumeros = cpf.replace(/\D/g, '')
  if (apenasNumeros.length <= 2) return cpf
  const ultimos2 = apenasNumeros.slice(-2)
  return '*******' + ultimos2
}

const carregarPacientes = async () => {
  try {
    const { data } = await pacienteService.getAll()
    pacientes.value = data
  } catch (e) {
    console.error('Erro ao carregar pacientes:', e)
  }
}

const pacientesFiltrados = computed(() => {
  const termo = busca.value.toLowerCase()
  return pacientes.value.filter(
    (p) =>
      p.nome.toLowerCase().includes(termo) ||
      p.cpf.includes(termo)
  )
})

onMounted(carregarPacientes)
</script>

<style scoped>
.container {
  max-width: 900px;
  margin: 2rem auto;
  background: #fff;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}
.title {
  color: #4f46e5;
  text-align: center;
  margin-bottom: 1.5rem;
}
.actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}
.search {
  width: 65%;
  padding: 0.7rem;
  border: 1px solid #ddd;
  border-radius: 8px;
  outline: none;
  transition: 0.2s;
}
.search:focus {
  border-color: #4f46e5;
  box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
}
.btn-primary {
  background: #4f46e5;
  color: white;
  padding: 0.7rem 1.3rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.2s;
}
.btn-primary:hover {
  background: #3730a3;
}
.pacientes-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.paciente-card {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f9fafb;
  padding: 1.2rem;
  border-radius: 10px;
  border: 1px solid #e5e7eb;
  transition: 0.2s;
}
.paciente-card:hover {
  background: #f3f4f6;
  border-color: #c7d2fe;
  transform: translateY(-2px);
}
.info h2 {
  margin: 0;
  color: #111827;
  font-size: 1.1rem;
}
.info p {
  margin: 0.2rem 0;
  color: #6b7280;
}
.btn-link {
  color: #4f46e5;
  font-weight: bold;
  text-decoration: none;
  transition: 0.2s;
}
.btn-link:hover {
  text-decoration: underline;
}
.empty {
  text-align: center;
  color: #9ca3af;
  font-style: italic;
}

.btn-home {
  display: inline-block;
  background-color: #4f46e5;
  color: white;
  font-weight: 600;
  text-decoration: none;
  padding: 0.6rem 1.2rem;
  border-radius: 8px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
  margin-bottom: 1.2rem;
  transition: background 0.2s ease-in-out;
}

.btn-home:hover {
  background-color: #3730a3;
}

</style>
