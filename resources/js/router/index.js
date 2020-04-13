import Vue from 'vue';
import Router from 'vue-router';

import Home from '../views/Home';

Vue.use(Router);

const router = new Router({
  mode: 'history',
  routes: [
    {
      name: 'home',
      path: '/',
      meta: {layout: 'main'},
      component: Home
    },
    {
      path: '/login',
      name: 'login',
      meta: {layout: 'auth'},
      component: () => import('../views/Login')
    },
    {
      path: '/register',
      name: 'register',
      meta: {layout: 'auth'},
      component: () => import('../views/Register')
    },
    {
      path: '/categories',
      name: 'categories',
      meta: {layout: 'main'},
      component: () => import('../views/Categories')
    },
    {
      path: '/detail-record',
      name: 'detail-record',
      meta: {layout: 'main'},
      component: () => import('../views/DetailRecord')
    },
    {
      path: '/history',
      name: 'history',
      meta: {layout: 'main'},
      component: () => import('../views/History')
    },
    {
      path: '/planning',
      name: 'planning',
      meta: {layout: 'main'},
      component: () => import('../views/Planning')
    },
    {
      path: '/profile',
      name: 'profile',
      meta: {layout: 'main'},
      component: () => import('../views/Profile')
    },
    {
      path: '/record',
      name: 'record',
      meta: {layout: 'main'},
      component: () => import('../views/Record')
    }
  ]
});

export default router;
