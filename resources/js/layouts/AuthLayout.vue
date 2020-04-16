<template>
  <div class="grey darken-1 empty-layout">
    <router-view></router-view>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'AuthLayout',
  computed: {
    ...mapGetters(['error']),
  },
  watch: {
    error(err) {
      if (err) {
        this.$error(err);
      }
    },
  },
};
</script>

<style scoped>

</style>
