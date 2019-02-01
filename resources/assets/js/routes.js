import Login from './components/Auth/Login.vue';

export const routes = [
   {
      path: '/login',
      name: 'login',
      component: Login,
   },
   {
      path: '/dashboard',
      name: 'dashboard',
      component: require('./components/Dashboard.vue'),
      meta: {
            requiresAuth: true
      },
   },
   {
      path: '/recording',
      name: 'recording',
      component: require('./components/Recording.vue'),
      meta: {
            requiresAuth: true
      },
   },
   {
      path: '/records',
      name: 'records',
      component: require('./components/Records.vue'),
      meta: {
            requiresAuth: true
      },
   },

]