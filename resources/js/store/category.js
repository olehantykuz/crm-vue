import categoryService from '../api/category';

export default {
  state: {
    requestCreateCategory: false,
  },
  actions: {
    async createCategory({ commit }, data) {
      commit('clearError');
      commit('sendingRequestCreateCategory');
      let category = null;
      try {
        const response = await categoryService.create(data);
        category = response.data.category;
        commit('finishRequestCreateCategory');

        return category;
      } catch (e) {
        commit('setError', e.response.data.error);
        commit('finishRequestCreateCategory');
        throw e;
      }
    },
  },
  mutations: {
    sendingRequestCreateCategory(state) {
      state.requestCreateCategory = true;
    },
    finishRequestCreateCategory(state) {
      state.requestCreateCategory = false;
    },
  },
  getters: {
    creatingCategory: (s) => s.requestCreateCategory,
  },
};
