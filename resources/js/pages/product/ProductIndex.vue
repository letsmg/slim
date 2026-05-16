<template>
    <div>
        <!-- Cabeçalho -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Produtos</h1>
                <p class="mt-1 text-sm text-gray-500">Gerencie os produtos cadastrados</p>
            </div>
            <button
                @click="openCreateModal"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Produto
            </button>
        </div>

        <!-- Grid de Produtos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="product in products"
                :key="product.id"
                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
            >
                <div class="h-48 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                    <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ product.name }}</h3>
                    <p class="text-sm text-gray-500 mb-3">{{ product.description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-indigo-600">
                            {{ formatCurrency(product.price) }}
                        </span>
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full"
                            :class="product.active
                                ? 'bg-green-100 text-green-800'
                                : 'bg-gray-100 text-gray-800'"
                        >
                            {{ product.active ? 'Disponível' : 'Indisponível' }}
                        </span>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <button @click="editProduct(product)" class="flex-1 px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">
                            Editar
                        </button>
                        <button @click="deleteProduct(product)" class="flex-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado vazio -->
        <div v-if="products.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <p class="mt-4 text-gray-500 text-sm">Nenhum produto encontrado.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'

/**
 * Lista de produtos carregada da API
 */
const products = ref([])

/**
 * Carrega a lista de produtos ao montar o componente
 */
onMounted(async () => {
    try {
        const response = await api.get('/api/products')
        products.value = response.data?.products ?? []
    } catch (error) {
        console.error('Erro ao carregar produtos:', error)
    }
})

/**
 * Formata valor monetário no padrão BRL
 */
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(value ?? 0)
}

function openCreateModal() {
    alert('Funcionalidade de criar produto - implementar modal/formulário')
}

function editProduct(product) {
    alert(`Editar produto: ${product.name}`)
}

function deleteProduct(product) {
    if (confirm(`Excluir produto ${product.name}?`)) {
        alert(`Produto ${product.name} excluído (simulado)`)
    }
}
</script>