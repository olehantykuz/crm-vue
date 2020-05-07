import Vue from 'vue';
import Router from 'vue-router';

import { isAuth } from '../helpers';

import Login from '../views/Login';
import Register from '../views/Register';
import Home from '../views/Home';
import Categories from '../views/Categories';
import Detail from '../views/Detail';
import History from '../views/History';
import Planning from '../views/Planning';
import Profile from '../views/Profile';
import Transaction from '../views/Transaction';

Vue.use(Router);

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/login',
      name: 'login',
      meta: { layout: 'auth' },
      component: Login,
    },
    {
      path: '/register',
      name: 'register',
      meta: { layout: 'auth' },
      component: Register,
    },
    {
      name: 'home',
      path: '/',
      meta: { layout: 'main', auth: true },
      component: Home,
    },
    {
      path: '/categories',
      name: 'categories',
      meta: { layout: 'main', auth: true },
      component: Categories,
    },
    {
      path: '/detail/:id',
      name: 'detail',
      meta: { layout: 'main', auth: true },
      component: Detail,
    },
    {
      path: '/history',
      name: 'history',
      meta: { layout: 'main', auth: true },
      component: History,
    },
    {
      path: '/planning',
      name: 'planning',
      meta: { layout: 'main', auth: true },
      component: Planning,
    },
    {
      path: '/profile',
      name: 'profile',
      meta: { layout: 'main', auth: true },
      component: Profile,
    },
    {
      path: '/transaction',
      name: 'transaction',
      meta: { layout: 'main', auth: true },
      component: Transaction,
    },
  ],
});

router.beforeEach((to, from, next) => {
  const requireAuth = to.meta.auth;

  if (!isAuth() && requireAuth) {
    next('/login?message=login');
  } else {
    next();
  }
});

export default router;
