<template>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Viagens</h1>
        <p class="mt-1 text-sm text-gray-500">Gerencie as viagens programadas</p>
      </div>
      <button @click="openCreateModal" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nova Viagem
      </button>
    </div>

    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motorista</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Veículo</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Origem</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destino</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Previsão</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="trip in trips" :key="trip.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ trip.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ trip.driver?.name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ trip.vehicle?.marca || 'N/A' }} {{ trip.vehicle?.modelo || '' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ trip.origin }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ trip.destination }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(trip.departure_forecast) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full" :class="statusClass(trip.status)">{{ statusLabel(trip.status) }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="editTrip(trip)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                <button @click="deleteTrip(trip)" class="text-red-600 hover:text-red-900">Excluir</button>
              </td>
            </tr>
            <tr v-if="trips.length === 0">
              <td colspan="8" class="px-6 py-12 text-center text-gray-500 text-sm">Nenhuma viagem encontrada.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <FormModal :open="modalOpen" :title="editing ? 'Editar Viagem' : 'Nova Viagem'" :form="form" entity="trips" @close="closeModal">
      <form class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Motorista</label>
            <select v-model="form.driver_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
              <option value="">Selecione...</option>
              <option v-for="d in drivers" :key="d.id" :value="d.id">{{ d.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Veículo</label>
            <select v-model="form.vehicle_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
              <option value="">Selecione...</option>
              <option v-for="v in vehicles" :key="v.id" :value="v.id">{{ v.marca }} {{ v.modelo }}</option>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Origem</label>
            <input v-model="form.origin" type="text" required placeholder="Cidade, UF" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
            <input v-model="form.destination" type="text" required placeholder="Cidade, UF" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Previsão Saída</label>
            <input v-model="form.departure_forecast" type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Previsão Chegada</label>
            <input v-model="form.arrival_forecast" type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select v-model="form.status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="scheduled">Agendada</option>
            <option value="in_progress">Em Andamento</option>
            <option value="completed">Concluída</option>
            <option value="cancelled">Cancelada</option>
          </select>
        </div>
      </form>

      <template #footer>
        <button type="button" @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancelar</button>
        <button type="button" @click="saveTrip" :disabled="saving" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50">
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

const trips = ref([])
const drivers = ref([])
const vehicles = ref([])
const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)

const form = reactive({
  driver_id: '', vehicle_id: '', origin: '', destination: '',
  departure_forecast: '', arrival_forecast: '', status: 'scheduled',
})

onMounted(async () => {
  try {
    const [resTrips, resDrivers, resVehicles] = await Promise.all([
      api.get('/api/trips'),
      api.get('/api/drivers'),
      api.get('/api/vehicles'),
    ])
    trips.value = resTrips.data?.trips ?? []
    drivers.value = resDrivers.data?.drivers ?? []
    vehicles.value = resVehicles.data?.vehicles ?? []
  } catch (e) { console.error('Erro ao carregar dados:', e) }
})

function openCreateModal() {
  editing.value = null
  Object.assign(form, { driver_id: '', vehicle_id: '', origin: '', destination: '', departure_forecast: '', arrival_forecast: '', status: 'scheduled' })
  modalOpen.value = true
}

function editTrip(t) {
  editing.value = t
  Object.assign(form, {
    driver_id: t.driver_id || '', vehicle_id: t.vehicle_id || '',
    origin: t.origin, destination: t.destination,
    departure_forecast: t.departure_forecast ? t.departure_forecast.substring(0, 16) : '',
    arrival_forecast: t.arrival_forecast ? t.arrival_forecast.substring(0, 16) : '',
    status: t.status,
  })
  modalOpen.value = true
}

function closeModal() { modalOpen.value = false; editing.value = null }

async function saveTrip() {
  saving.value = true
  try {
    if (editing.value) {
      await api.put(`/api/trips/${editing.value.id}`, form)
      const idx = trips.value.findIndex(t => t.id === editing.value.id)
      if (idx !== -1) trips.value[idx] = { ...editing.value, ...form }
    } else {
      const res = await api.post('/api/trips', form)
      trips.value.push(res.data?.trip || { ...form, id: Date.now() })
    }
    closeModal()
  } catch (e) { console.error('Erro ao salvar:', e); alert('Erro ao salvar.') }
  finally { saving.value = false }
}

async function deleteTrip(t) {
  if (!confirm(`Excluir viagem #${t.id}?`)) return
  try {
    await api.delete(`/api/trips/${t.id}`)
    trips.value = trips.value.filter(x => x.id !== t.id)
  } catch (e) { console.error('Erro ao excluir:', e) }
}

function formatDate(d) { return d ? new Date(d).toLocaleString('pt-BR') : '-' }

function statusClass(s) {
  const map = { scheduled: 'bg-yellow-100 text-yellow-800', in_progress: 'bg-blue-100 text-blue-800', completed: 'bg-green-100 text-green-800', cancelled: 'bg-red-100 text-red-800' }
  return map[s] || 'bg-gray-100 text-gray-800'
}

function statusLabel(s) {
  const map = { scheduled: 'Agendada', in_progress: 'Em Andamento', completed: 'Concluída', cancelled: 'Cancelada' }
  return map[s] || s
}
</script>
