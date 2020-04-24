import categoryService from '../api/category';

export default {
  state: {
    requestCreateCategory: false,
    requestUpdateCategory: false,
    requestFetchCategories: false,
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
    async updateCategory({ commit }, { categoryId, data }) {
      commit('clearError');
      commit('sendingRequestUpdateCategory');
      let category = null;
      try {
        const response = await categoryService.update(categoryId, data);
        category = response.data.category;
        commit('finishRequestUpdateCategory');

        return category;
      } catch (e) {
        commit('setError', e.response.data.error);
        commit('finishRequestUpdateCategory');
        throw e;
      }
    },
    async fetchCategories({ commit }) {
      commit('clearError');
      commit('sendingRequestFetchCategories');
      let categories = [];
      try {
        const response = await categoryService.fetchAll();
        categories = response.data.categories;
        commit('finishRequestFetchCategories');

        return categories;
      } catch (e) {
        commit('setError', e.response.data.error);
        commit('finishRequestFetchCategories');
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
    sendingRequestUpdateCategory(state) {
      state.requestUpdateCategory = true;
    },
    finishRequestUpdateCategory(state) {
      state.requestUpdateCategory = false;
    },
    sendingRequestFetchCategories(state) {
      state.requestFetchCategories = true;
    },
    finishRequestFetchCategories(state) {
      state.requestFetchCategories = false;
    },
  },
  getters: {
    creatingCategory: (s) => s.requestCreateCategory,
    fetchingCategories: (s) => s.requestFetchCategories,
  },
};
