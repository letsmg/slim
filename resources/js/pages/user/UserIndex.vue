<template>
  <div>
    <!-- Cabeçalho -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Usuários</h1>
        <p class="mt-1 text-sm text-gray-500">Gerencie os usuários do sistema</p>
      </div>
      <button @click="openCreateModal" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Novo Usuário
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
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nível</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition-colors">
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
                <button @click="editUser(user)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                <button @click="deleteUser(user)" class="text-red-600 hover:text-red-900">Excluir</button>
              </td>
            </tr>
            <tr v-if="users.length === 0">
              <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">Nenhum usuário encontrado.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal de Cadastro/Edição -->
    <FormModal :open="modalOpen" :title="editing ? 'Editar Usuário' : 'Novo Usuário'" :form="form" entity="users" @close="closeModal">
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
          <input v-model="form.password" type="password" :required="!editing" placeholder="Mínimo 6 caracteres" minlength="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nível de Acesso</label>
          <select v-model="form.level" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="admin">Admin</option>
            <option value="operational">Operacional</option>
            <option value="support">Suporte</option>
          </select>
        </div>
        <div class="flex items-center">
          <input v-model="form.active" type="checkbox" id="user-active" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
          <label for="user-active" class="ml-2 text-sm text-gray-700">Usuário ativo</label>
        </div>
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
import { ref, reactive, onMounted } from 'vue'
import api from '@/services/api'
import FormModal from '@components/FormModal.vue'

const users = ref([])
const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)

const form = reactive({
  name: '',
  email: '',
  password: '',
  level: 'operational',
  active: true,
})

onMounted(async () => {
  try {
    const res = await api.get('/api/users')
    users.value = res.data?.users ?? []
  } catch (e) {
    console.error('Erro ao carregar usuários:', e)
  }
})

function openCreateModal() {
  editing.value = null
  Object.assign(form, { name: '', email: '', password: '', level: 'operational', active: true })
  modalOpen.value = true
}

function editUser(user) {
  editing.value = user
  Object.assign(form, { name: user.name, email: user.email, password: '', level: user.level || 'operational', active: !!user.active })
  modalOpen.value = true
}

function closeModal() {
  modalOpen.value = false
  editing.value = null
}

async function saveUser() {
  saving.value = true
  try {
    if (editing.value) {
      await api.put(`/api/users/${editing.value.id}`, form)
      const idx = users.value.findIndex(u => u.id === editing.value.id)
      if (idx !== -1) users.value[idx] = { ...editing.value, ...form, password: undefined }
    } else {
      const res = await api.post('/api/users', form)
      users.value.push(res.data?.user || { ...form, id: Date.now(), password: undefined })
    }
    closeModal()
  } catch (e) {
    console.error('Erro ao salvar usuário:', e)
    alert('Erro ao salvar. Verifique o console.')
  } finally {
    saving.value = false
  }
}

async function deleteUser(user) {
  if (!confirm(`Excluir usuário ${user.name}?`)) return
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
</script>
