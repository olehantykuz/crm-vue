import { authService } from '../api/auth';
import router from '../router';

export default {
  actions: {
    async login({ dispatch, commit }, { email, password }) {
      try {
        await authService.login({ email, password });
        console.log('push');
        await router.push({ name: 'home' });
      } catch (e) {
        console.log({ e });
      }
    },
    async register({ dispatch, commit }, { email, password, name }) {
      try {
        await authService.register({ email, password, name });
        await router.push({ name: 'login', query: { message: 'register' } });
      } catch (e) {
        console.log({ e });
      }
    },
    async getAuthUser({ dispatch, commit }) {
      try {
        await authService.authUser();
      } catch (e) {
        console.log({ e });
      }
    },
    async logout() {
      try {
        await authService.logout();
        await router.push({name: 'login', query: {message: 'logout'}});
      } catch (e) {
        console.log({ e });
      }
    },
  }
};
