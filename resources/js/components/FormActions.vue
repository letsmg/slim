<template>
  <div class="flex items-center space-x-2">
    <!-- Botão Preencher com dados de teste -->
    <button
      type="button"
      @click="handleFill"
      class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md border border-dashed border-emerald-300 text-emerald-700 bg-emerald-50 hover:bg-emerald-100 hover:border-emerald-400 transition-colors"
      title="Preencher com dados de teste"
    >
      <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
      </svg>
      Preencher
    </button>

    <!-- Botão Limpar formulário -->
    <button
      type="button"
      @click="handleClear"
      class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md border border-dashed border-gray-300 text-gray-600 bg-gray-50 hover:bg-gray-100 hover:border-gray-400 transition-colors"
      title="Limpar todos os campos"
    >
      <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
      </svg>
      Limpar
    </button>
  </div>
</template>

<script setup>
/**
 * Componente de ações para formulários
 * 
 * Fornece botões "Preencher" (com dados de teste dos seeders) e "Limpar"
 * para agilizar testes em telas de cadastro/edição.
 * 
 * Uso:
 * <FormActions :form="form" entity="users" @fill="handleFill" @clear="handleClear" />
 */
import { fillForm, clearForm } from '@/composables/useFormHelpers'

const props = defineProps({
  form: {
    type: Object,
    required: true,
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

const emit = defineEmits(['fill', 'clear'])

function handleFill() {
  if (props.entity && props.form) {
    fillForm(props.form, props.entity)
  }
  emit('fill')
}

function handleClear() {
  if (props.form) {
    clearForm(props.form, props.keepFields)
  }
  emit('clear')
}
</script>
