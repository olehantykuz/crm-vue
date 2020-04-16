<template>
  <div class="grey darken-1 empty-layout">
    <router-view></router-view>
  </div>
</template>

<script>
  export default {
    name: 'AuthLayout',
    computed: {
      error() {
        return this.$store.getters.error;
      }
    },
    watch: {
      error(err) {
        if (err) {
          this.$error(err);
        }
      }
    }
  }
</script>

<style scoped>

</style>
