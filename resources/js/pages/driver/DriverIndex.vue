<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Motoristas</h1>
        <p class="mt-1 text-sm text-gray-500">Gerencie os motoristas cadastrados</p>
      </div>
      <button @click="openCreateModal" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Novo Motorista
      </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="driver in drivers" :key="driver.id" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="h-48 bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
          <svg class="w-16 h-16 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>
        <div class="p-5">
          <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ driver.nome }}</h3>
          <div class="space-y-2 text-sm text-gray-600">
            <p><span class="font-medium">CPF:</span> {{ driver.cpf }}</p>
            <p><span class="font-medium">CNH:</span> {{ driver.cnh }} ({{ driver.categoria_cnh }})</p>
            <p><span class="font-medium">RG:</span> {{ driver.rg }}</p>
            <p><span class="font-medium">Endereço:</span> {{ driver.endereco }}, {{ driver.cidade }}/{{ driver.estado }}</p>
            <p><span class="font-medium">Toxicológico:</span> {{ driver.toxicologico ? 'Em dia' : 'Pendente' }}</p>
            <p><span class="font-medium">Pendências:</span> {{ driver.pendencias ? 'Sim' : 'Não' }}</p>
          </div>
          <div class="mt-4 flex space-x-2">
            <button @click="editDriver(driver)" class="flex-1 px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">Editar</button>
            <button v-if="isAdmin" @click="deleteDriver(driver)" class="flex-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">Excluir</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="drivers.length === 0" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
      </svg>
      <p class="mt-4 text-gray-500 text-sm">Nenhum motorista encontrado.</p>
    </div>

    <!-- Modal -->
    <FormModal :open="modalOpen" :title="editing ? 'Editar Motorista' : 'Novo Motorista'" :form="form" entity="drivers" @close="closeModal">
      <form class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
          <input v-model="form.nome" type="text" required placeholder="Nome completo" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
            <input v-model="form.cpf" type="text" placeholder="000.000.000-00" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">RG</label>
            <input v-model="form.rg" type="text" placeholder="00.000.000-0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">CNH</label>
            <input v-model="form.cnh" type="text" placeholder="Número da CNH" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoria CNH</label>
            <select v-model="form.categoria_cnh" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
              <option value="D">D</option>
              <option value="E">E</option>
            </select>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
          <input v-model="form.endereco" type="text" placeholder="Rua, número" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
            <input v-model="form.bairro" type="text" placeholder="Bairro" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
            <input v-model="form.cidade" type="text" placeholder="Cidade" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <select v-model="form.estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
              <option value="">Selecione</option>
              <option value="SP">SP</option>
              <option value="RJ">RJ</option>
              <option value="MG">MG</option>
              <option value="RS">RS</option>
              <option value="PR">PR</option>
              <option value="BA">BA</option>
              <option value="DF">DF</option>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
            <input v-model="form.cep" type="text" placeholder="00000-000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
        <div class="flex space-x-6">
          <div class="flex items-center">
            <input v-model="form.toxicologico" type="checkbox" id="driver-toxico" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            <label for="driver-toxico" class="ml-2 text-sm text-gray-700">Toxicológico em dia</label>
          </div>
          <div class="flex items-center">
            <input v-model="form.pendencias" type="checkbox" id="driver-pendencias" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            <label for="driver-pendencias" class="ml-2 text-sm text-gray-700">Possui pendências</label>
          </div>
        </div>
      </form>

      <template #footer>
        <button type="button" @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancelar</button>
        <button type="button" @click="saveDriver" :disabled="saving" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50">
          {{ saving ? 'Salvando...' : 'Salvar' }}
        </button>
      </template>
    </FormModal>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import api from '@/services/api'
import FormModal from '@components/FormModal.vue'

const drivers = ref([])
const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)

/**
 * Nível do usuário logado (vem do sessionStorage setado no login)
 */
const userLevel = computed(() => sessionStorage.getItem('user_level') || '')
const isAdmin = computed(() => userLevel.value === 'admin')

const form = reactive({
  nome: '', cpf: '', rg: '', cnh: '', categoria_cnh: 'D',
  endereco: '', bairro: '', cidade: '', estado: 'SP', cep: '',
  toxicologico: false, pendencias: false,
})

onMounted(async () => {
  try {
    const res = await api.get('/api/drivers')
    drivers.value = res.data?.drivers ?? []
  } catch (e) { console.error('Erro ao carregar motoristas:', e) }
})

function openCreateModal() {
  editing.value = null
  Object.assign(form, {
    nome: '', cpf: '', rg: '', cnh: '', categoria_cnh: 'D',
    endereco: '', bairro: '', cidade: '', estado: 'SP', cep: '',
    toxicologico: false, pendencias: false,
  })
  modalOpen.value = true
}

function editDriver(d) {
  editing.value = d
  Object.assign(form, {
    nome: d.nome, cpf: d.cpf, rg: d.rg || '', cnh: d.cnh,
    categoria_cnh: d.categoria_cnh || 'D',
    endereco: d.endereco || '', bairro: d.bairro || '',
    cidade: d.cidade || '', estado: d.estado || 'SP', cep: d.cep || '',
    toxicologico: !!d.toxicologico, pendencias: !!d.pendencias,
  })
  modalOpen.value = true
}

function closeModal() { modalOpen.value = false; editing.value = null }

async function saveDriver() {
  saving.value = true
  try {
    if (editing.value) {
      await api.put(`/api/drivers/${editing.value.id}`, form)
      const idx = drivers.value.findIndex(d => d.id === editing.value.id)
      if (idx !== -1) drivers.value[idx] = { ...editing.value, ...form }
    } else {
      const res = await api.post('/api/drivers', form)
      drivers.value.push(res.data?.driver || { ...form, id: Date.now() })
    }
    closeModal()
  } catch (e) { console.error('Erro ao salvar:', e); alert('Erro ao salvar.') }
  finally { saving.value = false }
}

async function deleteDriver(d) {
  if (!confirm(`Excluir motorista ${d.nome}?`)) return
  try {
    await api.delete(`/api/drivers/${d.id}`)
    drivers.value = drivers.value.filter(x => x.id !== d.id)
  } catch (e) { console.error('Erro ao excluir:', e) }
}
</script>
