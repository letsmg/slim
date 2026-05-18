<template>
    <div class="min-h-screen flex flex-col">
        <!-- Quando não autenticado: renderiza a página diretamente (ex: HomePage já tem layout próprio) -->
        <template v-if="!isAuthenticated">
            <router-view />
        </template>
        <!-- Quando autenticado: layout com AuthHeader e AuthFooter -->
        <template v-else>
            <AuthHeader :user="authUser" @logout="handleLogout" />
            <main class="flex-1 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <router-view />
                </div>
            </main>
            <AuthFooter />
        </template>
    </div>
</template>

<script setup>
import { ref, provide, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AuthHeader from '@components/AuthHeader.vue'
import AuthFooter from '@components/AuthFooter.vue'

const route = useRoute()
const router = useRouter()

const isAuthenticated = ref(false)
const authUser = ref({
    name: sessionStorage.getItem('user_name') || 'Usuário',
    email: sessionStorage.getItem('user_email') || '',
    level: sessionStorage.getItem('user_level') || '',
})

provide('authUser', authUser)
provide('isAuthenticated', isAuthenticated)

const authRoutes = ['dashboard', 'users.index', 'products.index', 'reports.index', 'vehicles.index', 'drivers.index', 'mechanics.index', 'trips.index', 'scheduled-maintenances.index']

watch(
    () => route.name,
    (name) => {
        isAuthenticated.value = authRoutes.includes(name)
        // Atualiza dados do usuário do sessionStorage sempre que navega
        if (isAuthenticated.value) {
            authUser.value = {
                name: sessionStorage.getItem('user_name') || 'Usuário',
                email: sessionStorage.getItem('user_email') || '',
            }
        }
    },
    { immediate: true }
)

function handleLogout() {
    isAuthenticated.value = false
    authUser.value = { name: '', email: '' }
    router.push('/')
}
</script>
