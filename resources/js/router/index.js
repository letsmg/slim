import { createRouter, createWebHistory } from 'vue-router'

// Lazy load das páginas para melhor performance
const HomePage = () => import('@pages/HomePage.vue')
const DashboardPage = () => import('@pages/DashboardPage.vue')
const UserIndex = () => import('@user/UserIndex.vue')
const ProductIndex = () => import('@product/ProductIndex.vue')
const ReportIndex = () => import('@report/ReportIndex.vue')

/**
 * Configuração de rotas do Vue Router
 * Cada módulo tem sua própria categoria em subpasta pages/
 * 
 * Rotas públicas (layout Header/Footer padrão):
 *   /, /login, /register
 * 
 * Rotas autenticadas (layout AuthHeader/AuthFooter):
 *   /dashboard, /usuarios, /produtos, /relatorios
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
        component: HomePage, // Placeholder - implementar LoginPage futuramente
        meta: { title: 'Login', auth: false },
    },
    {
        path: '/register',
        name: 'register',
        component: HomePage, // Placeholder - implementar RegisterPage futuramente
        meta: { title: 'Cadastro', auth: false },
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
 * Guard de navegação: atualiza título da página
 * Em produção, aqui também seria verificado token de autenticação
 */
router.beforeEach((to, from, next) => {
    // Atualiza título da página
    document.title = to.meta?.title
        ? `${to.meta.title} | Slim App`
        : 'Slim App'

    // TODO: implementar verificação de autenticação real
    // Se a rota requer auth e usuário não está logado, redirecionar para /login
    // if (to.meta?.auth && !isAuthenticated()) {
    //     return next({ name: 'login' })
    // }

    next()
})

export default router