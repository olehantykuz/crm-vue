import { authService } from '../api/auth';
import router from '../router';

export default {
  actions: {
    async login({ dispatch, commit }, { email, password }) {
      try {
        commit('clearError');
        await authService.login({ email, password });
        await router.push({ name: 'home' });
      } catch (e) {
        commit('setError', e.response.data.error);
      }
    },
    async register({ dispatch, commit }, { email, password, name }) {
      try {
        commit('clearError');
        await authService.register({ email, password, name });
        await router.push({ name: 'login', query: { message: 'register' } });
      } catch (e) {
        commit('setError', e.response.data.error);
      }
    },
    async logout({ dispatch, commit }) {
      try {
        commit('clearError');
        await authService.logout();
        await router.push({name: 'login', query: {message: 'logout'}});
      } catch (e) {
        commit('setError', e.response.data.error);
      }
    },
  }
};
