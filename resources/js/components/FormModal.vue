<template>
  <Teleport to="body">
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="handleClose">
      <!-- Overlay -->
      <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" @click="handleClose" />

      <!-- Modal -->
      <div class="relative transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all w-full max-w-lg max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 shrink-0">
          <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
          <div class="flex items-center space-x-2">
            <!-- Botões Preencher/Limpar (se entity for informada) -->
            <FormActions v-if="entity" :form="form" :entity="entity" :keep-fields="keepFields" />
            <!-- Botão Fechar -->
            <button type="button" @click="handleClose" class="text-gray-400 hover:text-gray-600 transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 overflow-y-auto">
          <slot />
        </div>

        <!-- Footer -->
        <div v-if="$slots.footer" class="flex items-center justify-end space-x-3 px-6 py-4 border-t border-gray-200 bg-gray-50 shrink-0">
          <slot name="footer" />
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import FormActions from './FormActions.vue'

defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Formulário',
  },
  form: {
    type: Object,
    default: null,
  },
  entity: {
    type: String,
    default: '',
  },
  keepFields: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['close'])

function handleClose() {
  emit('close')
}
</script>
