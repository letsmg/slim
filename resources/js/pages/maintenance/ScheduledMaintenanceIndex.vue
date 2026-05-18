<template>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Manutenções Programadas</h1>
        <p class="mt-1 text-sm text-gray-500">Gerencie as manutenções agendadas</p>
      </div>
      <button @click="openCreateModal" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nova Manutenção
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
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mecânico</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serviço</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Realizado</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pago</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="item in maintenances" :key="item.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ item.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.driver?.name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.vehicle?.marca || 'N/A' }} {{ item.vehicle?.modelo || '' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.mechanic?.name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-[200px] truncate" :title="item.service">{{ item.service }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.scheduled_date }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full" :class="item.completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                  {{ item.completed ? 'Sim' : 'Não' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full" :class="item.paid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                  {{ item.paid ? 'Sim' : 'Não' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="editMaintenance(item)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                <button @click="deleteMaintenance(item)" class="text-red-600 hover:text-red-900">Excluir</button>
              </td>
            </tr>
            <tr v-if="maintenances.length === 0">
              <td colspan="9" class="px-6 py-12 text-center text-gray-500 text-sm">Nenhuma manutenção encontrada.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <FormModal :open="modalOpen" :title="editing ? 'Editar Manutenção' : 'Nova Manutenção'" :form="form" entity="maintenances" @close="closeModal">
      <!-- Erros de validacao -->
      <div v-if="validationErrors" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
        <ul class="list-disc list-inside space-y-1">
          <li v-for="(msg, field) in validationErrors" :key="field">{{ msg }}</li>
        </ul>
      </div>
      <form class="space-y-4">
        <div class="grid grid-cols-3 gap-4">
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
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mecânico</label>
            <select v-model="form.mechanic_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
              <option value="">Selecione...</option>
              <option v-for="m in mechanics" :key="m.id" :value="m.id">{{ m.name }}</option>
            </select>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Serviço</label>
          <input v-model="form.service" type="text" required placeholder="Descrição do serviço" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Data Agendada</label>
            <input v-model="form.scheduled_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Horário</label>
            <input v-model="form.scheduled_time" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Contato</label>
          <input v-model="form.contact" type="text" placeholder="Telefone de contato" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
          <textarea v-model="form.observations" rows="2" placeholder="Observações adicionais" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
        </div>
        <div class="flex space-x-6">
          <div class="flex items-center">
            <input v-model="form.completed" type="checkbox" id="mnt-completed" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            <label for="mnt-completed" class="ml-2 text-sm text-gray-700">Serviço realizado</label>
          </div>
          <div class="flex items-center">
            <input v-model="form.paid" type="checkbox" id="mnt-paid" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            <label for="mnt-paid" class="ml-2 text-sm text-gray-700">Pagamento efetuado</label>
          </div>
        </div>
      </form>

      <template #footer>
        <button type="button" @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancelar</button>
        <button type="button" @click="saveMaintenance" :disabled="saving" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50">
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

const maintenances = ref([])
const drivers = ref([])
const vehicles = ref([])
const mechanics = ref([])
const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)
const validationErrors = ref(null)

const form = reactive({
  driver_id: '', vehicle_id: '', mechanic_id: '', scheduled_date: '',
  scheduled_time: '', contact: '', service: '', observations: '',
  completed: false, paid: false,
})

onMounted(async () => {
  try {
    const [resMnt, resDrivers, resVehicles, resMechanics] = await Promise.all([
      api.get('/api/scheduled-maintenances'),
      api.get('/api/drivers'),
      api.get('/api/vehicles'),
      api.get('/api/mechanics'),
    ])
    maintenances.value = resMnt.data?.scheduled_maintenances ?? []
    drivers.value = resDrivers.data?.drivers ?? []
    vehicles.value = resVehicles.data?.vehicles ?? []
    mechanics.value = resMechanics.data?.mechanics ?? []
  } catch (e) { console.error('Erro ao carregar dados:', e) }
})

function openCreateModal() {
  editing.value = null
  validationErrors.value = null
  Object.assign(form, { driver_id: '', vehicle_id: '', mechanic_id: '', scheduled_date: '', scheduled_time: '', contact: '', service: '', observations: '', completed: false, paid: false })
  modalOpen.value = true
}

function editMaintenance(item) {
  editing.value = item
  validationErrors.value = null
  Object.assign(form, {
    driver_id: item.driver_id || '', vehicle_id: item.vehicle_id || '', mechanic_id: item.mechanic_id || '',
    scheduled_date: item.scheduled_date || '', scheduled_time: item.scheduled_time?.substring(0, 5) || '',
    contact: item.contact || '', service: item.service, observations: item.observations || '',
    completed: !!item.completed, paid: !!item.paid,
  })
  modalOpen.value = true
}

function closeModal() { modalOpen.value = false; editing.value = null; validationErrors.value = null }

async function saveMaintenance() {
  saving.value = true
  validationErrors.value = null
  try {
    if (editing.value) {
      const res = await api.put(`/api/scheduled-maintenances/${editing.value.id}`, form)
      const idx = maintenances.value.findIndex(m => m.id === editing.value.id)
      if (idx !== -1) {
        // Mantem os relacionamentos originais e atualiza apenas os campos alterados
        maintenances.value[idx] = {
          ...maintenances.value[idx],
          ...res.data?.scheduled_maintenance,
          driver: maintenances.value[idx].driver,
          vehicle: maintenances.value[idx].vehicle,
          mechanic: maintenances.value[idx].mechanic,
        }
      }
    } else {
      const res = await api.post('/api/scheduled-maintenances', form)
      maintenances.value.push(res.data?.scheduled_maintenance || { ...form, id: Date.now() })
    }
    closeModal()
  } catch (e) {
    const errors = e.response?.data?.errors
    if (errors) {
      validationErrors.value = errors
    } else {
      alert('Erro ao salvar. Verifique os dados e tente novamente.')
    }
  }
  finally { saving.value = false }
}

async function deleteMaintenance(item) {
  if (!confirm(`Excluir manutenção #${item.id}?`)) return
  try {
    await api.delete(`/api/scheduled-maintenances/${item.id}`)
    maintenances.value = maintenances.value.filter(x => x.id !== item.id)
  } catch (e) { console.error('Erro ao excluir:', e) }
}
</script>
