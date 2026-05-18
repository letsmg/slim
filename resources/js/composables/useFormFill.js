/**
 * useFormFill — Composável para preenchimento e limpeza de formulários
 * 
 * Fornece duas funções utilitárias:
 * - fillForm(form, data): preenche um objeto reativo com dados válidos
 * - clearForm(form, defaults): limpa um objeto reativo para valores padrão
 * 
 * Uso:
 *   import { useFormFill } from '@/composables/useFormFill'
 *   const { fillForm, clearForm } = useFormFill()
 *   fillForm(form, { name: 'João', email: 'joao@teste.com' })
 *   clearForm(form, { name: '', email: '', active: true })
 */

export function useFormFill() {
  /**
   * Preenche um objeto reativo (reactive ou ref) com os dados fornecidos
   * @param {Object} form - Objeto reativo a ser preenchido
   * @param {Object} data - Dados a serem inseridos no formulário
   */
  function fillForm(form, data) {
    if (!form || !data) return
    Object.keys(data).forEach((key) => {
      if (key in form) {
        form[key] = data[key]
      }
    })
  }

  /**
   * Limpa um objeto reativo para os valores padrão
   * @param {Object} form - Objeto reativo a ser limpo
   * @param {Object} defaults - Valores padrão (opcional, usa string vazia se omitido)
   */
  function clearForm(form, defaults = null) {
    if (!form) return
    if (defaults) {
      Object.keys(defaults).forEach((key) => {
        if (key in form) {
          form[key] = defaults[key]
        }
      })
    } else {
      Object.keys(form).forEach((key) => {
        const val = form[key]
        if (typeof val === 'string') form[key] = ''
        else if (typeof val === 'number') form[key] = 0
        else if (typeof val === 'boolean') form[key] = false
        else if (val === null) form[key] = null
        else form[key] = ''
      })
    }
  }

  return { fillForm, clearForm }
}
