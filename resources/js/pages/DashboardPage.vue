<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Bem-vindo, {{ userName }}. Aqui está um resumo do sistema.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div v-for="metric in metrics" :key="metric.label"
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow"
            >
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" :class="metric.bgColor">
                        <svg class="w-6 h-6" :class="metric.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-html="metric.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium" :class="metric.change >= 0 ? 'text-green-600' : 'text-red-600'">
                        {{ metric.change >= 0 ? '↑' : '↓' }} {{ Math.abs(metric.change) }}%
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-1">{{ metric.value }}</p>
                <p class="text-sm text-gray-500">{{ metric.label }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <router-link
                to="/usuarios"
                class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-300 transition-all group"
            >
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-emerald-200 transition-colors">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Usuários</h2>
                <p class="text-sm text-gray-500">Gerencie os usuários do sistema</p>
            </router-link>

            <router-link
                to="/veiculos"
                class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-300 transition-all group"
            >
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Veículos</h2>
                <p class="text-sm text-gray-500">Gerencie os veículos da frota</p>
            </router-link>

            <router-link
                to="/motoristas"
                class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-300 transition-all group"
            >
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-teal-200 transition-colors">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Motoristas</h2>
                <p class="text-sm text-gray-500">Gerencie os motoristas cadastrados</p>
            </router-link>

            <router-link
                to="/produtos"
                class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-300 transition-all group"
            >
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-colors">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Produtos</h2>
                <p class="text-sm text-gray-500">Controle de produtos e estoque</p>
            </router-link>

            <router-link
                to="/relatorios"
                class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-300 transition-all group"
            >
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-amber-200 transition-colors">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Relatórios</h2>
                <p class="text-sm text-gray-500">Métricas e estatísticas</p>
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { inject } from 'vue'

const authUser = inject('authUser', { name: 'Usuário' })
const userName = authUser?.name || 'Usuário'

const metrics = [
    {
        label: 'Usuários Ativos',
        value: '1.234',
        change: 12,
        bgColor: 'bg-emerald-100',
        iconColor: 'text-emerald-600',
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
        bgColor: 'bg-blue-100',
        iconColor: 'text-blue-600',
        icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    },
    {
        label: 'Ticket Médio',
        value: 'R$ 157,00',
        change: 5,
        bgColor: 'bg-amber-100',
        iconColor: 'text-amber-600',
        icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    },
]
</script>
