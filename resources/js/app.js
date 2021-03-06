import Vue from 'vue';
import Vuelidate from 'vuelidate';
import './bootstrap';

import router from './router';
import store from './store';
import tooltipDirective from './directives/tooltip.directive';
import dateFilter from './filters/date.filter';
import currencyFilter from './filters/currency.filter';
import messagePlugin from './utils/message.plugin';
import Loader from './components/app/Loader';

import App from './App';

Vue.use(Vuelidate);
Vue.use(messagePlugin);
Vue.filter('date', dateFilter);
Vue.filter('currency', currencyFilter);
Vue.directive('tooltip', tooltipDirective);
Vue.component('Loader', Loader);

// eslint-disable-next-line no-new
new Vue({
  el: '#app',
  store,
  router,
  render: (h) => h(App),
});
