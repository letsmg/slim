<template>
    <header class="bg-gray-900 shadow-lg border-b border-gray-700">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <router-link to="/dashboard" class="text-xl font-bold text-emerald-400 hover:text-emerald-300 transition-colors">
                        Slim App
                    </router-link>
                    <span class="ml-3 px-2 py-0.5 text-xs font-medium bg-emerald-900 text-emerald-300 rounded-full">Painel</span>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden sm:flex sm:space-x-8">
                    <router-link
                        v-for="item in menuItems"
                        :key="item.path"
                        :to="item.path"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
                        :class="isActive(item.path)
                            ? 'text-emerald-400 bg-gray-800'
                            : 'text-gray-300 hover:text-emerald-400 hover:bg-gray-800'"
                    >
                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-html="item.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                        {{ item.label }}
                    </router-link>
                </div>

                <!-- Avatar / Logout Desktop -->
                <div class="hidden sm:flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-emerald-500 flex items-center justify-center">
                            <span class="text-sm font-bold text-white">{{ userInitials }}</span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-200">{{ userName }}</p>
                            <p class="text-xs text-gray-400">{{ userEmail }}</p>
                        </div>
                    </div>
                    <button
                        @click="handleLogout"
                        class="p-2 text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-md transition-colors"
                        title="Sair"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </div>

                <!-- Botão Mobile -->
                <div class="flex items-center sm:hidden">
                    <button
                        @click="mobileOpen = !mobileOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-emerald-400 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500"
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
        <div v-show="mobileOpen" class="sm:hidden border-t border-gray-700">
            <div class="pt-2 pb-3 space-y-1">
                <div class="px-4 py-3 border-b border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-full bg-emerald-500 flex items-center justify-center">
                            <span class="text-sm font-bold text-white">{{ userInitials }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-200">{{ userName }}</p>
                            <p class="text-xs text-gray-400">{{ userEmail }}</p>
                        </div>
                    </div>
                </div>
                <router-link
                    v-for="item in menuItems"
                    :key="item.path"
                    :to="item.path"
                    @click="mobileOpen = false"
                    class="block pl-4 pr-3 py-2 text-base font-medium transition-colors"
                    :class="isActive(item.path)
                        ? 'text-emerald-400 bg-gray-800 border-l-4 border-emerald-400'
                        : 'text-gray-300 hover:text-emerald-400 hover:bg-gray-800 border-l-4 border-transparent'"
                >
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-html="item.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                    {{ item.label }}
                </router-link>
                <button
                    @click="handleLogout"
                    class="w-full text-left pl-4 pr-3 py-2 text-base font-medium text-red-400 hover:bg-gray-800 border-l-4 border-transparent transition-colors"
                >
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sair
                </button>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const mobileOpen = ref(false)

// Props para receber dados do usuário autenticado
const props = defineProps({
    user: {
        type: Object,
        default: () => ({
            name: 'Usuário',
            email: 'usuario@exemplo.com',
        }),
    },
})

const emit = defineEmits(['logout'])

const userName = computed(() => props.user?.name || 'Usuário')
const userEmail = computed(() => props.user?.email || '')
const userInitials = computed(() => {
    const name = userName.value
    const parts = name.split(' ')
    return parts.length > 1
        ? (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
        : name.substring(0, 2).toUpperCase()
})

const menuItems = [
    {
        label: 'Dashboard',
        path: '/dashboard',
        icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    },
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

function handleLogout() {
    emit('logout')
    router.push('/login')
}
</script>