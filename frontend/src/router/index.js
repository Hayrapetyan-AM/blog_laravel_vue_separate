import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import DashboardView from "@/views/DashboardView.vue";
import BlogCreateView from "@/views/BlogCreateView.vue";
import BlogShowView from "@/views/BlogShowView.vue";
import BlogViewView from "@/views/BlogViewView.vue";
import BlogEditView from '@/views/BlogEditView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView,
    },
    {
      path: '/blog/create',
      name: 'blogCreate',
      component: BlogCreateView,
    },
    {
      path: '/blog/show',
      name: 'blogShow',
        component: BlogShowView,
    },
    {
      path: '/blog/view/:id',
      name: 'blogView',
      component: BlogViewView,
    },
    {
      path: '/blog/edit/:id',
      name: 'blogEdit',
      component: BlogEditView,
    }

  ],
})

export default router
