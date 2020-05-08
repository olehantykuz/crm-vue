import transactionService from '../api/transaction';

const defaultState = () => ({
  requestCreateTransaction: false,
  requestFetchTransactions: false,
  requestFetchTransaction: false,
  interval: null,
  transactions: [],
  totals: {},
  defaultCurrency: null,
  defaultMonthlyBill: 0,
});

export default {
  state: defaultState(),
  actions: {
    async createTransaction({ commit }, { categoryId, data }) {
      commit('clearError');
      commit('sendingRequestCreateTransaction');
      try {
        const response = await transactionService.create(categoryId, data);
        commit('addTransaction', response.data.transaction);
        commit('setTotalsByDate', response.data.totals);
        commit('finishRequestCreateTransaction');
      } catch (e) {
        commit('setError', e.response.data.error);
        commit('finishRequestCreateTransaction');
        throw e;
      }
    },
    async fetchTransactions({ commit }, { month, year }) {
      commit('clearError');
      commit('sendingRequestFetchTransactions');
      try {
        const response = await transactionService.getList(month, year);
        commit('setTransactions', response.data.transactions);
        commit('setTotalsByDate', response.data.totals);
        commit('setCurrentInterval', { month, year });
        commit('finishRequestFetchTransactions');
      } catch (e) {
        commit('setError', e.response.data.error);
        commit('finishRequestFetchTransactions');
        throw e;
      }
    },
    async fetchTransaction({ commit }, id) {
      commit('clearError');
      commit('sendingRequestFetchTransaction');
      try {
        const response = await transactionService.getById(id);
        commit('finishRequestFetchTransaction');

        return response.data.transaction;
      } catch (e) {
        commit('setError', e.response.data.error || e.response.data.message);
        commit('finishRequestFetchTransaction');
        throw e;
      }
    },
    setDefaultBudget({ commit }, { currency, total }) {
      commit('setDefaultCurrency', currency);
      commit('setDefaultMonthlyBill', total);
    },
  },
  mutations: {
    setDefaultCurrency(state, currency) {
      state.defaultCurrency = currency;
    },
    setDefaultMonthlyBill(state, defaultValue) {
      state.defaultMonthlyBill = defaultValue;
    },
    setCurrentInterval(state, { month, year }) {
      state.interval = `${year}_${month}`;
    },
    sendingRequestCreateTransaction(state) {
      state.requestCreateTransaction = true;
    },
    finishRequestCreateTransaction(state) {
      state.requestCreateTransaction = false;
    },
    sendingRequestFetchTransactions(state) {
      state.requestFetchTransactions = true;
    },
    finishRequestFetchTransactions(state) {
      state.requestFetchTransactions = false;
    },
    sendingRequestFetchTransaction(state) {
      state.requestFetchTransaction = true;
    },
    finishRequestFetchTransaction(state) {
      state.requestFetchTransaction = false;
    },
    setTransactions(state, transactions) {
      state.transactions = transactions;
    },
    addTransaction(state, transaction) {
      const { transactions } = state;
      transactions.push(transaction);
      state.transactions = transactions;
    },
    clearTransactions(state) {
      state.transactions = [];
    },
    setTotalsByDate(state, data) {
      const { amounts, date } = data;
      const { totals } = state;
      let key = 'all';
      if (date.year) {
        key = date.month ? `${date.year}_${date.month}` : date.year;
      }
      totals[key] = amounts;
      state.totals = totals;
    },
    clearTransactionTotals(state) {
      state.totals = {};
    },
    clearBill(state) {
      Object.assign(state, defaultState());
    },
  },
  getters: {
    transactionsList: (s) => s.transactions,
    totals: (s) => s.totals,
    base: (state, getters, rootState) => {
      const { defaultCurrency, defaultMonthlyBill } = state;
      const { currencyConversation: rates, baseCurrency } = rootState.currency;

      return defaultCurrency && Object.keys(rates).length
        ? defaultMonthlyBill / (rates[defaultCurrency].rate / rates[baseCurrency].rate)
        : null;
    },
    billInCurrencies: (state, getters, rootState, rootGetters) => (interval) => {
      const result = [];
      const { currencyConversation: rates, sortedCurrencyCodes } = rootGetters;
      const { totals } = state;
      const { base } = getters;

      function getBillInCurrency(code) {
        const key = interval;
        if (!key) {
          return base * rates[code].rate;
        }
        let sumTransactions = 0;
        if (state.totals && state.totals[key]) {
          if (totals[key].income) {
            sumTransactions += state.totals[key].income[code] || 0;
          }
          if (totals[key].outcome) {
            sumTransactions -= state.totals[key].outcome[code] || 0;
          }
        }

        return (base * rates[code].rate + sumTransactions).toFixed(2);
      }

      sortedCurrencyCodes.forEach((code) => {
        result.push({ code, value: getBillInCurrency(code) });
      });

      return result;
    },
    fetchingTransactions: (s) => s.requestFetchTransactions,
    fetchingTransaction: (s) => s.requestFetchTransaction,
    currentInterval: (s) => s.interval,
    defaultCurrency: (s) => s.defaultCurrency,
    monthlyBill: (s) => s.defaultMonthlyBill,
    bill: (s, g) => g.billInCurrencies(s.interval),
  },
};
