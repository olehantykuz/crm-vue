<template>
  <div>
    <div class="page-title">
      <h3>Планирование</h3>
      <h4>12 212</h4>
    </div>

    <section>
      <div>
        <p>
          <strong>Девушка:</strong>
          12 122 из 14 0000
        </p>
        <div class="progress">
          <div
            class="determinate green"
            style="width:40%"
          ></div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'Planning',
  data: () => ({
    loading: false,
  }),
  computed: {
    ...mapGetters(['categories', 'transactionsList', 'fetchingTransactions']),
  },
  methods: {
    ...mapActions(['fetchCategories', 'fetchTransactions']),
  },
  async mounted() {
    const date = new Date();
    if (!this.categories.length) {
      await this.fetchCategories();
    }
    if (!this.transactionsList.length && !this.fetchTransactions) {
      await this.fetchTransactions({ month: date.getMonth() + 1, year: date.getFullYear() });
    }
    this.loading = false;
  },
};
</script>

<style scoped>

</style>
