<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Cabeçalho -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Relatórios</h1>
            <p class="mt-1 text-sm text-gray-500">Visualize métricas e estatísticas do sistema</p>
        </div>

        <!-- Cards de Métricas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div
                v-for="metric in metrics"
                :key="metric.label"
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
            >
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 rounded-lg flex items-center justify-center"
                        :class="metric.bgColor"
                    >
                        <svg class="w-6 h-6" :class="metric.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-html="metric.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-1">{{ metric.value }}</p>
                <p class="text-sm text-gray-500">{{ metric.label }}</p>
                <p v-if="metric.change" class="mt-2 text-xs" :class="metric.change > 0 ? 'text-green-600' : 'text-red-600'">
                    {{ metric.change > 0 ? '↑' : '↓' }} {{ Math.abs(metric.change) }}% em relação ao mês anterior
                </p>
            </div>
        </div>

        <!-- Tabela de Relatórios Detalhados -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Relatórios Detalhados</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Relatório</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última Atualização</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="report in reports" :key="report.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ report.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.period }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.total }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.updatedAt }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="viewReport(report)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Visualizar
                                </button>
                                <button @click="downloadReport(report)" class="text-gray-600 hover:text-gray-900">
                                    Download
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

/**
 * Métricas do dashboard
 */
const metrics = ref([
    {
        label: 'Usuários Ativos',
        value: '1.234',
        change: 12,
        bgColor: 'bg-blue-100',
        iconColor: 'text-blue-600',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    },
    {
        label: 'Produtos Cadastrados',
        value: '567',
        change: -3,
        bgColor: 'bg-purple-100',
        iconColor: 'text-purple-600',
        icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
    },
    {
        label: 'Vendas no Mês',
        value: 'R$ 89.234',
        change: 8,
        bgColor: 'bg-green-100',
        iconColor: 'text-green-600',
        icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    },
    {
        label: 'Ticket Médio',
        value: 'R$ 157,00',
        change: 5,
        bgColor: 'bg-yellow-100',
        iconColor: 'text-yellow-600',
        icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    },
])

/**
 * Lista de relatórios detalhados
 */
const reports = ref([
    {
        id: 1,
        name: 'Relatório de Usuários',
        period: 'Abril 2026',
        total: '1.500 usuários',
        updatedAt: '15/04/2026 10:30',
    },
    {
        id: 2,
        name: 'Relatório de Produtos',
        period: 'Abril 2026',
        total: '230 produtos',
        updatedAt: '15/04/2026 10:30',
    },
    {
        id: 3,
        name: 'Relatório Financeiro',
        period: '1º Trimestre 2026',
        total: 'R$ 250.000,00',
        updatedAt: '01/04/2026 08:00',
    },
    {
        id: 4,
        name: 'Relatório de Acessos',
        period: 'Abril 2026',
        total: '45.000 visitas',
        updatedAt: '15/04/2026 10:30',
    },
])

function viewReport(report) {
    alert(`Visualizar relatório: ${report.name}`)
}

function downloadReport(report) {
    alert(`Download do relatório: ${report.name}`)
}
</script>