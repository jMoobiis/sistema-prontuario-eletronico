<template>
  <div class="container">

    <router-link to="/" class="btn-home">🏠 Voltar ao Início</router-link>

    <h1>🆕 Novo Paciente</h1>

    <form @submit.prevent="salvar" class="card">
      <div class="form-group">
        <label>Nome:</label>
        <input v-model="paciente.nome" required />
      </div>

      <div class="form-group">
        <label>Sobrenome:</label>
        <input v-model="paciente.sobrenome" required />
      </div>

      <div class="form-group">
        <label>Data de Nascimento:</label>
        <input v-model="paciente.data_nascimento" type="date" required />
      </div>

      <div class="form-group">
        <label>CPF:</label>
        <input
          v-model="paciente.cpf"
          required
          maxlength="11"
          pattern="\d{11}"
          placeholder="Somente números (11 dígitos)"
        />
      </div>

      <div class="form-group">
        <label>Telefone:</label>
        <input v-model="paciente.telefone" inputmode="tel" />
      </div>

      <div class="form-group">
        <label>Endereço:</label>
        <input v-model="paciente.endereco" />
      </div>

      <div class="actions">
        <button type="submit" class="btn-primary">💾 Salvar</button>
        <router-link to="/pacientes" class="btn-secondary">↩️ Voltar</router-link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { pacienteService } from '@/services/api'

const router = useRouter()

const paciente = ref({
  nome: '',
  sobrenome: '',
  data_nascimento: '',
  cpf: '',
  telefone: '',
  endereco: ''
})

const salvar = async () => {
  try {
    await pacienteService.create(paciente.value)
    alert('Paciente cadastrado com sucesso!')
    router.push('/pacientes')
  } catch (error) {
    console.error(error)
    if (error?.response?.status === 422) {
      alert('CPF já cadastrado ou dados inválidos.')
    } else {
      alert('Erro ao salvar paciente.')
    }
  }
}
</script>

<style scoped>
.container {
  max-width: 700px;
  margin: 2rem auto;
  background: #fff;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}
h1 {
  color: #4f46e5;
  text-align: center;
  margin-bottom: 1.5rem;
}
.card {
  background: #f8fafc;
  padding: 1.5rem;
  border-radius: 12px;
}
.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
}
input {
  padding: 0.6rem;
  border: 1px solid #ccc;
  border-radius: 8px;
}
.actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1.5rem;
}
.btn-primary {
  background: #4f46e5;
  color: white;
  padding: 0.7rem 1.3rem;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  transition: 0.2s;
}
.btn-primary:hover {
  background: #3730a3;
}
.btn-secondary {
  background: #e5e7eb;
  color: #111827;
  padding: 0.7rem 1.3rem;
  border-radius: 8px;
  text-decoration: none;
}
.btn-secondary:hover {
  background: #d1d5db;
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
