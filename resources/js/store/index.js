import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth';
import user from './user';
import currency from './currency';
import category from './category';
import transaction from './transaction';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    error: null,
  },
  actions: {},
  mutations: {
    setError(state, error) {
      state.error = error;
    },
    clearError(state) {
      state.error = null;
    },
  },
  getters: {
    error: (state) => state.error,
  },
  modules: {
    auth,
    user,
    currency,
    category,
    transaction,
  },
});

export default store;
