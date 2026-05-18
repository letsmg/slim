<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <!-- Modal de aceitação de termos -->
    <TermsModal :visible="showTermsModal" @accept="showTermsModal = false" />

    <!-- Header com logo -->
    <header class="border-b border-slate-700/50 backdrop-blur-sm bg-slate-900/80 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <img :src="imgUrl('logo2.svg')" alt="Slim App" class="h-10 w-auto drop-shadow-lg" />
          <span class="text-2xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">Slim App</span>
        </div>
        <nav class="flex items-center space-x-4">
          <router-link to="/login" class="text-slate-300 hover:text-emerald-400 px-4 py-2 text-sm font-medium transition-all duration-200 hover:scale-105">Entrar</router-link>
          <router-link to="/register" class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:from-emerald-500 hover:to-teal-500 transition-all duration-200 shadow-lg shadow-emerald-600/25 hover:shadow-emerald-500/40 hover:scale-105">
            Cadastrar
          </router-link>
        </nav>
      </div>
    </header>

    <!-- Banner principal com imagem -->
    <section class="relative overflow-hidden">
      <!-- Efeitos de fundo -->
      <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/20 via-transparent to-teal-900/20 pointer-events-none"></div>
      <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 relative">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
          <!-- Texto -->
          <div class="text-center lg:text-left animate-fade-in">
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-900/50 border border-emerald-700/50 text-emerald-300 text-xs font-medium mb-6">
              <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></span>
              Sistema de Gestão de Frotas
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
              Gerencie sua 
              <span class="bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">Frota</span>
              <br />com Inteligência
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
              Controle de veículos, motoristas, viagens e manutenções em um só lugar.
              Sistema completo para gestão de frotas com segurança e eficiência.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
              <router-link to="/register" class="group inline-flex items-center justify-center px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-base font-medium rounded-lg hover:from-emerald-500 hover:to-teal-500 transition-all duration-200 shadow-xl shadow-emerald-600/25 hover:shadow-emerald-500/40 hover:scale-105">
                Começar Agora
                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </router-link>
              <router-link to="/login" class="inline-flex items-center justify-center px-8 py-3.5 border border-slate-600 text-slate-300 text-base font-medium rounded-lg hover:border-emerald-500 hover:text-emerald-400 transition-all duration-200 hover:scale-105">
                Já tenho conta
              </router-link>
            </div>
            <!-- Estatísticas -->
            <div class="mt-12 grid grid-cols-3 gap-8 max-w-md mx-auto lg:mx-0">
              <div v-for="stat in stats" :key="stat.label" class="text-center">
                <div class="text-2xl font-bold text-white">{{ stat.value }}</div>
                <div class="text-xs text-slate-400 mt-1">{{ stat.label }}</div>
              </div>
            </div>
          </div>
          <!-- Imagem do banner -->
          <div class="mt-12 lg:mt-0 animate-fade-in" style="animation-delay: 0.2s">
            <div class="relative group">
              <div class="absolute -inset-1 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl blur-xl opacity-30 group-hover:opacity-50 transition-opacity duration-500"></div>
              <img :src="imgUrl('banner.svg')" alt="Banner Gerenciamento de Frotas" class="relative w-full rounded-xl shadow-2xl shadow-emerald-900/20 group-hover:scale-[1.02] transition-transform duration-500" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features -->
    <section class="border-t border-slate-700/50 py-20 relative">
      <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent pointer-events-none"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
          <h2 class="text-3xl sm:text-4xl font-bold text-white">
            Tudo que você precisa para
            <span class="bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">gerenciar sua frota</span>
          </h2>
          <p class="mt-4 text-slate-400 max-w-2xl mx-auto">Módulos completos para cada aspecto da gestão de frotas</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="(feature, index) in features"
            :key="feature.title"
            class="group relative p-6 rounded-xl bg-slate-800/50 border border-slate-700/50 hover:border-emerald-500/50 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-900/20"
            :style="{ animationDelay: `${index * 0.1}s` }"
          >
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative">
              <div class="w-full h-40 rounded-lg overflow-hidden mb-4 group-hover:scale-105 transition-transform duration-300">
                <img :src="imgUrl(feature.image)" :alt="feature.title" class="w-full h-full object-cover" />
              </div>
              <h3 class="text-lg font-semibold text-white mb-2">{{ feature.title }}</h3>
              <p class="text-sm text-slate-400">{{ feature.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="border-t border-slate-700/50 py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
          Pronto para começar?
        </h2>
        <p class="text-slate-400 max-w-xl mx-auto mb-8">
          Cadastre-se agora e comece a gerenciar sua frota de forma inteligente e eficiente.
        </p>
        <router-link to="/register" class="inline-flex items-center justify-center px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-base font-medium rounded-lg hover:from-emerald-500 hover:to-teal-500 transition-all duration-200 shadow-xl shadow-emerald-600/25 hover:shadow-emerald-500/40 hover:scale-105">
          Começar Agora
          <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
          </svg>
        </router-link>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-700/50 py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
          <div class="flex items-center space-x-3">
            <img :src="imgUrl('logo2.svg')" alt="Slim App" class="h-8 w-auto opacity-60" />
            <span class="text-sm text-slate-500">Slim App</span>
          </div>
          <div class="flex items-center space-x-6">
            <router-link to="/termos-de-uso" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Termos de Uso</router-link>
            <router-link to="/politica-de-privacidade" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Privacidade</router-link>
            <router-link to="/login" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Entrar</router-link>
            <router-link to="/register" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Cadastrar</router-link>
          </div>
          <p class="text-sm text-slate-600">&copy; {{ new Date().getFullYear() }} Slim App. Todos os direitos reservados.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { imgUrl } from '../config.js'
import TermsModal from '../components/TermsModal.vue'

/**
 * Página inicial do sistema - Landing page moderna e responsiva
 * Exibe banner, funcionalidades e call-to-action
 */

// Controle do modal de aceitação de termos
const showTermsModal = ref(false)

onMounted(() => {
  // Exibe o modal se o usuário ainda não aceitou os termos
  const termsAccepted = localStorage.getItem('terms_accepted')
  if (!termsAccepted) {
    showTermsModal.value = true
  }
})

const stats = [
  { label: 'Veículos', value: '100+' },
  { label: 'Motoristas', value: '50+' },
  { label: 'Viagens', value: '1000+' },
]

const features = [
  {
    title: 'Veículos',
    description: 'Cadastro completo de veículos com placas, modelos e documentação.',
    image: 'vehicle.jpg',
  },
  {
    title: 'Motoristas',
    description: 'Gestão de motoristas com documentos, contatos e histórico.',
    image: 'driver.jpg',
  },
  {
    title: 'Viagens',
    description: 'Planejamento e acompanhamento de viagens com previsões.',
    image: 'trip.jpg',
  },
  {
    title: 'Manutenções',
    description: 'Agendamento e controle de manutenções preventivas e corretivas.',
    image: 'mechanics.jpg',
  },
]
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.8s ease-out forwards;
}
</style>
