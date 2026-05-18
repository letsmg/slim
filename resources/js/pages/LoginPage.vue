<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-4xl">
      <!-- Link Voltar -->
      <div class="mb-4">
        <router-link to="/" class="inline-flex items-center text-sm text-slate-400 hover:text-emerald-400 transition-colors">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Voltar para página inicial
        </router-link>
      </div>

      <!-- Logo -->
      <div class="text-center mb-8">
        <router-link to="/" class="inline-flex items-center space-x-3">
          <img :src="imgUrl('logo2.svg')" alt="Slim App" class="h-10 w-auto drop-shadow-lg" />
          <span class="text-2xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">Slim App</span>
        </router-link>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Card de Login (ocupa 3 colunas) -->
        <div class="lg:col-span-3 bg-slate-800/50 border border-slate-700/50 rounded-xl p-8 backdrop-blur-sm">
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
              <div class="relative">
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  placeholder="Sua senha"
                  class="w-full px-4 py-2.5 pr-10 bg-slate-900/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-emerald-400 transition-colors"
                  tabindex="-1"
                >
                  <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                </button>
              </div>
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

        <!-- Tabela de Usuários de Teste (ocupa 2 colunas) -->
        <div class="lg:col-span-2 bg-slate-800/30 border border-slate-700/30 rounded-xl p-6 backdrop-blur-sm">
          <h3 class="text-lg font-semibold text-white mb-1">Usuários de Teste</h3>
          <p class="text-xs text-slate-400 mb-4">Clique em um usuário para preencher o login</p>

          <div class="space-y-2">
            <button
              v-for="user in testUsers"
              :key="user.email"
              @click="selectUser(user)"
              class="w-full text-left px-4 py-3 rounded-lg border border-slate-600/50 bg-slate-900/30 hover:bg-slate-700/50 hover:border-emerald-500/50 transition-all group"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 min-w-0">
                  <div class="h-8 w-8 rounded-full flex items-center justify-center flex-shrink-0" :class="user.level === 'admin' ? 'bg-purple-900/50 text-purple-300' : user.level === 'operational' ? 'bg-blue-900/50 text-blue-300' : 'bg-gray-700/50 text-gray-300'">
                    <span class="text-xs font-bold">{{ user.name.charAt(0) }}</span>
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-200 group-hover:text-emerald-300 transition-colors truncate">{{ user.name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ user.email }}</p>
                  </div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0 ml-2" :class="levelBadgeClass(user.level)">
                  {{ levelLabel(user.level) }}
                </span>
              </div>
            </button>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-700/30">
            <p class="text-xs text-slate-500">
              <span class="text-emerald-400 font-medium">Senha padrão:</span> <code class="bg-slate-900 px-1.5 py-0.5 rounded text-emerald-300">123456</code>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { imgUrl } from '../config.js'
import api from '@/services/api'

const router = useRouter()
const loading = ref(false)
const error = ref('')
const showPassword = ref(false)

const form = reactive({
  email: '',
  password: '',
})

/**
 * Usuários de teste cadastrados pelos seeders
 * Senha padrão para todos: 123456
 */
const testUsers = [
  { name: 'Admin', email: 'admin@slimapp.com', level: 'admin' },
  { name: 'João Silva', email: 'joao@slimapp.com', level: 'operational' },
  { name: 'Maria Souza', email: 'maria@slimapp.com', level: 'operational' },
  { name: 'Carlos Pereira', email: 'carlos@slimapp.com', level: 'support' },
  { name: 'Ana Oliveira', email: 'ana@slimapp.com', level: 'support' },
]

/**
 * Preenche o formulário com os dados do usuário selecionado
 */
function selectUser(user) {
  form.email = user.email
  form.password = '123456'
  error.value = ''
}

/**
 * Login real via API
 */
async function handleLogin() {
  loading.value = true
  error.value = ''

  try {
    const res = await api.post('/api/auth/login', {
      email: form.email,
      password: form.password,
    })

    const data = res.data

    if (data.success && data.user) {
      sessionStorage.setItem('auth_token', data.token || 'authenticated')
      sessionStorage.setItem('user_name', data.user.name || '')
      sessionStorage.setItem('user_email', data.user.email || '')
      sessionStorage.setItem('user_level', data.user.level || 'operational')
      router.push('/dashboard')
    } else {
      error.value = data.message || 'Credenciais inválidas'
    }
  } catch (e) {
    const msg = e.response?.data?.message || e.response?.data?.error || 'Erro ao conectar ao servidor'
    error.value = msg
  } finally {
    loading.value = false
  }
}

function levelBadgeClass(level) {
  const map = { admin: 'bg-purple-900/50 text-purple-300', operational: 'bg-blue-900/50 text-blue-300', support: 'bg-gray-700/50 text-gray-300' }
  return map[level] || 'bg-gray-700/50 text-gray-300'
}

function levelLabel(level) {
  const map = { admin: 'Admin', operational: 'Operacional', support: 'Suporte' }
  return map[level] || level
}
</script>
