import currencyService from '../api/currency';

export default {
  state: {
    baseCurrency: 'EUR',
    currencyConversation: {},
    fetchingCurrencyRates: false,
  },
  actions: {
    async fetchCurrencyConversation({ commit }) {
      commit('fetchingRates');
      let result = null;
      try {
        const response = await currencyService.getConversation();
        commit('setBaseCurrency', response.data.baseCurrency);
        commit('setCurrencyRates', response.data.rates);
        result = response.data;
      } catch (e) {
        commit('setError', e.response.data.error);
      }
      commit('fetchedRates');

      return result;
    },
  },
  mutations: {
    fetchingRates(state) {
      state.fetchingCurrencyRates = true;
    },
    fetchedRates(state) {
      state.fetchingCurrencyRates = false;
    },
    setBaseCurrency(state, baseCurrency) {
      state.baseCurrency = baseCurrency;
    },
    setCurrencyRates(state, rates) {
      state.currencyConversation = rates;
    },
  },
  getters: {
    baseCurrency: (state) => state.baseCurrency,
    currencyConversation: (state) => state.currencyConversation,
    currencyCodes: (state) => Object.keys(state.currencyConversation),
    sortedCurrencyCodes: (state, getters, rootState, rootGetters) => {
      const codes = getters.currencyCodes;
      const { defaultCurrency } = rootGetters;

      if (defaultCurrency) {
        return codes.sort((a, b) => {
          if (a === defaultCurrency) {
            return -1;
          }
          if (b === defaultCurrency) {
            return 1;
          }
          return 0;
        });
      }

      return codes;
    },
    fetchingCurrencies: (state) => state.fetchingCurrencyRates,
  },
};
