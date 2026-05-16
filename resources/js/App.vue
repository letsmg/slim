<template>
    <div class="min-h-screen flex flex-col">
        <!-- Layout Público -->
        <template v-if="!isAuthenticated">
            <Header />
            <main class="flex-1">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <router-view />
                </div>
            </main>
            <Footer />
        </template>

        <!-- Layout Autenticado -->
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
import { ref, computed, provide, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import AuthHeader from './components/AuthHeader.vue'
import AuthFooter from './components/AuthFooter.vue'

const route = useRoute()
const router = useRouter()

// Estado de autenticação (será gerenciado futuramente por Pinia store)
const isAuthenticated = ref(false)
const authUser = ref({
    name: 'Luiz Eduardo',
    email: 'luiz@exemplo.com',
})

// Disponibiliza para componentes filhos via provide/inject
provide('authUser', authUser)
provide('isAuthenticated', isAuthenticated)

// Verifica autenticação baseada nas rotas que requerem auth
const authRoutes = ['dashboard', 'users.index', 'products.index', 'reports.index']
// Atualiza estado de autenticação conforme rota atual
// Em produção, isso viria de um guard de navegação + store Pinia
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
