<template>
  <div>
    <!-- Cabecalho -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Usuarios</h1>
        <p class="mt-1 text-sm text-gray-500">Gerencie os usuarios do sistema</p>
      </div>
      <button v-if="isAdmin" @click="openCreateModal" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Novo Usuario
      </button>
    </div>

    <!-- Tabela -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nivel</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ user.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                    <span class="text-sm font-medium text-indigo-600">{{ user.name?.charAt(0)?.toUpperCase() }}</span>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full" :class="levelClass(user.level)">
                  {{ levelLabel(user.level) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full" :class="user.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                  {{ user.active ? 'Ativo' : 'Inativo' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <!-- Editar: admin edita nao-admin, ou usuario edita a si mesmo -->
                <button v-if="canEdit(user)" @click="editUser(user)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                <!-- Resetar Senha: admin, operacional ou suporte conforme regra -->
                <button v-if="canResetPassword(user)" @click="resetPassword(user)" class="text-amber-600 hover:text-amber-900 mr-3" title="Resetar senha para 123456">&#x1f511;</button>
                <!-- Excluir: apenas admin, nao pode excluir a si mesmo nem outro admin -->
                <button v-if="canDelete(user)" @click="deleteUser(user)" class="text-red-600 hover:text-red-900">Excluir</button>
              </td>
            </tr>
            <tr v-if="filteredUsers.length === 0">
              <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">Nenhum usuario encontrado.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal de Cadastro/Edicao -->
    <FormModal :open="modalOpen" :title="editing ? 'Editar Usuario' : 'Novo Usuario'" :form="form" entity="users" @close="closeModal">
      <!-- Erros de validacao -->
      <div v-if="validationErrors" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
        <ul class="list-disc list-inside space-y-1">
          <li v-for="(msg, field) in validationErrors" :key="field">{{ msg }}</li>
        </ul>
      </div>
      <form @submit.prevent="saveUser" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
          <input v-model="form.name" type="text" required placeholder="Nome completo" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input v-model="form.email" type="email" required placeholder="email@exemplo.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
          <input v-model="form.password" type="password" :required="!editing" placeholder="Minimo 6 caracteres" minlength="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div v-if="isAdmin && editing?.level !== 'admin'">
          <label class="block text-sm font-medium text-gray-700 mb-1">Nivel de Acesso</label>
          <select v-model="form.level" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="admin">Admin</option>
            <option value="operational">Operacional</option>
            <option value="support">Suporte</option>
          </select>
        </div>
        <div class="flex items-center">
          <input v-model="form.active" type="checkbox" id="user-active" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
          <label for="user-active" class="ml-2 text-sm text-gray-700">Usuario ativo</label>
        </div>
        <PhotoUpload v-model="form.photo" label="Foto do Usuario" />
      </form>

      <template #footer>
        <button type="button" @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancelar</button>
        <button type="button" @click="saveUser" :disabled="saving" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50">
          {{ saving ? 'Salvando...' : 'Salvar' }}
        </button>
      </template>
    </FormModal>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import api from '@/services/api'
import FormModal from '@components/FormModal.vue'
import PhotoUpload from '@components/PhotoUpload.vue'

const users = ref([])
const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)
const validationErrors = ref(null)

/**
 * Nivel do usuario logado (vem do sessionStorage setado no login)
 */
const userLevel = computed(() => sessionStorage.getItem('user_level') || '')
const userEmail = computed(() => sessionStorage.getItem('user_email') || '')
const isAdmin = computed(() => userLevel.value === 'admin')
const isOperational = computed(() => userLevel.value === 'operational')
const isSupport = computed(() => userLevel.value === 'support')

/**
 * Filtra usuarios conforme o nivel de acesso:
 * - Admin: ve todos
 * - Operacional: ve operacional e suporte
 * - Suporte: ve apenas suportes
 * - User comum: ve apenas a si mesmo
 */
const filteredUsers = computed(() => {
  const level = userLevel.value
  const myEmail = userEmail.value

  if (level === 'admin') return users.value
  if (level === 'operational') return users.value.filter(u => u.level === 'operational' || u.level === 'support')
  if (level === 'support') return users.value.filter(u => u.level === 'support')
  // User comum: ve apenas a si mesmo
  return users.value.filter(u => u.email === myEmail)
})

const form = reactive({
  name: '',
  email: '',
  password: '',
  level: 'operational',
  active: true,
  photo: '',
})

onMounted(async () => {
  try {
    const res = await api.get('/api/users')
    users.value = res.data?.users ?? []
  } catch (e) {
    console.error('Erro ao carregar usuarios:', e)
  }
})

function openCreateModal() {
  editing.value = null
  validationErrors.value = null
  Object.assign(form, { name: '', email: '', password: '', level: 'operational', active: true, photo: '' })
  modalOpen.value = true
}

function editUser(user) {
  editing.value = user
  validationErrors.value = null
  Object.assign(form, { name: user.name, email: user.email, password: '', level: user.level || 'operational', active: !!user.active, photo: user.photo || '' })
  modalOpen.value = true
}

function closeModal() {
  modalOpen.value = false
  editing.value = null
  validationErrors.value = null
}

async function saveUser() {
  saving.value = true
  validationErrors.value = null
  try {
    // Monta payload: só envia level se o campo estiver visivel (admin editando nao-admin)
    const payload = { ...form }
    // Se nao pode alterar nivel (admin editando admin, ou nao-admin), remove level do payload
    if (!isAdmin.value || editing.value?.level === 'admin') {
      delete payload.level
    }
    // Se editando e senha vazia, remove do payload para nao sobrescrever
    if (editing.value && !payload.password) {
      delete payload.password
    }

    if (editing.value) {
      const res = await api.put(`/api/users/${editing.value.id}`, payload)
      const idx = users.value.findIndex(u => u.id === editing.value.id)
      if (idx !== -1) {
        users.value[idx] = { ...users.value[idx], ...res.data?.user, password: undefined }
      }
    } else {
      const res = await api.post('/api/users', payload)
      users.value.push(res.data?.user || { ...payload, id: Date.now(), password: undefined })
    }
    closeModal()
  } catch (e) {
    const errors = e.response?.data?.errors
    if (errors) {
      validationErrors.value = errors
    } else {
      alert('Erro ao salvar. Verifique os dados e tente novamente.')
    }
  } finally {
    saving.value = false
  }
}

async function deleteUser(user) {
  if (!confirm(`Excluir usuario ${user.name}?`)) return
  try {
    await api.delete(`/api/users/${user.id}`)
    users.value = users.value.filter(u => u.id !== user.id)
  } catch (e) {
    console.error('Erro ao excluir:', e)
  }
}

function levelClass(level) {
  const map = { admin: 'bg-purple-100 text-purple-800', operational: 'bg-blue-100 text-blue-800', support: 'bg-gray-100 text-gray-800' }
  return map[level] || 'bg-gray-100 text-gray-800'
}

function levelLabel(level) {
  const map = { admin: 'Admin', operational: 'Operacional', support: 'Suporte' }
  return map[level] || level
}

/**
 * Verifica se pode editar um usuario
 * - Admin pode editar qualquer usuario EXCETO outro admin
 * - Usuario pode editar a si mesmo
 */
function canEdit(user) {
  const level = userLevel.value
  const myEmail = userEmail.value

  // Admin nao pode editar outro admin
  if (level === 'admin' && user.level === 'admin') return false
  // Admin pode editar nao-admin
  if (level === 'admin') return true
  // Usuario so pode editar a si mesmo
  return user.email === myEmail
}

/**
 * Verifica se pode resetar a senha de um usuario
 * - Admin pode resetar senha de qualquer usuario
 * - Operacional pode resetar senha de operacional e suporte
 * - Suporte pode resetar senha de outros suportes
 */
function canResetPassword(user) {
  const level = userLevel.value

  // Admin pode resetar senha de qualquer um
  if (level === 'admin') return true
  // Operacional pode resetar senha de operacional e suporte
  if (level === 'operational') return user.level === 'operational' || user.level === 'support'
  // Suporte pode resetar senha de outros suportes
  if (level === 'support') return user.level === 'support'
  return false
}

/**
 * Verifica se pode excluir um usuario
 * - Apenas admin pode excluir
 * - Nao pode excluir a si mesmo
 * - Nao pode excluir outro admin
 */
function canDelete(user) {
  if (!isAdmin.value) return false
  const myEmail = userEmail.value
  if (user.email === myEmail) return false
  if (user.level === 'admin') return false
  return true
}

/**
 * Reseta a senha de um usuario para 123456
 */
async function resetPassword(user) {
  if (!confirm(`Resetar senha do usuario ${user.name} para 123456?`)) return
  try {
    await api.put(`/api/users/${user.id}/reset-password`, { password: '123456' })
    alert('Senha resetada com sucesso para 123456.')
  } catch (e) {
    console.error('Erro ao resetar senha:', e)
    alert('Erro ao resetar senha.')
  }
}
</script>
