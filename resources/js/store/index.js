import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth';
import user from './user';
import currencyService from '../api/currency';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    error: null,
    baseCurrency: null,
    currencyConversation: {},
  },
  actions: {
    async fetchCurrencyConversation({ commit }) {
      let result = null;
      try {
        const response = await currencyService.getConversation();
        result = response.data;
      } catch (e) {
        commit('setError', e.response.data.error);
      }

      return result;
    },
  },
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
  },
});

export default store;
