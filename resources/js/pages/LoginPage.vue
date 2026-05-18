<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <router-link to="/" class="inline-flex items-center space-x-3">
          <img :src="imgUrl('logo2.svg')" alt="Slim App" class="h-10 w-auto drop-shadow-lg" />
          <span class="text-2xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">Slim App</span>
        </router-link>
      </div>

      <!-- Card de Login -->
      <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-8 backdrop-blur-sm">
        <h2 class="text-2xl font-bold text-white text-center mb-2">Entrar</h2>
        <p class="text-slate-400 text-center mb-8 text-sm">Acesse sua conta para gerenciar a frota</p>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">E-mail</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              placeholder="seu@email.com"
              class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
            />
          </div>

          <!-- Senha -->
          <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Senha</label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              placeholder="Sua senha"
              class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
            />
          </div>

          <!-- Erro -->
          <div v-if="error" class="bg-red-900/30 border border-red-700/50 text-red-300 px-4 py-2.5 rounded-lg text-sm">
            {{ error }}
          </div>

          <!-- Botão -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-lg hover:from-emerald-500 hover:to-teal-500 transition-all duration-200 shadow-lg shadow-emerald-600/25 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading">Entrando...</span>
            <span v-else>Entrar</span>
          </button>
        </form>

        <p class="text-center mt-6 text-sm text-slate-400">
          Não tem conta?
          <router-link to="/register" class="text-emerald-400 hover:text-emerald-300 font-medium">Cadastre-se</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { imgUrl } from '../config.js'

const router = useRouter()
const loading = ref(false)
const error = ref('')

const form = reactive({
  email: '',
  password: '',
})

/**
 * Simula login - em produção chamaria a API
 */
function handleLogin() {
  loading.value = true
  error.value = ''

  // Simulação de chamada API
  setTimeout(() => {
    loading.value = false
    // Placeholder: redireciona para dashboard
    sessionStorage.setItem('auth_token', 'simulado')
    router.push('/dashboard')
  }, 1000)
}
</script>
