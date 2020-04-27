import categoryService from '../api/category';

export default {
  state: {
    requestCreateCategory: false,
    requestUpdateCategory: false,
    requestFetchCategories: false,
    categories: [],
    current: null,
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
        commit('addCategory', category);
        commit('setCurrent', category.id);
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
        commit('updateCategory', category);

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
        commit('setCategories', categories);
        if (categories.length) {
          commit('setCurrent', categories[0].id);
        }
      } catch (e) {
        commit('setError', e.response.data.error);
        commit('finishRequestFetchCategories');
        throw e;
      }
    },
    addNewCategory({ commit }, category) {
      commit('addCategory', category);
    },
    setCurrentCategory({ commit }, id) {
      commit('setCurrent', id);
    },
    clearCategories({ commit }) {
      commit('clearCategories');
      commit('clearCurrent');
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
    setCategories(state, categories) {
      state.categories = categories;
    },
    addCategory(state, category) {
      const { categories } = state;
      categories.push(category);
      state.categories = categories;
    },
    updateCategory(state, category) {
      const categories = state.categories.map((cat) => (cat.id === category.id ? category : cat));
      state.categories = categories;
    },
    clearCategories(state) {
      state.categories = [];
    },
    setCurrent(state, id) {
      state.current = id;
    },
    clearCurrent(state) {
      state.current = null;
    },
  },
  getters: {
    creatingCategory: (s) => s.requestCreateCategory,
    fetchingCategories: (s) => s.requestFetchCategories,
    categories: (s) => s.categories,
    current: (s) => s.current,
  },
};
