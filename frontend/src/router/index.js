import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('../views/HomeView.vue')
  },
  {
    path: '/film/:id',
    name: 'film',
    component: () => import('../views/FilmView.vue')
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('../views/ProfileView.vue')
  },
  {
    path: '/auth',
    name: 'auth',
    component: () => import('../views/AuthorizationView.vue')
  },
  {
    path: '/filters',
    name: 'filters',
    component: () => import('../views/NavigatorView.vue')
  },
  {
    path: '/later',
    name: 'later',
    component: () => import('../views/WatchLaterView.vue')
  },
  {
    path: '/trands',
    name: 'trands',
    component: () => import('../views/TrandsView.vue')
  },
  {
    path: '/playlists',
    name: 'playlists',
    component: () => import('../views/PlaylistsView.vue')
  },
  {
    path: '/playlist/:id',
    name: 'playlist',
    component: () => import('../views/PlaylistView.vue')
  },
  {
    path: '/searchedFilms/:title',
    name: 'searchedFilms',
    component: () => import('../views/SearchView.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
