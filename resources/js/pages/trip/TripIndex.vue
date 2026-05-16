<template>
    <div>
        <!-- Cabeçalho -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Viagens</h1>
                <p class="mt-1 text-sm text-gray-500">Gerencie as viagens programadas</p>
            </div>
            <button
                @click="openCreateModal"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Viagem
            </button>
        </div>

        <!-- Tabela de Viagens -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motorista</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Veículo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Previsão Saída</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Previsão Chegada</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="trip in trips" :key="trip.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ trip.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ trip.driver?.name || 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ trip.vehicle?.plate || 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(trip.departure_forecast) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(trip.arrival_forecast) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full"
                                    :class="statusClass(trip.status)"
                                >
                                    {{ statusLabel(trip.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="editTrip(trip)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                <button @click="deleteTrip(trip)" class="text-red-600 hover:text-red-900">Excluir</button>
                            </td>
                        </tr>
                        <tr v-if="trips.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <p class="text-gray-500 text-sm">Nenhuma viagem encontrada.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'

/**
 * Lista de viagens carregada da API
 */
const trips = ref([])

/**
 * Carrega a lista de viagens ao montar o componente
 */
onMounted(async () => {
    try {
        const response = await api.get('/api/trips')
        trips.value = response.data?.trips ?? []
    } catch (error) {
        console.error('Erro ao carregar viagens:', error)
    }
})

/**
 * Formata data para exibição
 */
function formatDate(dateStr) {
    if (!dateStr) return '-'
    const date = new Date(dateStr)
    return date.toLocaleString('pt-BR')
}

/**
 * Retorna classe CSS baseada no status
 */
function statusClass(status) {
    const map = {
        scheduled: 'bg-yellow-100 text-yellow-800',
        in_progress: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    }
    return map[status] || 'bg-gray-100 text-gray-800'
}

/**
 * Retorna label baseada no status
 */
function statusLabel(status) {
    const map = {
        scheduled: 'Agendada',
        in_progress: 'Em Andamento',
        completed: 'Concluída',
        cancelled: 'Cancelada',
    }
    return map[status] || status
}

function openCreateModal() {
    alert('Funcionalidade de criar viagem - implementar modal/formulário')
}

function editTrip(trip) {
    alert(`Editar viagem #${trip.id}`)
}

function deleteTrip(trip) {
    if (confirm(`Excluir viagem #${trip.id}?`)) {
        alert(`Viagem #${trip.id} excluída (simulado)`)
    }
}
</script>
