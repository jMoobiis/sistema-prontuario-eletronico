<template>
  <div class="container">
    <!-- Botão para voltar ao início -->
    <router-link to="/" class="btn-home">🏠 Voltar ao Início</router-link>

    <h1 class="titulo-principal">📄 Ficha do Paciente</h1>

    <div class="card">
      <router-link to="/pacientes" class="voltar">⬅ Voltar</router-link>

      <!-- Modo Visualização -->
      <div v-if="!editando">
        <h2>{{ paciente.nome }} {{ paciente.sobrenome }}</h2>
        <p><strong>📅 Nascimento:</strong> {{ paciente.data_nascimento }}</p>
        <p><strong>📞 Telefone:</strong> {{ paciente.telefone }}</p>
        <p><strong>🏠 Endereço:</strong> {{ paciente.endereco }}</p>
        <p><strong>🧾 CPF:</strong> {{ mascararCPF(paciente.cpf) }}</p>

        <div class="botoes">
          <button class="btn-editar" @click="editando = true">✏️ Editar</button>
          <button class="btn-verde" @click="abrirModalNovo">➕ Novo Atendimento</button>
        </div>
      </div>

      <!-- Modo Edição -->
      <div v-else class="form-edicao">
        <label>Nome:</label>
        <input v-model="paciente.nome" />

        <label>Sobrenome:</label>
        <input v-model="paciente.sobrenome" />

        <label>Data de Nascimento:</label>
        <input type="date" v-model="paciente.data_nascimento" />

        <label>Telefone:</label>
        <input v-model="paciente.telefone" />

        <label>Endereço:</label>
        <input v-model="paciente.endereco" />

        <label>CPF:</label>
        <input v-model="paciente.cpf" />

        <div class="botoes">
          <button class="btn-salvar" @click="salvarEdicao">💾 Salvar</button>
          <button class="btn-cancelar" @click="cancelarEdicao">❌ Cancelar</button>
        </div>
      </div>
    </div>

    <!-- Lista de atendimentos -->
    <div class="card atendimentos">
      <h3>📋 Atendimentos</h3>

      <div v-if="atendimentos.length">
        <div v-for="a in atendimentos" :key="a.id" class="atendimento-item">
          <strong>{{ formatarData(a.created_at) }}</strong> — {{ a.hipotese_diagnostica }}
          <span v-if="a.cid"> (CID: {{ a.cid }})</span>
          <button class="btn-editar-atendimento" @click="abrirEdicaoAtendimento(a)">✏️</button>
        </div>
      </div>

      <p v-else>Nenhum atendimento registrado para este paciente.</p>
    </div>

    <!-- Modal de Edição de Atendimento -->
    <div v-if="editandoAtendimento" class="modal-overlay">
      <div class="modal-card">
        <h3>Editar Atendimento</h3>

        <label>Hipótese Diagnóstica:</label>
        <input v-model="atendimentoEdit.hipotese_diagnostica" />

        <label>CID (opcional):</label>
        <input v-model="atendimentoEdit.cid" />

        <div class="botoes">
          <button class="btn-salvar" @click="salvarAtendimento">💾 Salvar</button>
          <button class="btn-cancelar" @click="cancelarEdicaoAtendimento">❌ Cancelar</button>
        </div>
      </div>
    </div>

    <!-- Modal de Novo Atendimento -->
    <div v-if="modalNovoAberto" class="modal-overlay">
      <div class="modal-card">
        <h3>Novo Atendimento</h3>

        <label>Hipótese Diagnóstica:</label>
        <input v-model="novoAtendimento.hipotese_diagnostica" placeholder="Ex: Anemia, Gripe, etc" />

        <label>CID (opcional):</label>
        <input v-model="novoAtendimento.cid" placeholder="Ex: F41.1" />

        <div class="botoes">
          <button class="btn-salvar" @click="salvarNovoAtendimento">💾 Salvar</button>
          <button class="btn-cancelar" @click="fecharModalNovo">❌ Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { pacienteService, atendimentoService } from '@/services/api'

const route = useRoute()
const paciente = ref({})
const atendimentos = ref([])
const editando = ref(false)
const editandoAtendimento = ref(false)
const atendimentoEdit = ref({})
const modalNovoAberto = ref(false)
const novoAtendimento = ref({
  hipotese_diagnostica: '',
  cid: ''
})

// 🧾 Função para mascarar CPF
const mascararCPF = (cpf) => {
  if (!cpf) return ''
  const apenasNumeros = cpf.replace(/\D/g, '')
  if (apenasNumeros.length <= 2) return cpf
  const ultimos2 = apenasNumeros.slice(-2)
  return '*******' + ultimos2
}

const carregarDados = async () => {
  try {
    const id = route.params.id
    const { data } = await pacienteService.getById(id)
    paciente.value = data
    const atRes = await atendimentoService.getAll()
    atendimentos.value = atRes.data.filter(a => a.paciente_id === data.id)
  } catch (error) {
    console.error(error)
    alert('Não foi possível carregar os dados do paciente.')
  }
}

const salvarEdicao = async () => {
  try {
    await pacienteService.update(paciente.value.id, paciente.value)
    alert('✅ Paciente atualizado com sucesso!')
    editando.value = false
    carregarDados()
  } catch (error) {
    console.error(error)
    alert('Erro ao salvar alterações.')
  }
}

const cancelarEdicao = () => {
  editando.value = false
  carregarDados()
}

const formatarData = (data) => {
  if (!data) return '-'
  const d = new Date(data)
  return d.toLocaleDateString('pt-BR')
}

// =====================
// 🩺 Edição de Atendimento
// =====================
const abrirEdicaoAtendimento = (a) => {
  atendimentoEdit.value = { ...a }
  editandoAtendimento.value = true
}


const salvarAtendimento = async () => {
  try {
    await atendimentoService.update(atendimentoEdit.value.id, atendimentoEdit.value)
    alert('✅ Atendimento atualizado com sucesso!')
    editandoAtendimento.value = false
    carregarDados()
  } catch (error) {
    console.error(error)
    alert('Erro ao atualizar atendimento.')
  }
}

const cancelarEdicaoAtendimento = () => {
  editandoAtendimento.value = false
}

// =====================
// 🆕 Novo Atendimento
// =====================
const abrirModalNovo = () => {
  novoAtendimento.value = { hipotese_diagnostica: '', cid: '' }
  modalNovoAberto.value = true
}

const fecharModalNovo = () => {
  modalNovoAberto.value = false
}

const salvarNovoAtendimento = async () => {
  if (!novoAtendimento.value.hipotese_diagnostica.trim()) {
    alert('Informe a hipótese diagnóstica!')
    return
  }

  try {
    await atendimentoService.create({
      paciente_id: paciente.value.id,
      hipotese_diagnostica: novoAtendimento.value.hipotese_diagnostica,
      cid: novoAtendimento.value.cid
    })
    alert('✅ Atendimento cadastrado com sucesso!')
    fecharModalNovo()
    carregarDados()
  } catch (error) {
    console.error(error)
    alert('Erro ao cadastrar atendimento.')
  }
}

onMounted(carregarDados)
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 2rem auto;
  padding: 1rem;
  background-color: #f9fafb;
  border-radius: 12px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}

.titulo-principal {
  text-align: center;
  color: #111827;
  font-weight: 700;
  margin-bottom: 2rem;
}

.voltar {
  display: inline-block;
  margin-bottom: 1rem;
  color: #4f46e5;
  text-decoration: none;
  font-weight: 500;
}

.voltar:hover {
  text-decoration: underline;
}

.card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.5rem 2rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}

h2 {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 1rem;
  color: #111827;
}

.botoes {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}

.btn-editar {
  background-color: #4f46e5;
  color: white;
  border: none;
  padding: 0.6rem 1rem;
  border-radius: 8px;
  cursor: pointer;
}

.btn-editar:hover {
  background-color: #3730a3;
}

.btn-verde {
  background-color: #10b981;
  color: white;
  border: none;
  padding: 0.6rem 1rem;
  border-radius: 8px;
  cursor: pointer;
}

.btn-verde:hover {
  background-color: #059669;
}

.btn-salvar {
  background-color: #2563eb;
  color: white;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 8px;
  cursor: pointer;
}

.btn-salvar:hover {
  background-color: #1e3a8a;
}

.btn-cancelar {
  background-color: #ef4444;
  color: white;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 8px;
  cursor: pointer;
}

.btn-cancelar:hover {
  background-color: #b91c1c;
}

.form-edicao label {
  display: block;
  margin-bottom: 0.3rem;
  font-weight: 500;
  color: #111827;
}

.form-edicao input {
  display: block;
  width: 100%;
  margin-bottom: 0.8rem;
  padding: 0.6rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  background-color: #fff;
}

.atendimentos h3 {
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: #111827;
}

.atendimento-item {
  padding: 0.6rem 0;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.btn-editar-atendimento {
  background-color: #f3f4f6;
  border: 1px solid #d1d5db;
  color: #f97316;
  padding: 0.3rem 0.6rem;
  border-radius: 6px;
  cursor: pointer;
}

.btn-editar-atendimento:hover {
  background-color: #fee2e2;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-card {
  background: #fff;
  padding: 1.5rem 2rem;
  border-radius: 12px;
  width: 400px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  border: 1px solid #e5e7eb;
}

.modal-card h3 {
  font-size: 1.2rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 1rem;
  text-align: center;
}

.modal-card label {
  display: block;
  margin-top: 0.5rem;
  font-weight: 500;
  color: #111827;
}

.modal-card input {
  width: 100%;
  margin-top: 0.3rem;
  padding: 0.6rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  background: #fff;
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
