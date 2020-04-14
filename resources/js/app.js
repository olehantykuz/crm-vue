import Vue from 'vue';
import Vuelidate from 'vuelidate';

import router from './router';
import store from './store';
import './bootstrap';
import dateFilter from './filters/date.filter';

import App from './App';

Vue.use(Vuelidate);
Vue.filter('date', dateFilter);

const app = new Vue({
  el: '#app',
  store,
  router,
  render: h => h(App)
});
