<template>
    <div>
        <!-- Cabeçalho -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manutenções Programadas</h1>
                <p class="mt-1 text-sm text-gray-500">Gerencie as manutenções agendadas</p>
            </div>
            <button
                @click="openCreateModal"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Manutenção
            </button>
        </div>

        <!-- Tabela de Manutenções -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motorista</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Veículo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mecânico</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Realizado</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in maintenances" :key="item.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ item.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.driver?.name || 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.vehicle?.plate || 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.mechanic?.name || 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.scheduled_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full"
                                    :class="item.completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                >
                                    {{ item.completed ? 'Sim' : 'Não' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="editMaintenance(item)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                <button @click="deleteMaintenance(item)" class="text-red-600 hover:text-red-900">Excluir</button>
                            </td>
                        </tr>
                        <tr v-if="maintenances.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <p class="text-gray-500 text-sm">Nenhuma manutenção encontrada.</p>
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
 * Lista de manutenções carregada da API
 */
const maintenances = ref([])

/**
 * Carrega a lista de manutenções ao montar o componente
 */
onMounted(async () => {
    try {
        const response = await api.get('/api/scheduled-maintenances')
        maintenances.value = response.data?.scheduled_maintenances ?? []
    } catch (error) {
        console.error('Erro ao carregar manutenções:', error)
    }
})

function openCreateModal() {
    alert('Funcionalidade de criar manutenção - implementar modal/formulário')
}

function editMaintenance(item) {
    alert(`Editar manutenção #${item.id}`)
}

function deleteMaintenance(item) {
    if (confirm(`Excluir manutenção #${item.id}?`)) {
        alert(`Manutenção #${item.id} excluída (simulado)`)
    }
}
</script>
