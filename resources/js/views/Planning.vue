<template>
  <div>
    <div v-if="!loading" class="page-title">
      <h3>Планирование</h3>
      <h4>{{ budget | currency(defaultCurrency) }}</h4>
    </div>
    <loader v-if="loading"></loader>
    <p
      class="center"
      v-else-if="!categories.length"
    >
      Категорий пока нет. <router-link to="/categories">Добавить новую категорию</router-link>
    </p>
    <section v-else>
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
    loading: true,
  }),
  computed: {
    ...mapGetters(['categories', 'transactionsList', 'bill', 'defaultCurrency', 'monthlyBill']),
    budget() {
      let result = null;
      if (this.bill.length) {
        const item = this.bill.find((row) => row.code === this.defaultCurrency);

        result = item ? item.value : null;
      }

      return result;
    },
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
