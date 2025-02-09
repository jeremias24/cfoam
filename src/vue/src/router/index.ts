import { createRouter, createWebHistory, type RouteRecordRaw } from "vue-router";
import { useAuthStore } from "@/assets/template/stores/auth";
import { useConfigStore } from "@/assets/template/stores/config";

const routes: Array<RouteRecordRaw> = [
    {
        path: "/",
        redirect: { name: "dashboard" },
        meta: { middleware: "auth" },
    },
    {
        path: "/",
        component: () => import("@/assets/template/layouts/default-layout/DefaultLayout.vue"),
        children: [
            {
                path: "/dashboard",
                name: "dashboard",
                component: () => import("@/views/Dashboard.vue"),
                meta: {
                    pageTitle: "Dashboard",
                    middleware: "auth"
                },
            },
            {
                path: "/analyze",
                name: "analyze",
                component: () => import("@/views/Analyze.vue"),
                meta: {
                    pageTitle: "Analyze",
                    middleware: "auth"
                },
            },
        ]
    },
    {
        path: "/auth",
        component: () => import("@/assets/template/layouts/default-layout/AuthLayout.vue"),
        children: [
            {
                path: "/sign-in",
                name: "signIn",
                component: () =>
                    import("@/views/authentication/SignIn.vue"),
                meta: {
                    pageTitle: "Sign In",
                },
            },
        ],
    },
    {
        path: "/login",
        component: () => import("@/assets/template/layouts/plain-layout/PlainLayout.vue"),
        children: [
            {
                path: "/logout",
                name: "logout",
                component: () =>
                    import("@/views/authentication/Logout.vue"),
                meta: {
                    pageTitle: "Logout",
                },
            },
        ]
    },
    {
        path: "/error",
        component: () => import("@/assets/template/layouts/default-layout/SystemLayout.vue"),
        children: [
            {
                // the 404 route, when none of the above matches
                path: "/404",
                name: "pageNotFound",
                component: () => import("@/views/errors/PageNotFound.vue"),
                meta: {
                    pageTitle: "Page Not Found",
                },
            },
            {
                path: "/500",
                name: "serverError",
                component: () => import("@/views/authentication/Error500.vue"),
                meta: {
                    pageTitle: "Internal Server Error",
                },
            },
        ],
    },
    {
        path: "/:pathMatch(.*)*",
        redirect: { name: "pageNotFound", },
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
    scrollBehavior(to) {
        // If the route has a hash, scroll to the section with the specified ID; otherwise, scroll toc the top of the page.
        if (to.hash) {
            return {
                el: to.hash,
                top: 80,
                behavior: "smooth",
            };
        } else {
            return {
                top: 0,
                left: 0,
                behavior: "smooth",
            };
        }
    },
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    const configStore = useConfigStore();

    // current page view title
    document.title = `${to.meta.pageTitle} - ${import.meta.env.VITE_APP_NAME}`;
    // reset config to initial state
    configStore.resetLayoutConfig();

    // verify auth token before each page change
    authStore.verifyAuth();

    // Check if the user is already authenticated
    if (authStore.isAuthenticated) {
        // Redirect authenticated users away from the login page
        if (to.name === 'signIn') {
            next({ name: 'dashboard' });
            return;
        }
    }

    // before page access check if page requires authentication
    if (to.meta.middleware == "auth") {
        if (authStore.isAuthenticated) {
            next();
        } else {
            next({ name: "signIn" });
        }
    } else {
        next();
    }
});

export default router;