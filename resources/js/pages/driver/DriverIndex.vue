<template>
    <div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Motoristas</h1>
                <p class="mt-1 text-sm text-gray-500">Gerencie os motoristas cadastrados</p>
            </div>
            <button
                @click="openCreateModal"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Motorista
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="driver in drivers"
                :key="driver.id"
                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
            >
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
                        <p v-if="driver.rg"><span class="font-medium">RG:</span> {{ driver.rg }}</p>
                        <p v-if="driver.endereco"><span class="font-medium">Endereço:</span> {{ driver.endereco }}</p>
                        <p v-if="driver.cidade || driver.estado">
                            <span class="font-medium">Cidade/UF:</span> {{ driver.cidade }}/{{ driver.estado }}
                        </p>
                        <div class="flex space-x-4 mt-2">
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full"
                                :class="driver.toxicologico
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-red-100 text-red-800'"
                            >
                                {{ driver.toxicologico ? 'Toxicológico OK' : 'Toxicológico Pendente' }}
                            </span>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full"
                                :class="!driver.pendencias
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-red-100 text-red-800'"
                            >
                                {{ driver.pendencias ? 'Com Pendências' : 'Sem Pendências' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <button @click="editDriver(driver)" class="flex-1 px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">
                            Editar
                        </button>
                        <button @click="deleteDriver(driver)" class="flex-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                            Excluir
                        </button>
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
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'

const drivers = ref([])

onMounted(async () => {
    try {
        const response = await api.get('/api/drivers')
        drivers.value = response.data?.drivers ?? []
    } catch (error) {
        console.error('Erro ao carregar motoristas:', error)
    }
})

function openCreateModal() {
    alert('Funcionalidade de criar motorista - implementar modal/formulário')
}

function editDriver(driver) {
    alert(`Editar motorista: ${driver.nome}`)
}

function deleteDriver(driver) {
    if (confirm(`Excluir motorista ${driver.nome}?`)) {
        alert(`Motorista ${driver.nome} excluído (simulado)`)
    }
}
</script>
