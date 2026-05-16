/**
 * Configurações centralizadas do frontend
 * 
 * As constantes são injetadas via variáveis de ambiente do Vite (VITE_*)
 * ou usam valores padrão.
 */

// URL base do servidor - configurado via .env (BASE_URL)
export const BASE_URL = import.meta.env.VITE_BASE_URL || 'http://localhost:8000'

// Path base para imagens - configurado via .env (PATH_IMG)
export const PATH_IMG = import.meta.env.VITE_PATH_IMG || '/imgs'

/**
 * Retorna a URL completa para uma imagem
 * Usa caminho relativo quando possível, ou URL absoluta com BASE_URL
 * @param {string} filename - Nome do arquivo (ex: 'logo2.png')
 * @returns {string} Caminho da imagem (ex: '/imgs/logo2.png')
 */
export function imgUrl(filename) {
    // Usa caminho relativo para evitar problemas com VITE_* no build
    return `${PATH_IMG}/${filename}`
}
