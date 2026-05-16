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
                <router-view />
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
    name: 'Luiz Eduardo',
    email: 'luiz@exemplo.com',
})

provide('authUser', authUser)
provide('isAuthenticated', isAuthenticated)

const authRoutes = ['dashboard', 'users.index', 'products.index', 'reports.index', 'vehicles.index', 'drivers.index', 'mechanics.index', 'trips.index', 'scheduled-maintenances.index']

watch(
    () => route.name,
    (name) => {
        isAuthenticated.value = authRoutes.includes(name)
    },
    { immediate: true }
)

function handleLogout() {
    isAuthenticated.value = false
    authUser.value = { name: '', email: '' }
    router.push('/')
}
</script>
