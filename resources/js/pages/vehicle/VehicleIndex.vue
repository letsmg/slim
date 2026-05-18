<template>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Veículos</h1>
        <p class="mt-1 text-sm text-gray-500">Gerencie os veículos cadastrados</p>
      </div>
      <button @click="openCreateModal" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Novo Veículo
      </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="vehicle in vehicles" :key="vehicle.id" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="h-48 bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center">
          <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
          </svg>
        </div>
        <div class="p-5">
          <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ vehicle.marca }} {{ vehicle.modelo }}</h3>
          <div class="space-y-2 text-sm text-gray-600">
            <p><span class="font-medium">CRLV:</span> {{ vehicle.crlv }}</p>
            <p><span class="font-medium">Eixos:</span> {{ vehicle.eixos }}</p>
            <p><span class="font-medium">Combustível:</span> {{ vehicle.tipo_combustivel }}</p>
            <p v-if="vehicle.dt_ultima_revisao"><span class="font-medium">Últ. Revisão:</span> {{ vehicle.dt_ultima_revisao }}</p>
            <p v-if="vehicle.dt_proxima_revisao"><span class="font-medium">Próx. Revisão:</span> {{ vehicle.dt_proxima_revisao }}</p>
            <p v-if="vehicle.dt_compra"><span class="font-medium">Compra:</span> {{ vehicle.dt_compra }}</p>
          </div>
          <div class="mt-4 flex space-x-2">
            <button @click="editVehicle(vehicle)" class="flex-1 px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">Editar</button>
            <button @click="deleteVehicle(vehicle)" class="flex-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">Excluir</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="vehicles.length === 0" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
      </svg>
      <p class="mt-4 text-gray-500 text-sm">Nenhum veículo encontrado.</p>
    </div>

    <!-- Modal -->
    <FormModal :open="modalOpen" :title="editing ? 'Editar Veículo' : 'Novo Veículo'" :form="form" entity="vehicles" @close="closeModal">
      <form class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
            <input v-model="form.marca" type="text" required placeholder="Ex: Fiat" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
            <input v-model="form.modelo" type="text" required placeholder="Ex: Toro" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">CRLV</label>
            <input v-model="form.crlv" type="text" placeholder="CRLV-2026-001" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Eixos</label>
            <input v-model="form.eixos" type="number" min="2" max="9" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Combustível</label>
            <select v-model="form.tipo_combustivel" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
              <option value="Diesel">Diesel</option>
              <option value="Gasolina">Gasolina</option>
              <option value="Etanol">Etanol</option>
              <option value="Flex">Flex</option>
              <option value="Elétrico">Elétrico</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Data de Compra</label>
            <input v-model="form.dt_compra" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Última Revisão</label>
            <input v-model="form.dt_ultima_revisao" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Próxima Revisão</label>
            <input v-model="form.dt_proxima_revisao" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
      </form>

      <template #footer>
        <button type="button" @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancelar</button>
        <button type="button" @click="saveVehicle" :disabled="saving" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50">
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

const vehicles = ref([])
const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)

const form = reactive({
  marca: '', modelo: '', eixos: 2, crlv: '', tipo_combustivel: 'Diesel',
  dt_ultima_revisao: '', dt_proxima_revisao: '', dt_compra: '',
})

onMounted(async () => {
  try {
    const res = await api.get('/api/vehicles')
    vehicles.value = res.data?.vehicles ?? []
  } catch (e) {
    console.error('Erro ao carregar veículos:', e)
  }
})

function openCreateModal() {
  editing.value = null
  Object.assign(form, { marca: '', modelo: '', eixos: 2, crlv: '', tipo_combustivel: 'Diesel', dt_ultima_revisao: '', dt_proxima_revisao: '', dt_compra: '' })
  modalOpen.value = true
}

function editVehicle(v) {
  editing.value = v
  Object.assign(form, {
    marca: v.marca, modelo: v.modelo, eixos: v.eixos, crlv: v.crlv,
    tipo_combustivel: v.tipo_combustivel, dt_ultima_revisao: v.dt_ultima_revisao || '',
    dt_proxima_revisao: v.dt_proxima_revisao || '', dt_compra: v.dt_compra || '',
  })
  modalOpen.value = true
}

function closeModal() { modalOpen.value = false; editing.value = null }

async function saveVehicle() {
  saving.value = true
  try {
    if (editing.value) {
      await api.put(`/api/vehicles/${editing.value.id}`, form)
      const idx = vehicles.value.findIndex(v => v.id === editing.value.id)
      if (idx !== -1) vehicles.value[idx] = { ...editing.value, ...form }
    } else {
      const res = await api.post('/api/vehicles', form)
      vehicles.value.push(res.data?.vehicle || { ...form, id: Date.now() })
    }
    closeModal()
  } catch (e) {
    console.error('Erro ao salvar:', e)
    alert('Erro ao salvar. Verifique o console.')
  } finally { saving.value = false }
}

async function deleteVehicle(v) {
  if (!confirm(`Excluir veículo ${v.marca} ${v.modelo}?`)) return
  try {
    await api.delete(`/api/vehicles/${v.id}`)
    vehicles.value = vehicles.value.filter(x => x.id !== v.id)
  } catch (e) { console.error('Erro ao excluir:', e) }
}
</script>
