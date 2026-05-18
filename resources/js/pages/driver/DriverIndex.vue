<template>
  <div>
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
          <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ driver.name }}</h3>
          <div class="space-y-2 text-sm text-gray-600">
            <p><span class="font-medium">CPF:</span> {{ driver.document }}</p>
            <p><span class="font-medium">CNH:</span> {{ driver.cnh }}</p>
            <p><span class="font-medium">Telefone:</span> {{ driver.phone }}</p>
            <p><span class="font-medium">Email:</span> {{ driver.email }}</p>
            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full" :class="driver.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
              {{ driver.active ? 'Ativo' : 'Inativo' }}
            </span>
          </div>
          <div class="mt-4 flex space-x-2">
            <button @click="editDriver(driver)" class="flex-1 px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">Editar</button>
            <button @click="deleteDriver(driver)" class="flex-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">Excluir</button>
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
          <input v-model="form.name" type="text" required placeholder="Nome completo" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
            <input v-model="form.document" type="text" placeholder="000.000.000-00" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">CNH</label>
            <input v-model="form.cnh" type="text" placeholder="Número da CNH" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
            <input v-model="form.phone" type="text" placeholder="(11) 99999-0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="form.email" type="email" placeholder="email@exemplo.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
        <div class="flex items-center">
          <input v-model="form.active" type="checkbox" id="driver-active" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
          <label for="driver-active" class="ml-2 text-sm text-gray-700">Motorista ativo</label>
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
import { ref, reactive, onMounted } from 'vue'
import api from '@/services/api'
import FormModal from '@components/FormModal.vue'

const drivers = ref([])
const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)

const form = reactive({ name: '', document: '', cnh: '', phone: '', email: '', active: true })

onMounted(async () => {
  try {
    const res = await api.get('/api/drivers')
    drivers.value = res.data?.drivers ?? []
  } catch (e) { console.error('Erro ao carregar motoristas:', e) }
})

function openCreateModal() {
  editing.value = null
  Object.assign(form, { name: '', document: '', cnh: '', phone: '', email: '', active: true })
  modalOpen.value = true
}

function editDriver(d) {
  editing.value = d
  Object.assign(form, { name: d.name, document: d.document, cnh: d.cnh, phone: d.phone, email: d.email, active: !!d.active })
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
  if (!confirm(`Excluir motorista ${d.name}?`)) return
  try {
    await api.delete(`/api/drivers/${d.id}`)
    drivers.value = drivers.value.filter(x => x.id !== d.id)
  } catch (e) { console.error('Erro ao excluir:', e) }
}
</script>
