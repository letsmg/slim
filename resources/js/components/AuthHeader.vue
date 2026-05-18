<template>
    <header class="bg-gray-900 shadow-lg border-b border-gray-700">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Container flex com wrap: itens que não couberem vão para linha de baixo -->
            <div class="flex flex-wrap items-center py-2 sm:py-0">
                <!-- Logo (sempre na primeira linha, à esquerda) -->
                <div class="flex items-center h-12 sm:h-14 mr-auto">
                    <router-link to="/dashboard" class="text-xl font-bold text-emerald-400 hover:text-emerald-300 transition-colors whitespace-nowrap">
                        Slim App
                    </router-link>
                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-emerald-900 text-emerald-300 rounded-full hidden sm:inline">Painel</span>
                </div>

                <!-- Avatar + Sair (sempre na primeira linha, à direita em sm+) -->
                <div class="hidden sm:flex items-center space-x-3 ml-auto">
                    <div class="flex items-center space-x-2">
                        <div class="h-7 w-7 lg:h-8 lg:w-8 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs lg:text-sm font-bold text-white">{{ userInitials }}</span>
                        </div>
                        <div class="text-right hidden lg:block">
                            <p class="text-sm font-medium text-gray-200 leading-tight">{{ userName }}</p>
                            <p class="text-xs text-gray-400 leading-tight">{{ userEmail }}</p>
                        </div>
                    </div>
                    <button
                        @click="handleLogout"
                        class="p-1.5 lg:p-2 text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-md transition-colors"
                        title="Sair"
                    >
                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </div>

                <!-- Hamburguer (mobile < sm) -->
                <div class="flex items-center sm:hidden ml-auto">
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

                <!-- Itens do menu + Adm dropdown (segunda linha, wrap natural) -->
                <div class="hidden sm:flex sm:flex-wrap sm:items-center sm:w-full sm:pb-1.5 sm:gap-x-0.5 sm:gap-y-0.5">
                    <router-link
                        v-for="item in mainMenuItems"
                        :key="item.path"
                        :to="item.path"
                        class="inline-flex items-center px-2 py-1 text-xs lg:text-sm font-medium rounded-md transition-colors whitespace-nowrap"
                        :class="isActive(item.path)
                            ? 'text-emerald-400 bg-gray-800'
                            : 'text-gray-300 hover:text-emerald-400 hover:bg-gray-800'"
                    >
                        <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-html="item.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                        {{ item.label }}
                    </router-link>

                    <!-- Dropdown Adm -->
                    <div class="relative inline-flex" @mouseenter="admOpen = true" @mouseleave="admOpen = false">
                        <button
                            class="inline-flex items-center px-2 py-1 text-xs lg:text-sm font-medium rounded-md transition-colors whitespace-nowrap"
                            :class="isAdmActive
                                ? 'text-emerald-400 bg-gray-800'
                                : 'text-gray-300 hover:text-emerald-400 hover:bg-gray-800'"
                        >
                            <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                            Adm
                            <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            v-show="admOpen"
                            class="absolute left-0 top-full mt-0.5 w-48 bg-gray-800 rounded-lg shadow-xl border border-gray-700 py-1 z-50"
                        >
                            <router-link
                                v-for="item in admMenuItems"
                                :key="item.path"
                                :to="item.path"
                                @click="admOpen = false"
                                class="block px-4 py-2 text-sm text-gray-300 hover:text-emerald-400 hover:bg-gray-700 transition-colors"
                                :class="isActive(item.path) ? 'text-emerald-400 bg-gray-700' : ''"
                            >
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-html="item.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                </svg>
                                {{ item.label }}
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Menu Mobile (< sm) -->
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
                    v-for="item in mainMenuItems"
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

                <div class="border-t border-gray-700 pt-1 mt-1">
                    <p class="px-4 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Administração</p>
                    <router-link
                        v-for="item in admMenuItems"
                        :key="item.path"
                        :to="item.path"
                        @click="mobileOpen = false"
                        class="block pl-8 pr-3 py-2 text-base font-medium transition-colors"
                        :class="isActive(item.path)
                            ? 'text-emerald-400 bg-gray-800 border-l-4 border-emerald-400'
                            : 'text-gray-300 hover:text-emerald-400 hover:bg-gray-800 border-l-4 border-transparent'"
                    >
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-html="item.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                        {{ item.label }}
                    </router-link>
                </div>

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
const admOpen = ref(false)

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

const mainMenuItems = [
    {
        label: 'Dashboard',
        path: '/dashboard',
        icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    },
    {
        label: 'Veículos',
        path: '/veiculos',
        icon: 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
    },
    {
        label: 'Motoristas',
        path: '/motoristas',
        icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    },
    {
        label: 'Viagens',
        path: '/viagens',
        icon: 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
    },
    {
        label: 'Manutenções',
        path: '/manutencoes',
        icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
    },
    {
        label: 'Mecânicos',
        path: '/mecanicos',
        icon: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
    },
]

const admMenuItems = [
    {
        label: 'Usuários',
        path: '/usuarios',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    },
    {
        label: 'Relatórios',
        path: '/relatorios',
        icon: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    },
]

const isAdmActive = computed(() =>
    admMenuItems.some(item => route.path === item.path || route.path.startsWith(item.path + '/'))
)

function isActive(path) {
    return route.path === path || route.path.startsWith(path + '/')
}

function handleLogout() {
    sessionStorage.removeItem('auth_token')
    sessionStorage.removeItem('user_name')
    sessionStorage.removeItem('user_email')
    sessionStorage.removeItem('user_level')
    emit('logout')
}
</script>
