<template>
  <Teleport to="body">
    <div
      v-if="visible"
      class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
    >
      <div class="bg-slate-800 border border-slate-700/50 rounded-xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <!-- Cabeçalho -->
        <div class="p-6 border-b border-slate-700/50">
          <div class="flex items-center space-x-3 mb-2">
            <img :src="imgUrl('logo2.svg')" alt="Slim App" class="h-8 w-auto" />
            <span class="text-xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">Slim App</span>
          </div>
          <h2 class="text-lg font-semibold text-white">Termos de Uso e Privacidade</h2>
          <p class="text-sm text-slate-400 mt-1">
            Para continuar utilizando o Sistema, você precisa aceitar nossos Termos de Uso e Política de Privacidade.
          </p>
        </div>

        <!-- Conteúdo resumido -->
        <div class="p-6 space-y-4 text-sm text-slate-300">
          <div class="bg-slate-900/50 rounded-lg p-4 border border-slate-700/50">
            <h3 class="font-semibold text-white mb-2">📋 Resumo dos Termos de Uso</h3>
            <ul class="space-y-1.5 list-disc pl-5">
              <li>Coletamos dados de <strong>localização</strong> para segurança e rastreamento da frota;</li>
              <li>Coletamos seu <strong>endereço de IP</strong> para detectar acessos indevidos;</li>
              <li>Seus dados são armazenados por até <strong>5 anos</strong> conforme legislação;</li>
              <li>Adotamos medidas de segurança conforme <strong>ISO 27001</strong>.</li>
            </ul>
            <router-link
              to="/termos-de-uso"
              class="inline-block mt-2 text-emerald-400 hover:text-emerald-300 text-xs font-medium"
              @click="visible = false"
            >
              Ler Termos de Uso completos &rarr;
            </router-link>
          </div>

          <div class="bg-slate-900/50 rounded-lg p-4 border border-slate-700/50">
            <h3 class="font-semibold text-white mb-2">🔒 Resumo da Política de Privacidade</h3>
            <ul class="space-y-1.5 list-disc pl-5">
              <li><strong>Não vendemos</strong> seus dados para terceiros;</li>
              <li>Usamos criptografia <strong>TLS/SSL</strong> e hash <strong>Argon2id</strong>;</li>
              <li>Infraestrutura segura com controle de acesso <strong>RBAC</strong>;</li>
              <li>Você pode solicitar exclusão dos dados a qualquer momento.</li>
            </ul>
            <router-link
              to="/politica-de-privacidade"
              class="inline-block mt-2 text-emerald-400 hover:text-emerald-300 text-xs font-medium"
              @click="visible = false"
            >
              Ler Política de Privacidade completa &rarr;
            </router-link>
          </div>
        </div>

        <!-- Ações -->
        <div class="p-6 border-t border-slate-700/50 space-y-3">
          <label class="flex items-start space-x-3 cursor-pointer">
            <input
              v-model="accepted"
              type="checkbox"
              class="mt-0.5 h-4 w-4 rounded border-slate-600 bg-slate-900/50 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0"
            />
            <span class="text-sm text-slate-300">
              Li e aceito os
              <router-link to="/termos-de-uso" class="text-emerald-400 hover:text-emerald-300" @click="visible = false">Termos de Uso</router-link>
              e a
              <router-link to="/politica-de-privacidade" class="text-emerald-400 hover:text-emerald-300" @click="visible = false">Política de Privacidade</router-link>
            </span>
          </label>

          <button
            :disabled="!accepted"
            class="w-full py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-lg hover:from-emerald-500 hover:to-teal-500 transition-all duration-200 shadow-lg shadow-emerald-600/25 disabled:opacity-50 disabled:cursor-not-allowed"
            @click="confirm"
          >
            Aceitar e Continuar
          </button>

          <p class="text-xs text-slate-500 text-center">
            Ao aceitar, você concorda com a coleta de dados conforme descrito acima.
          </p>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'
import { imgUrl } from '../config.js'

const props = defineProps({
  visible: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['accept', 'close'])

const accepted = ref(false)

/**
 * Fecha o modal e emite evento de aceitação
 */
function confirm() {
  if (accepted.value) {
    localStorage.setItem('terms_accepted', 'true')
    localStorage.setItem('terms_accepted_at', new Date().toISOString())
    emit('accept')
  }
}

// Reseta o checkbox quando o modal abre
watch(() => props.visible, (val) => {
  if (val) accepted.value = false
})
</script>
