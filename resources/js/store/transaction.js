import transactionService from '../api/transaction';

export default {
  state: {
    requestCreateTransaction: false,
    requestFetchTransactions: false,
    transactions: [],
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
  },
  getters: {
    transactionsList: (s) => s.transactions,
  },
};
