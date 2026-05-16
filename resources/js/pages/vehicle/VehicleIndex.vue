<template>
    <div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Veículos</h1>
                <p class="mt-1 text-sm text-gray-500">Gerencie os veículos cadastrados</p>
            </div>
            <button
                @click="openCreateModal"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Veículo
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="vehicle in vehicles"
                :key="vehicle.id"
                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
            >
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
                        <button @click="editVehicle(vehicle)" class="flex-1 px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">
                            Editar
                        </button>
                        <button @click="deleteVehicle(vehicle)" class="flex-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                            Excluir
                        </button>
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
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'

const vehicles = ref([])

onMounted(async () => {
    try {
        const response = await api.get('/api/vehicles')
        vehicles.value = response.data?.vehicles ?? []
    } catch (error) {
        console.error('Erro ao carregar veículos:', error)
    }
})

function openCreateModal() {
    alert('Funcionalidade de criar veículo - implementar modal/formulário')
}

function editVehicle(vehicle) {
    alert(`Editar veículo: ${vehicle.marca} ${vehicle.modelo}`)
}

function deleteVehicle(vehicle) {
    if (confirm(`Excluir veículo ${vehicle.marca} ${vehicle.modelo}?`)) {
        alert(`Veículo ${vehicle.marca} ${vehicle.modelo} excluído (simulado)`)
    }
}
</script>
