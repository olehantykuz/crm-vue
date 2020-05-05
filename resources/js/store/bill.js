import transactionService from '../api/transaction';

const defaultState = () => ({
  requestCreateTransaction: false,
  requestFetchTransactions: false,
  requestFetchTransactionsTotals: false,
  transactions: [],
  totals: {},
  defaultBudget: {},
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
        commit('finishRequestFetchTransactions');
      } catch (e) {
        commit('setError', e.response.data.error);
        commit('finishRequestFetchTransactions');
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
    clearBill(state) {
      Object.assign(state, defaultState());
    },
  },
  getters: {
    transactionsList: (s) => s.transactions,
    totals: (s) => s.totals,
    fetchingTotals: (s) => s.requestFetchTransactionsTotals,
    fetchingTransactions: (s) => s.requestFetchTransactions,
  },
};
