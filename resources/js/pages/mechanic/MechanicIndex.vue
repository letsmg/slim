<template>
    <div>
        <!-- Cabeçalho -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mecânicos</h1>
                <p class="mt-1 text-sm text-gray-500">Gerencie os mecânicos cadastrados</p>
            </div>
            <button
                @click="openCreateModal"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Mecânico
            </button>
        </div>

        <!-- Tabela de Mecânicos -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celular 1</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celular 2</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="mechanic in mechanics" :key="mechanic.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ mechanic.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ mechanic.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ mechanic.document }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ mechanic.phone1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ mechanic.phone2 || '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="editMechanic(mechanic)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                <button @click="deleteMechanic(mechanic)" class="text-red-600 hover:text-red-900">Excluir</button>
                            </td>
                        </tr>
                        <tr v-if="mechanics.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-gray-500 text-sm">Nenhum mecânico encontrado.</p>
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
 * Lista de mecânicos carregada da API
 */
const mechanics = ref([])

/**
 * Carrega a lista de mecânicos ao montar o componente
 */
onMounted(async () => {
    try {
        const response = await api.get('/api/mechanics')
        mechanics.value = response.data?.mechanics ?? []
    } catch (error) {
        console.error('Erro ao carregar mecânicos:', error)
    }
})

function openCreateModal() {
    alert('Funcionalidade de criar mecânico - implementar modal/formulário')
}

function editMechanic(mechanic) {
    alert(`Editar mecânico: ${mechanic.name}`)
}

function deleteMechanic(mechanic) {
    if (confirm(`Excluir mecânico ${mechanic.name}?`)) {
        alert(`Mecânico ${mechanic.name} excluído (simulado)`)
    }
}
</script>
