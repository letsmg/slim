<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <router-link to="/" class="inline-flex items-center space-x-3">
          <img :src="imgUrl('logo2.svg')" alt="Slim App" class="h-10 w-auto drop-shadow-lg" />
          <span class="text-2xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">Slim App</span>
        </router-link>
      </div>

      <!-- Card de Cadastro -->
      <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-8 backdrop-blur-sm">
        <h2 class="text-2xl font-bold text-white text-center mb-2">Criar Conta</h2>
        <p class="text-slate-400 text-center mb-8 text-sm">Cadastre-se para gerenciar sua frota</p>

        <form @submit.prevent="handleRegister" class="space-y-5">
          <!-- Nome -->
          <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Nome completo</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              placeholder="Seu nome"
              class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
            />
          </div>

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
              minlength="6"
              placeholder="Mínimo 6 caracteres"
              class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
            />
          </div>

          <!-- Confirmar Senha -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Confirmar senha</label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              required
              placeholder="Repita a senha"
              class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
            />
          </div>

          <!-- Aceite dos Termos -->
          <div class="flex items-start">
            <input
              id="accept_terms"
              v-model="form.acceptTerms"
              type="checkbox"
              required
              class="mt-1 h-4 w-4 rounded border-slate-600 bg-slate-900/50 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0"
            />
            <label for="accept_terms" class="ml-2 text-sm text-slate-400">
              Li e aceito os
              <router-link to="/termos-de-uso" class="text-emerald-400 hover:text-emerald-300">Termos de Uso</router-link>
              e a
              <router-link to="/politica-de-privacidade" class="text-emerald-400 hover:text-emerald-300">Política de Privacidade</router-link>
            </label>
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
            <span v-if="loading">Cadastrando...</span>
            <span v-else>Cadastrar</span>
          </button>
        </form>

        <p class="text-center mt-6 text-sm text-slate-400">
          Já tem conta?
          <router-link to="/login" class="text-emerald-400 hover:text-emerald-300 font-medium">Entrar</router-link>
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
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  acceptTerms: false,
})

/**
 * Simula cadastro - em produção chamaria a API
 */
function handleRegister() {
  loading.value = true
  error.value = ''

  // Validação básica
  if (form.password !== form.password_confirmation) {
    error.value = 'As senhas não conferem.'
    loading.value = false
    return
  }

  if (!form.acceptTerms) {
    error.value = 'Você precisa aceitar os Termos de Uso e Política de Privacidade.'
    loading.value = false
    return
  }

  // Simulação de chamada API
  setTimeout(() => {
    loading.value = false
    // Placeholder: redireciona para dashboard
    sessionStorage.setItem('auth_token', 'simulado')
    router.push('/dashboard')
  }, 1000)
}
</script>
