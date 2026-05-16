<template>
    <header class="bg-white shadow-sm border-b border-gray-200">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <router-link to="/" class="text-xl font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                        Slim App
                    </router-link>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden sm:flex sm:space-x-8">
                    <router-link
                        v-for="item in menuItems"
                        :key="item.path"
                        :to="item.path"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
                        :class="isActive(item.path)
                            ? 'text-indigo-600 bg-indigo-50'
                            : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50'"
                    >
                        <!-- Icon via inline SVG -->
                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-html="item.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                        {{ item.label }}
                    </router-link>
                </div>

                <!-- Botão Mobile -->
                <div class="flex items-center sm:hidden">
                    <button
                        @click="mobileOpen = !mobileOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-indigo-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        aria-label="Abrir menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Menu Mobile -->
        <div v-show="mobileOpen" class="sm:hidden border-t border-gray-200">
            <div class="pt-2 pb-3 space-y-1">
                <router-link
                    v-for="item in menuItems"
                    :key="item.path"
                    :to="item.path"
                    @click="mobileOpen = false"
                    class="block pl-4 pr-3 py-2 text-base font-medium transition-colors"
                    :class="isActive(item.path)
                        ? 'text-indigo-600 bg-indigo-50 border-l-4 border-indigo-600'
                        : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50 border-l-4 border-transparent'"
                >
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-html="item.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                    {{ item.label }}
                </router-link>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const mobileOpen = ref(false)

const menuItems = [
    {
        label: 'Usuários',
        path: '/usuarios',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    },
    {
        label: 'Produtos',
        path: '/produtos',
        icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
    },
    {
        label: 'Relatórios',
        path: '/relatorios',
        icon: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    },
]

function isActive(path) {
    return route.path === path || route.path.startsWith(path + '/')
}
</script>