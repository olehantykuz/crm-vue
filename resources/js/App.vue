<template>
  <div role="main">
    <component :is="layout">
    </component>
  </div>
</template>

<script>
import { mapActions } from 'vuex';

import AuthLayout from './layouts/AuthLayout';
import MainLayout from './layouts/MainLayout';

export default {
  name: 'App',
  components: {
    AuthLayout,
    MainLayout,
  },
  computed: {
    layout() {
      return `${(this.$route.meta.layout || 'auth')}-layout`;
    },
  },
  methods: {
    ...mapActions(['fetchCurrencyConversation']),
  },
  async mounted() {
    this.currency = await this.fetchCurrencyConversation();
  },
};
</script>

<style scoped>

</style>
