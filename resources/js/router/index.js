import { createRouter, createWebHistory } from 'vue-router'

// Lazy load das páginas para melhor performance
const HomePage = () => import('@pages/HomePage.vue')
const LoginPage = () => import('@pages/LoginPage.vue')
const RegisterPage = () => import('@pages/RegisterPage.vue')
const TermosDeUso = () => import('@pages/TermosDeUso.vue')
const PoliticaPrivacidade = () => import('@pages/PoliticaPrivacidade.vue')
const DashboardPage = () => import('@pages/DashboardPage.vue')
const UserIndex = () => import('@user/UserIndex.vue')
const ProductIndex = () => import('@product/ProductIndex.vue')
const ReportIndex = () => import('@report/ReportIndex.vue')
const VehicleIndex = () => import('@vehicle/VehicleIndex.vue')
const DriverIndex = () => import('@driver/DriverIndex.vue')
const MechanicIndex = () => import('@mechanic/MechanicIndex.vue')
const TripIndex = () => import('@trip/TripIndex.vue')
const ScheduledMaintenanceIndex = () => import('@maintenance/ScheduledMaintenanceIndex.vue')

/**
 * Configuração de rotas do Vue Router
 * Cada módulo tem sua própria categoria em subpasta pages/
 * 
 * Rotas públicas (layout Header/Footer padrão):
 *   /, /login, /register
 * 
 * Rotas autenticadas (layout AuthHeader/AuthFooter):
 *   /dashboard, /usuarios, /produtos, /relatorios, /veiculos, /motoristas, /mecanicos, /viagens, /manutencoes
 */
const routes = [
    // === Rotas Públicas (layout padrão) ===
    {
        path: '/',
        name: 'home',
        component: HomePage,
        meta: { title: 'Início', auth: false },
    },
    {
        path: '/login',
        name: 'login',
        component: LoginPage,
        meta: { title: 'Login', auth: false },
    },
    {
        path: '/register',
        name: 'register',
        component: RegisterPage,
        meta: { title: 'Cadastro', auth: false },
    },
    {
        path: '/termos-de-uso',
        name: 'termos-de-uso',
        component: TermosDeUso,
        meta: { title: 'Termos de Uso', auth: false },
    },
    {
        path: '/politica-de-privacidade',
        name: 'politica-de-privacidade',
        component: PoliticaPrivacidade,
        meta: { title: 'Política de Privacidade', auth: false },
    },

    // === Rotas Autenticadas (layout AuthHeader/AuthFooter) ===
    {
        path: '/dashboard',
        name: 'dashboard',
        component: DashboardPage,
        meta: { title: 'Dashboard', auth: true },
    },
    {
        path: '/usuarios',
        name: 'users.index',
        component: UserIndex,
        meta: { title: 'Usuários', auth: true },
    },
    {
        path: '/produtos',
        name: 'products.index',
        component: ProductIndex,
        meta: { title: 'Produtos', auth: true },
    },
    {
        path: '/relatorios',
        name: 'reports.index',
        component: ReportIndex,
        meta: { title: 'Relatórios', auth: true },
    },
    {
        path: '/veiculos',
        name: 'vehicles.index',
        component: VehicleIndex,
        meta: { title: 'Veículos', auth: true },
    },
    {
        path: '/motoristas',
        name: 'drivers.index',
        component: DriverIndex,
        meta: { title: 'Motoristas', auth: true },
    },
    {
        path: '/mecanicos',
        name: 'mechanics.index',
        component: MechanicIndex,
        meta: { title: 'Mecânicos', auth: true },
    },
    {
        path: '/viagens',
        name: 'trips.index',
        component: TripIndex,
        meta: { title: 'Viagens', auth: true },
    },
    {
        path: '/manutencoes',
        name: 'scheduled-maintenances.index',
        component: ScheduledMaintenanceIndex,
        meta: { title: 'Manutenções', auth: true },
    },

    // Rota coringa para 404
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        redirect: '/',
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

/**
 * Guard de navegação: protege rotas e redireciona conforme autenticação
 * 
 * Regras:
 * - Rotas com meta.auth = true  → só acessíveis com sessão ativa (redireciona para / se não logado)
 * - Rotas com meta.auth = false → só acessíveis sem sessão ativa (redireciona para /dashboard se logado)
 * - Rotas sem meta.auth definido → livres (ex: 404)
 * - A sessão é verificada via sessionStorage (setada no login)
 */
router.beforeEach((to, from, next) => {
    // Atualiza título da página
    document.title = to.meta?.title
        ? `${to.meta.title} | Slim App`
        : 'Slim App'

    const isAuthenticated = !!sessionStorage.getItem('auth_token')

    // Se a rota requer autenticação e o usuário não está logado → redireciona para home
    if (to.meta?.auth === true && !isAuthenticated) {
        return next({ name: 'home' })
    }

    // Se a rota é pública (auth: false) e o usuário está logado → redireciona para dashboard
    if (to.meta?.auth === false && isAuthenticated) {
        return next({ name: 'dashboard' })
    }

    next()
})

export default router
