import Vue from 'vue';
import Router from 'vue-router';

import Home from '../views/Home';

Vue.use(Router);

const router = new Router({
  mode: 'history',
  routes: [
    {name: 'home', path: '/', component: Home},
  ]
});

export default router;
