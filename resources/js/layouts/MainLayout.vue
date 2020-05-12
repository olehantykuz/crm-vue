<template>
  <div class="app-main-layout">
    <navbar
      @toggle-sidebar="isOpen = !isOpen"
    ></navbar>
    <sidebar
      v-model="isOpen"
    ></sidebar>
    <main class="app-content" :class="{full: !isOpen}">
      <div class="app-page">
        <router-view></router-view>
      </div>
    </main>

    <div class="fixed-action-btn">
      <router-link
        class="btn-floating btn-large blue" :to="{name: 'record'}"
        v-tooltip="'Добавить новую транзакцию'"
      >
        <i class="large material-icons">add</i>
      </router-link>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import Navbar from '../components/app/Navbar';
import Sidebar from '../components/app/Sidebar';
import { isAuth } from '../helpers';

export default {
  name: 'MainLayout',
  components: {
    Navbar, Sidebar,
  },
  data: () => ({
    isOpen: true,
  }),
  computed: {
    ...mapGetters(['info', 'error', 'fetchingCategories', 'categories']),
  },
  methods: {
    ...mapActions(['getAuthUser', 'fetchTransactions', 'fetchCategories']),
  },
  async mounted() {
    if (isAuth()) {
      if (!Object.keys(this.info).length) {
        await this.getAuthUser();
      }
      const date = new Date();
      const monthYear = { month: date.getMonth() + 1, year: date.getFullYear() };
      try {
        if (!this.categories.length && !this.fetchingCategories) {
          await this.fetchCategories();
        }
        await this.fetchTransactions(monthYear);
      } catch (e) {
        this.$error(this.error);
      }
    }
  },
};
</script>

<style scoped>

</style>
