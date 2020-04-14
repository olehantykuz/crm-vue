import Vue from 'vue';
import Vuelidate from 'vuelidate';
import './bootstrap';

import router from './router';
import store from './store';
import dateFilter from './filters/date.filter';
import messagePlugin from './utils/message.plugin';

import App from './App';

Vue.use(Vuelidate);
Vue.use(messagePlugin);
Vue.filter('date', dateFilter);

const app = new Vue({
  el: '#app',
  store,
  router,
  render: h => h(App)
});
