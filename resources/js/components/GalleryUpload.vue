<template>
  <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ label }}</label>

    <!-- Grid de fotos com reordenacao via drag & drop -->
    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3 mb-3">
      <div
        v-for="(photo, index) in photos"
        :key="index"
        class="relative group cursor-grab active:cursor-grabbing"
        draggable="true"
        @dragstart="onDragStart($event, index)"
        @dragover.prevent="onDragOver($event, index)"
        @dragenter.prevent="onDragEnter($event, index)"
        @dragend="onDragEnd"
      >
        <img :src="getPhotoUrl(photo)" class="w-full h-24 object-cover rounded-lg border border-gray-200" />
        <!-- Overlay com numero e botao remover -->
        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
          <span class="text-white font-bold text-lg opacity-0 group-hover:opacity-100">{{ index + 1 }}</span>
        </div>
        <button type="button" @click="removePhoto(index)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
          &times;
        </button>
        <!-- Indicador de posicao -->
        <div class="absolute bottom-1 left-1 bg-indigo-600 text-white text-xs rounded px-1.5 py-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
          {{ index + 1 }}
        </div>
      </div>

      <!-- Botao adicionar -->
      <div class="flex items-center justify-center h-24 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-400 hover:bg-indigo-50 transition-all cursor-pointer" @click="triggerInput">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
      </div>
    </div>

    <input ref="fileInput" type="file" accept="image/png,image/jpeg,image/webp" class="hidden" @change="onFileChange" />
    <p class="text-xs text-gray-400">Arraste para reordenar as imagens. PNG, JPG ou WEBP até 5MB cada.</p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  modelValue: { type: Array, default: () => [] },
  label: { type: String, default: 'Fotos' },
})

const emit = defineEmits(['update:modelValue'])

const fileInput = ref(null)
const dragIndex = ref(null)
const dragOverIndex = ref(null)

const photos = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val),
})

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

  // Converte para base64
  const reader = new FileReader()
  reader.onload = () => {
    const newPhotos = [...photos.value, reader.result]
    photos.value = newPhotos
  }
  reader.readAsDataURL(file)

  // Limpa input
  e.target.value = ''
}

function removePhoto(index) {
  const newPhotos = photos.value.filter((_, i) => i !== index)
  photos.value = newPhotos
}

function onDragStart(e, index) {
  dragIndex.value = index
  e.dataTransfer.effectAllowed = 'move'
}

function onDragOver(e, index) {
  dragOverIndex.value = index
}

function onDragEnter(e, index) {
  if (dragIndex.value === null || dragIndex.value === index) return
  // Reordena
  const newPhotos = [...photos.value]
  const [removed] = newPhotos.splice(dragIndex.value, 1)
  newPhotos.splice(index, 0, removed)
  photos.value = newPhotos
  dragIndex.value = index
}

function onDragEnd() {
  dragIndex.value = null
  dragOverIndex.value = null
}
</script>
