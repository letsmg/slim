/**
 * Composables para auxiliar formulários
 * 
 * Fornece funções para preenchimento automático e limpeza de formulários
 * baseado nos dados dos seeders do sistema.
 */

// Dados dos seeders para preenchimento automático de formulários
export const seedData = {
  users: [
    { email: 'admin@slimapp.com', password: '123456', name: 'Admin', level: 'admin' },
    { email: 'joao@slimapp.com', password: '123456', name: 'João Silva', level: 'operational' },
    { email: 'maria@slimapp.com', password: '123456', name: 'Maria Souza', level: 'operational' },
    { email: 'carlos@slimapp.com', password: '123456', name: 'Carlos Pereira', level: 'support' },
    { email: 'ana@slimapp.com', password: '123456', name: 'Ana Oliveira', level: 'support' },
  ],
  vehicles: [
    { marca: 'Fiat', modelo: 'Toro', eixos: 2, crlv: 'CRLV-2026-001', tipo_combustivel: 'Diesel', dt_ultima_revisao: '2026-01-15', dt_proxima_revisao: '2026-07-15', dt_compra: '2023-06-01' },
    { marca: 'Volkswagen', modelo: 'Constellation', eixos: 3, crlv: 'CRLV-2026-002', tipo_combustivel: 'Diesel', dt_ultima_revisao: '2026-02-20', dt_proxima_revisao: '2026-08-20', dt_compra: '2022-03-15' },
    { marca: 'Mercedes-Benz', modelo: 'Actros', eixos: 3, crlv: 'CRLV-2026-003', tipo_combustivel: 'Diesel', dt_ultima_revisao: '2026-03-10', dt_proxima_revisao: '2026-09-10', dt_compra: '2024-01-20' },
    { marca: 'Scania', modelo: 'R440', eixos: 3, crlv: 'CRLV-2026-004', tipo_combustivel: 'Diesel', dt_ultima_revisao: '2026-04-05', dt_proxima_revisao: '2026-10-05', dt_compra: '2021-08-10' },
    { marca: 'Ford', modelo: 'Transit', eixos: 2, crlv: 'CRLV-2026-005', tipo_combustivel: 'Gasolina', dt_ultima_revisao: '2026-05-01', dt_proxima_revisao: '2026-11-01', dt_compra: '2023-11-25' },
  ],
  drivers: [
    { name: 'Pedro Alves', document: '123.456.789-00', cnh: '12345678901', phone: '(11) 99999-0001', email: 'pedro@exemplo.com', active: true },
    { name: 'Lucas Santos', document: '987.654.321-00', cnh: '98765432101', phone: '(11) 99999-0002', email: 'lucas@exemplo.com', active: true },
    { name: 'Fernanda Lima', document: '456.789.123-00', cnh: '45678912301', phone: '(11) 99999-0003', email: 'fernanda@exemplo.com', active: true },
    { name: 'Roberto Costa', document: '321.654.987-00', cnh: '32165498701', phone: '(11) 99999-0004', email: 'roberto@exemplo.com', active: false },
    { name: 'Juliana Martins', document: '654.321.789-00', cnh: '65432178901', phone: '(11) 99999-0005', email: 'juliana@exemplo.com', active: true },
  ],
  mechanics: [
    { name: 'Oficina do João', address: 'Rua das Oficinas, 100', document: '11.222.333/0001-01', phone1: '(11) 98888-0001', phone2: '(11) 97777-0001' },
    { name: 'Mecânica do Zé', address: 'Av. dos Mecânicos, 200', document: '22.333.444/0001-02', phone1: '(11) 98888-0002', phone2: '' },
    { name: 'Auto Center SP', address: 'Rua das Peças, 300', document: '33.444.555/0001-03', phone1: '(11) 98888-0003', phone2: '(11) 97777-0003' },
    { name: 'Oficina do Paulo', address: 'Av. das Reparações, 400', document: '44.555.666/0001-04', phone1: '(11) 98888-0004', phone2: '' },
    { name: 'Mecânica Express', address: 'Rua Rápida, 500', document: '55.666.777/0001-05', phone1: '(11) 98888-0005', phone2: '(11) 97777-0005' },
  ],
  trips: [
    { driver_id: 1, vehicle_id: 1, departure_forecast: '2026-05-19 08:00', arrival_forecast: '2026-05-21 18:00', origin: 'São Paulo, SP', destination: 'Rio de Janeiro, RJ', status: 'scheduled' },
    { driver_id: 2, vehicle_id: 2, departure_forecast: '2026-05-20 06:00', arrival_forecast: '2026-05-23 14:00', origin: 'Belo Horizonte, MG', destination: 'Brasília, DF', status: 'scheduled' },
    { driver_id: 3, vehicle_id: 3, departure_forecast: '2026-05-16 10:00', arrival_forecast: '2026-05-19 16:00', origin: 'Curitiba, PR', destination: 'Porto Alegre, RS', status: 'in_progress' },
    { driver_id: 1, vehicle_id: 5, departure_forecast: '2026-05-13 07:00', arrival_forecast: '2026-05-15 12:00', origin: 'São Paulo, SP', destination: 'Campinas, SP', status: 'completed' },
    { driver_id: 4, vehicle_id: 4, departure_forecast: '2026-05-23 09:00', arrival_forecast: '2026-05-25 20:00', origin: 'Salvador, BA', destination: 'Recife, PE', status: 'cancelled' },
  ],
  maintenances: [
    { driver_id: 1, vehicle_id: 1, mechanic_id: 1, scheduled_date: '2026-05-25', scheduled_time: '08:00', contact: '(11) 98888-0001', service: 'Troca de óleo e filtros', observations: 'Utilizar óleo sintético 5W30', completed: false, paid: false },
    { driver_id: 2, vehicle_id: 2, mechanic_id: 2, scheduled_date: '2026-06-01', scheduled_time: '10:00', contact: '(11) 98888-0002', service: 'Revisão geral dos freios', observations: 'Verificar pastilhas e discos', completed: false, paid: false },
    { driver_id: 3, vehicle_id: 3, mechanic_id: 3, scheduled_date: '2026-05-15', scheduled_time: '14:00', contact: '(11) 98888-0003', service: 'Alinhamento e balanceamento', observations: 'Verificar geometria da suspensão', completed: true, paid: true },
    { driver_id: 1, vehicle_id: 5, mechanic_id: 4, scheduled_date: '2026-05-08', scheduled_time: '09:00', contact: '(11) 98888-0004', service: 'Troca de pneus', observations: '4 pneus novos, medida 205/55R16', completed: true, paid: false },
    { driver_id: 5, vehicle_id: 1, mechanic_id: 5, scheduled_date: '2026-06-08', scheduled_time: '16:00', contact: '(11) 98888-0005', service: 'Revisão completa 50.000 km', observations: 'Incluir troca de correia dentada', completed: false, paid: false },
  ],
}

/**
 * Gera um índice aleatório baseado no tamanho do array
 */
function randomIndex(arrayLength) {
  return Math.floor(Math.random() * arrayLength)
}

/**
 * Preenche um objeto reativo com dados aleatórios do seed correspondente
 * @param {object} form - Objeto reativo do Vue (reactive ou ref)
 * @param {string} entity - Nome da entidade (users, vehicles, drivers, etc.)
 * @param {number} [index] - Índice específico (opcional, aleatório se omitido)
 */
export function fillForm(form, entity, index) {
  const data = seedData[entity]
  if (!data || data.length === 0) return

  const idx = index !== undefined ? index : randomIndex(data.length)
  const selected = data[idx % data.length]

  Object.keys(selected).forEach((key) => {
    if (key in form) {
      form[key] = selected[key]
    }
  })
}

/**
 * Limpa todos os campos de um objeto reativo
 * @param {object} form - Objeto reativo do Vue
 * @param {string[]} [keepFields] - Campos que não devem ser limpos
 */
export function clearForm(form, keepFields = []) {
  Object.keys(form).forEach((key) => {
    if (!keepFields.includes(key)) {
      if (typeof form[key] === 'boolean') {
        form[key] = false
      } else if (typeof form[key] === 'number') {
        form[key] = 0
      } else {
        form[key] = ''
      }
    }
  })
}
