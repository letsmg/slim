<template>
  <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>

    <!-- Preview da foto atual -->
    <div v-if="modelValue" class="relative mb-2 inline-block">
      <img :src="getPhotoUrl(modelValue)" class="w-32 h-32 object-cover rounded-lg border border-gray-200" />
      <button type="button" @click="removePhoto" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
        &times;
      </button>
    </div>

    <!-- Input de upload -->
    <div v-if="!modelValue" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors cursor-pointer" @click="triggerInput">
      <div class="text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <p class="mt-1 text-sm text-gray-500">
          <span class="font-medium text-indigo-600 hover:text-indigo-500">Clique para selecionar</span>
        </p>
        <p class="text-xs text-gray-400 mt-1">PNG, JPG ou WEBP até 5MB</p>
      </div>
      <input ref="fileInput" type="file" accept="image/png,image/jpeg,image/webp" class="hidden" @change="onFileChange" />
    </div>

    <!-- Preview do arquivo selecionado (antes de salvar) -->
    <div v-if="previewUrl && !modelValue" class="mt-2">
      <img :src="previewUrl" class="w-32 h-32 object-cover rounded-lg border border-gray-200" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  modelValue: { type: String, default: '' },
  label: { type: String, default: 'Foto' },
})

const emit = defineEmits(['update:modelValue'])

const fileInput = ref(null)
const previewUrl = ref(null)

function triggerInput() {
  fileInput.value?.click()
}

function onFileChange(e) {
  const file = e.target.files?.[0]
  if (!file) return

  // Valida tamanho (5MB)
  if (file.size > 5 * 1024 * 1024) {
    alert('A imagem deve ter no máximo 5MB.')
    return
  }

  // Valida tipo
  const allowedTypes = ['image/png', 'image/jpeg', 'image/webp']
  if (!allowedTypes.includes(file.type)) {
    alert('Apenas PNG, JPG e WEBP são permitidos.')
    return
  }

  // Cria preview
  const reader = new FileReader()
  reader.onload = (ev) => {
    previewUrl.value = ev.target.result
  }
  reader.readAsDataURL(file)

  // Converte para base64 e emite
  const reader2 = new FileReader()
  reader2.onload = () => {
    emit('update:modelValue', reader2.result)
  }
  reader2.readAsDataURL(file)
}

function removePhoto() {
  emit('update:modelValue', '')
  previewUrl.value = null
  if (fileInput.value) fileInput.value.value = ''
}

function getPhotoUrl(path) {
  if (!path) return ''
  // Se já é base64, retorna direto
  if (path.startsWith('data:')) return path
  // Se é caminho relativo, monta URL
  return `/storage/imgs/${path}`
}
</script>
