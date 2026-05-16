import axios from 'axios'

/**
 * Instância do Axios configurada para comunicação com API REST
 * Usa a URL base do ambiente ou fallback para localhost
 */
const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL || '/',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    timeout: 15000,
})

// Interceptor de requisição: adiciona token CSRF se disponível
api.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token
    }
    return config
}, (error) => Promise.reject(error))

// Interceptor de resposta: trata erros HTTP padronizados
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Redirecionar para login ou tratar token expirado
            console.warn('Não autorizado - redirecionar para login')
        }
        if (error.response?.status === 419) {
            // CSRF token mismatch
            console.warn('CSRF token expirou - recarregar página')
            window.location.reload()
        }
        return Promise.reject(error)
    }
)

export default api