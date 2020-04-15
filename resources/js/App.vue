<template>
  <div role="main">
    <component :is="layout">
    </component>
  </div>
</template>

<script>
  import AuthLayout from './layouts/AuthLayout';
  import MainLayout from './layouts/MainLayout';
  import { authService } from './api/auth';

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
    mounted() {
      authService.authUser();
    }
  }
</script>

<style scoped>

</style>
