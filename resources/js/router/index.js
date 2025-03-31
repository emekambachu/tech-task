import {
    createRouter,
    createWebHistory,
} from 'vue-router'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: () => import('@/js/layouts/UserLayout.vue'),

            children: [
                {
                    path: '/',
                    name: 'user-list',
                    component: () => import('@/js/pages/user/UserList.vue'),
                },

            ],
        },

    ],
});

router.beforeEach((to, from, next) => {
    // scroll to top on route change
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    })
    next()
})

export default router
