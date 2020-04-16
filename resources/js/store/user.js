import { authService } from '../api/auth';

export default {
  state: {
    info: {},
  },
  actions: {
    async getAuthUser({ dispatch, commit }) {
      try {
        commit('clearError');
        const response = await authService.authUser();
        commit('setUserInfo', response.data.user);
      } catch (e) {
        commit('setError', e.response.data.error);
      }
    },
  },
  mutations: {
    setUserInfo(state, userInfo) {
      state.info = userInfo;
    }
  },
  getters: {
    info: s => s.info,
  },
};
