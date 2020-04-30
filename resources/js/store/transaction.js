import transactionService from '../api/transaction';

export default {
  state: {
    requestCreateTransaction: false,
    requestFetchTransactions: false,
    requestFetchTransactionsTotals: false,
    transactions: [],
    totals: {},
  },
  actions: {
    async createTransaction({ commit }, { categoryId, data }) {
      commit('clearError');
      commit('sendingRequestCreateTransaction');
      try {
        const response = await transactionService.create(categoryId, data);
        commit('finishRequestCreateTransaction');
        commit('addTransaction', response.data.transaction);
      } catch (e) {
        commit('setError', e.response.data.error);
        commit('finishRequestCreateTransaction');
        throw e;
      }
    },
    async fetchTotals({ commit }, { month, year }) {
      commit('clearError');
      commit('sendingRequestFetchTransactionsTotals');
      try {
        const response = await transactionService.getTotals(month, year);
        commit('finishRequestFetchTransactionsTotals');
        commit('setTotalsByDate', response.data.totals);
      } catch (e) {
        commit('setError', e.response.data.error);
        commit('finishRequestFetchTransactionsTotals');
        throw e;
      }
    },
  },
  mutations: {
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
    sendingRequestFetchTransactionsTotals(state) {
      state.requestFetchTransactionsTotals = true;
    },
    finishRequestFetchTransactionsTotals(state) {
      state.requestFetchTransactionsTotals = false;
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
  },
  getters: {
    transactionsList: (s) => s.transactions,
  },
};
