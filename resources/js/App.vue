<template>
  <div role="main">
    <component :is="layout">
    </component>
  </div>
</template>

<script>
  import AuthLayout from './layouts/AuthLayout';
  import MainLayout from './layouts/MainLayout';
  import { isAuth } from './helpers';

  export default {
    name: "App",
    components: {
      AuthLayout,
      MainLayout
    },
    computed: {
      layout() {
        return (this.$route.meta.layout || 'auth') + '-layout';
      }
    },
    methods: {
      async getAuthUser() {
        await this.$store.dispatch('getAuthUser');
      },
    },
    mounted() {
      if (isAuth()) {
        this.getAuthUser();
      }
    }
  }
</script>

<style scoped>

</style>
