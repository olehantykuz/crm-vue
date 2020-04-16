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
      <router-link class="btn-floating btn-large blue" :to="{name: 'record'}">
        <i class="large material-icons">add</i>
      </router-link>
    </div>
  </div>
</template>

<script>
  import Navbar from '../components/app/Navbar';
  import Sidebar from '../components/app/Sidebar';
  import { isAuth } from '../helpers';

  export default {
    name: "MainLayout",
    components: {
      Navbar, Sidebar
    },
    data: () => ({
      isOpen: true
    }),
    methods: {
      async getAuthUser() {
        await this.$store.dispatch('getAuthUser');
      },
    },
    mounted() {
      if (isAuth() && !Object.keys(this.$store.getters.info).length) {
        this.getAuthUser();
      }
    }
  }
</script>

<style scoped>

</style>
