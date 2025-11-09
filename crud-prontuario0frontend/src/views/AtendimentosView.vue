<template>
  <div class="container">

    <router-link to="/" class="btn-home">🏠 Voltar ao Início</router-link>

    <h1>Cadastro de Atendimentos</h1>

    <!-- Formulário de Cadastro -->
    <form @submit.prevent="salvarAtendimento" class="card">
      <div class="form-group">
        <label>Paciente:</label>
        <select v-model="novoAtendimento.paciente_id" required>
          <option value="">Selecione um paciente</option>
          <option v-for="p in pacientes" :key="p.id" :value="p.id">
            {{ p.nome }} {{ p.sobrenome }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Hipótese Diagnóstica:</label>
        <input v-model="novoAtendimento.hipotese_diagnostica" required />
      </div>

      <div class="form-group">
        <label>CID (opcional):</label>
        <input v-model="novoAtendimento.cid" placeholder="Ex: A00, B34, etc" />
      </div>

      <button type="submit" class="btn-primary">Salvar</button>
    </form>

    <!-- Lista de Atendimentos -->
    <div v-if="atendimentos.length" class="card table-container">
      <h2>Lista de Atendimentos</h2>
      <table>
        <thead>
          <tr>
            <th>Paciente</th>
            <th>Hipótese Diagnóstica</th>
            <th>CID</th>
            <th>Data</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="a in atendimentos" :key="a.id">
            <td>{{ a.paciente?.nome }} {{ a.paciente?.sobrenome }}</td>
            <td>{{ a.hipotese_diagnostica }}</td>
            <td>{{ a.cid || '-' }}</td>
            <td>{{ formatarData(a.created_at) }}</td>
            <td>
              <button @click="abrirEdicao(a)" class="btn-editar">Editar</button>
              <button @click="excluirAtendimento(a.id)" class="btn-delete">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <p v-else class="empty">Nenhum atendimento registrado.</p>

    <!-- Modal de Edição -->
    <div v-if="editandoAtendimento" class="modal-overlay">
      <div class="modal-card">
        <h3>Editar Atendimento</h3>

        <label>Hipótese Diagnóstica:</label>
        <input v-model="atendimentoEdit.hipotese_diagnostica" />

        <label>CID:</label>
        <input v-model="atendimentoEdit.cid" />

        <div class="botoes">
          <button class="btn-primary" @click="salvarEdicao">💾 Salvar</button>
          <button class="btn-cancelar" @click="cancelarEdicao">❌ Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { pacienteService, atendimentoService } from '@/services/api'

const pacientes = ref([])
const atendimentos = ref([])

const novoAtendimento = ref({
  paciente_id: '',
  hipotese_diagnostica: '',
  cid: ''
})

const editandoAtendimento = ref(false)
const atendimentoEdit = ref({})

const carregarDados = async () => {
  try {
    const [pac, at] = await Promise.all([
      pacienteService.getAll(),
      atendimentoService.getAll()
    ])
    pacientes.value = pac.data
    atendimentos.value = at.data
  } catch (error) {
    console.error('Erro ao carregar dados:', error)
  }
}

const salvarAtendimento = async () => {
  try {
    await atendimentoService.create(novoAtendimento.value)
    alert('✅ Atendimento cadastrado!')
    novoAtendimento.value = { paciente_id: '', hipotese_diagnostica: '', cid: '' }
    carregarDados()
  } catch (e) {
    console.error(e)
    alert('Erro ao cadastrar atendimento.')
  }
}

const excluirAtendimento = async (id) => {
  if (confirm('Deseja realmente excluir este atendimento?')) {
    try {
      await atendimentoService.delete(id)
      carregarDados()
    } catch (e) {
      console.error(e)
      alert('Erro ao excluir atendimento.')
    }
  }
}

const abrirEdicao = (a) => {
  atendimentoEdit.value = { ...a }
  editandoAtendimento.value = true
}

const salvarEdicao = async () => {
  try {
    await atendimentoService.update(atendimentoEdit.value.id, {
      hipotese_diagnostica: atendimentoEdit.value.hipotese_diagnostica,
      cid: atendimentoEdit.value.cid
    })
    alert('✅ Atendimento atualizado!')
    editandoAtendimento.value = false
    carregarDados()
  } catch (e) {
    console.error(e)
    alert('Erro ao atualizar atendimento.')
  }
}

const cancelarEdicao = () => {
  atendimentoEdit.value = {}
  editandoAtendimento.value = false
}

const formatarData = (data) => {
  if (!data) return '-'
  return new Date(data).toLocaleDateString('pt-BR')
}

onMounted(carregarDados)
</script>

<style scoped>
.container {
  max-width: 900px;
  margin: 2rem auto;
  padding: 1rem;
  background-color: #f9fafb;
  border-radius: 12px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}

h1 {
  text-align: center;
  color: #111827;
  font-weight: 700;
  margin-bottom: 2rem;
}

.card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.5rem 2rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}

/* Formulário */
form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

label {
  font-weight: 600;
  margin-bottom: 0.3rem;
  color: #111827;
}

input,
select {
  width: 100%;
  padding: 0.6rem;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  background: #fff;
  font-size: 1rem;
  transition: 0.2s;
}

input:focus,
select:focus {
  border-color: #6366f1;
  outline: none;
  box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
}

button {
  cursor: pointer;
  font-weight: 600;
  border-radius: 8px;
  border: none;
  transition: all 0.2s ease-in-out;
}

/* Botões principais */
.btn-primary {
  background-color: #4f46e5;
  color: #fff;
  padding: 0.6rem 1.2rem;
  width: fit-content;
  align-self: flex-end;
}

.btn-primary:hover {
  background-color: #3730a3;
}

/* Botão excluir */
.btn-delete {
  background-color: #ef4444;
  color: #fff;
  padding: 0.4rem 0.8rem;
  border-radius: 6px;
}

.btn-delete:hover {
  background-color: #b91c1c;
}

/* Botão editar */
.btn-editar {
  background-color: #4f46e5;
  color: white;
  padding: 0.4rem 0.8rem;
  border-radius: 6px;
  margin-right: 0.4rem;
}

.btn-editar:hover {
  background-color: #3730a3;
}

/* Tabela */
.table-container {
  overflow-x: auto;
}

.table-container table {
  width: 100%;
  border-collapse: collapse;
}

table th,
table td {
  padding: 0.8rem;
  border-bottom: 1px solid #e5e7eb;
  text-align: left;
  color: #111827;
  font-size: 0.95rem;
}

table th {
  font-weight: 700;
  background: #f3f4f6;
}

/* Título da lista */
h2 {
  color: #111827;
  font-size: 1.3rem;
  font-weight: 700;
  margin-bottom: 1rem;
}

/* Estado vazio */
.empty {
  text-align: center;
  color: #6b7280;
  font-style: italic;
  margin-top: 1rem;
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
