import authService from '../api/auth';
import router from '../router';

export default {
  actions: {
    async login({ commit }, { email, password }) {
      try {
        commit('clearError');
        await authService.login({ email, password });
        await router.push({ name: 'home' });
      } catch (e) {
        commit('setError', e.response.data.error);
      }
    },
    async register({ commit }, {
      email, password, name, monthlyBudget, currencyId,
    }) {
      try {
        commit('clearError');
        await authService.register({
          email, password, name, monthlyBudget, currencyId,
        });
        await router.push({ name: 'login', query: { message: 'register' } });
      } catch (e) {
        commit('setError', e.response.data.error);
      }
    },
    async logout({ commit }) {
      try {
        commit('clearError');
        await authService.logout();
        commit('clearUserInfo');
        commit('clearBill');
      } catch (e) {
        commit('setError', e.response.data.error);
      } finally {
        await router.push({ name: 'login', query: { message: 'logout' } });
      }
    },
  },
};
