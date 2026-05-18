<template>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Mecânicos</h1>
        <p class="mt-1 text-sm text-gray-500">Gerencie os mecânicos e oficinas cadastrados</p>
      </div>
      <button @click="openCreateModal" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Novo Mecânico
      </button>
    </div>

    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Endereço</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Celular 1</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Celular 2</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="mechanic in mechanics" :key="mechanic.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ mechanic.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ mechanic.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ mechanic.address }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ mechanic.document }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ mechanic.phone1 }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ mechanic.phone2 || '-' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="editMechanic(mechanic)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                <button @click="deleteMechanic(mechanic)" class="text-red-600 hover:text-red-900">Excluir</button>
              </td>
            </tr>
            <tr v-if="mechanics.length === 0">
              <td colspan="7" class="px-6 py-12 text-center text-gray-500 text-sm">Nenhum mecânico encontrado.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <FormModal :open="modalOpen" :title="editing ? 'Editar Mecânico' : 'Novo Mecânico'" :form="form" entity="mechanics" @close="closeModal">
      <form class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Oficina</label>
          <input v-model="form.name" type="text" required placeholder="Nome da oficina" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
          <input v-model="form.address" type="text" placeholder="Rua, número, bairro" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ</label>
          <input v-model="form.document" type="text" placeholder="00.000.000/0001-00" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Celular 1</label>
            <input v-model="form.phone1" type="text" placeholder="(11) 98888-0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Celular 2</label>
            <input v-model="form.phone2" type="text" placeholder="(11) 97777-0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
      </form>

      <template #footer>
        <button type="button" @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancelar</button>
        <button type="button" @click="saveMechanic" :disabled="saving" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50">
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

const mechanics = ref([])
const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)

const form = reactive({ name: '', address: '', document: '', phone1: '', phone2: '' })

onMounted(async () => {
  try {
    const res = await api.get('/api/mechanics')
    mechanics.value = res.data?.mechanics ?? []
  } catch (e) { console.error('Erro ao carregar mecânicos:', e) }
})

function openCreateModal() {
  editing.value = null
  Object.assign(form, { name: '', address: '', document: '', phone1: '', phone2: '' })
  modalOpen.value = true
}

function editMechanic(m) {
  editing.value = m
  Object.assign(form, { name: m.name, address: m.address || '', document: m.document, phone1: m.phone1, phone2: m.phone2 || '' })
  modalOpen.value = true
}

function closeModal() { modalOpen.value = false; editing.value = null }

async function saveMechanic() {
  saving.value = true
  try {
    if (editing.value) {
      await api.put(`/api/mechanics/${editing.value.id}`, form)
      const idx = mechanics.value.findIndex(m => m.id === editing.value.id)
      if (idx !== -1) mechanics.value[idx] = { ...editing.value, ...form }
    } else {
      const res = await api.post('/api/mechanics', form)
      mechanics.value.push(res.data?.mechanic || { ...form, id: Date.now() })
    }
    closeModal()
  } catch (e) { console.error('Erro ao salvar:', e); alert('Erro ao salvar.') }
  finally { saving.value = false }
}

async function deleteMechanic(m) {
  if (!confirm(`Excluir mecânico ${m.name}?`)) return
  try {
    await api.delete(`/api/mechanics/${m.id}`)
    mechanics.value = mechanics.value.filter(x => x.id !== m.id)
  } catch (e) { console.error('Erro ao excluir:', e) }
}
</script>
