/**
 * Configurações centralizadas do frontend
 * 
 * As constantes são injetadas via meta tags no HTML
 * ou via variáveis de ambiente do Vite (VITE_*)
 */

// Path base para imagens - configurado via .env (APP_IMG_PATH)
// Padrão: /imgs
export const IMG_PATH = import.meta.env.VITE_IMG_PATH || '/imgs'

/**
 * Retorna o caminho completo para uma imagem
 * @param {string} filename - Nome do arquivo (ex: 'logo2.png')
 * @returns {string} Caminho completo (ex: '/imgs/logo2.png')
 */
export function imgUrl(filename) {
    return `${IMG_PATH}/${filename}`
}
