<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <!-- Header com logo -->
    <header class="border-b border-slate-700/50 backdrop-blur-sm bg-slate-900/80 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <img src="/imgs/logo2.png" alt="Slim App" class="h-10 w-auto drop-shadow-lg" />
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
              <img src="/imgs/banner.png" alt="Banner Gerenciamento de Frotas" class="relative w-full rounded-xl shadow-2xl shadow-emerald-900/20 group-hover:scale-[1.02] transition-transform duration-500" />
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
              <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-emerald-900/50 to-teal-900/50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path v-html="feature.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                </svg>
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
            <img src="/imgs/logo2.png" alt="Slim App" class="h-8 w-auto opacity-60" />
            <span class="text-sm text-slate-500">Slim App</span>
          </div>
          <div class="flex items-center space-x-6">
            <router-link to="/termos-de-uso" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Termos de Uso</router-link>
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
import { imgUrl } from '../config.js'

/**
 * Página inicial do sistema - Landing page moderna e responsiva
 * Exibe banner, funcionalidades e call-to-action
 */
const stats = [
  { label: 'Veículos', value: '100+' },
  { label: 'Motoristas', value: '50+' },
  { label: 'Viagens', value: '1000+' },
]

const features = [
  {
    title: 'Veículos',
    description: 'Cadastro completo de veículos com placas, modelos e documentação.',
    icon: 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
  },
  {
    title: 'Motoristas',
    description: 'Gestão de motoristas com documentos, contatos e histórico.',
    icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
  },
  {
    title: 'Viagens',
    description: 'Planejamento e acompanhamento de viagens com previsões.',
    icon: 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
  },
  {
    title: 'Manutenções',
    description: 'Agendamento e controle de manutenções preventivas e corretivas.',
    icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
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
