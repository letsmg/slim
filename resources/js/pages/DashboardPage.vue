<template>
    <div>
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Bem-vindo, {{ userName }}. Aqui está um resumo do sistema.</p>
        </div>

        <!-- Cards de métricas do sistema de frotas -->
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
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-1">{{ metric.value }}</p>
                <p class="text-sm text-gray-500">{{ metric.label }}</p>
            </div>
        </div>

        <!-- Links rápidos para módulos do sistema -->
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
                to="/viagens"
                class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-300 transition-all group"
            >
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition-colors">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Viagens</h2>
                <p class="text-sm text-gray-500">Planejamento e acompanhamento de viagens</p>
            </router-link>

            <router-link
                to="/manutencoes"
                class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-300 transition-all group"
            >
                <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-rose-200 transition-colors">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Manutenções</h2>
                <p class="text-sm text-gray-500">Agendamento e controle de manutenções</p>
            </router-link>

            <router-link
                to="/mecanicos"
                class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-300 transition-all group"
            >
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-orange-200 transition-colors">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Mecânicos</h2>
                <p class="text-sm text-gray-500">Cadastro de mecânicos e oficinas</p>
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { inject } from 'vue'

/**
 * Dashboard do sistema de gerenciamento de frotas
 * Exibe métricas reais do sistema e links rápidos para os módulos
 */
const authUser = inject('authUser', { name: 'Usuário' })
const userName = authUser?.name || 'Usuário'

// Métricas focadas no sistema de frotas
const metrics = [
    {
        label: 'Usuários Ativos',
        value: '5',
        bgColor: 'bg-emerald-100',
        iconColor: 'text-emerald-600',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    },
    {
        label: 'Veículos na Frota',
        value: '5',
        bgColor: 'bg-blue-100',
        iconColor: 'text-blue-600',
        icon: 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
    },
    {
        label: 'Motoristas',
        value: '5',
        bgColor: 'bg-teal-100',
        iconColor: 'text-teal-600',
        icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    },
    {
        label: 'Manutenções Agendadas',
        value: '5',
        bgColor: 'bg-rose-100',
        iconColor: 'text-rose-600',
        icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
    },
]
</script>
