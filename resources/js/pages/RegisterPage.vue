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
        <!-- Card de Cadastro (ocupa 3 colunas) -->
        <div class="lg:col-span-3 bg-slate-800/50 border border-slate-700/50 rounded-xl p-8 backdrop-blur-sm">
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
              <div class="relative">
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  minlength="6"
                  placeholder="Mínimo 6 caracteres"
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

            <!-- Confirmar Senha -->
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Confirmar senha</label>
              <div class="relative">
                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  :type="showPasswordConfirm ? 'text' : 'password'"
                  required
                  placeholder="Repita a senha"
                  class="w-full px-4 py-2.5 pr-10 bg-slate-900/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                />
                <button
                  type="button"
                  @click="showPasswordConfirm = !showPasswordConfirm"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-emerald-400 transition-colors"
                  tabindex="-1"
                >
                  <svg v-if="!showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                </button>
              </div>
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

        <!-- Ações Rápidas (ocupa 2 colunas) -->
        <div class="lg:col-span-2 bg-slate-800/30 border border-slate-700/30 rounded-xl p-6 backdrop-blur-sm">
          <h3 class="text-lg font-semibold text-white mb-1">Ações Rápidas</h3>
          <p class="text-xs text-slate-400 mb-4">Preencha ou limpe o formulário rapidamente</p>

          <div class="space-y-3">
            <!-- Botão Preencher -->
            <button
              @click="fillForm"
              class="w-full text-left px-4 py-3 rounded-lg border border-emerald-600/50 bg-emerald-900/20 hover:bg-emerald-900/40 hover:border-emerald-500 transition-all group"
            >
              <div class="flex items-center space-x-3">
                <div class="h-8 w-8 rounded-full bg-emerald-900/50 flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-emerald-300 group-hover:text-emerald-200 transition-colors">Preencher Formulário</p>
                  <p class="text-xs text-slate-500">Preenche com dados de exemplo para teste</p>
                </div>
              </div>
            </button>

            <!-- Botão Limpar -->
            <button
              @click="clearForm"
              class="w-full text-left px-4 py-3 rounded-lg border border-slate-600/50 bg-slate-900/30 hover:bg-slate-700/50 hover:border-red-500/50 transition-all group"
            >
              <div class="flex items-center space-x-3">
                <div class="h-8 w-8 rounded-full bg-red-900/30 flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-red-300 group-hover:text-red-200 transition-colors">Limpar Formulário</p>
                  <p class="text-xs text-slate-500">Remove todos os dados do formulário</p>
                </div>
              </div>
            </button>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-700/30">
            <p class="text-xs text-slate-500">
              <span class="text-emerald-400 font-medium">Dica:</span> Use o preenchimento rápido para testar o cadastro sem digitar manualmente.
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
const showPasswordConfirm = ref(false)

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  acceptTerms: false,
})

/**
 * Preenche o formulário com dados de exemplo para teste
 */
function fillForm() {
  form.name = 'Novo Usuário Teste'
  form.email = 'teste' + Date.now() + '@exemplo.com'
  form.password = '123456'
  form.password_confirmation = '123456'
  form.acceptTerms = true
  error.value = ''
}

/**
 * Limpa todos os campos do formulário
 */
function clearForm() {
  form.name = ''
  form.email = ''
  form.password = ''
  form.password_confirmation = ''
  form.acceptTerms = false
  error.value = ''
}

/**
 * Valida email duplicado no frontend antes de enviar
 */
async function checkEmailExists(email) {
  try {
    const res = await api.get('/api/users')
    const users = res.data?.users ?? []
    return users.some(u => u.email === email)
  } catch {
    return false
  }
}

/**
 * Cadastra usuário via API com validação de email único
 */
async function handleRegister() {
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

  // Valida email duplicado no frontend
  const emailExists = await checkEmailExists(form.email)
  if (emailExists) {
    error.value = 'Este email já está cadastrado. Use outro email ou faça login.'
    loading.value = false
    return
  }

  try {
    const res = await api.post('/api/users', {
      name: form.name,
      email: form.email,
      password: form.password,
    })

    if (res.data?.success) {
      const user = res.data?.user || {}
      sessionStorage.setItem('auth_token', res.data?.token || 'simulado')
      sessionStorage.setItem('user_name', user.name || form.name)
      sessionStorage.setItem('user_email', user.email || form.email)
      sessionStorage.setItem('user_level', user.level || 'user')
      router.push('/dashboard')
    } else {
      error.value = res.data?.message || 'Erro ao cadastrar.'
    }
  } catch (e) {
    const errors = e.response?.data?.errors
    if (errors) {
      error.value = Object.values(errors).join(', ')
    } else {
      error.value = e.response?.data?.message || 'Erro de conexão com o servidor.'
    }
  } finally {
    loading.value = false
  }
}
</script>
