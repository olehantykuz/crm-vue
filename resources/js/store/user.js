import authService from '../api/auth';

export default {
  state: {
    info: {},
  },
  actions: {
    async getAuthUser({ commit, dispatch }) {
      try {
        commit('clearError');
        const response = await authService.authUser();
        commit('setUserInfo', response.data.user);
        dispatch('setDefaultBudget', response.data.defaultBudget);
      } catch (e) {
        commit('setError', e.response.data.error);
        dispatch('logout');
      }
    },
  },
  mutations: {
    setUserInfo(state, userInfo) {
      state.info = userInfo;
    },
    clearUserInfo(state) {
      state.info = {};
    },
  },
  getters: {
    info: (s) => s.info,
  },
};
