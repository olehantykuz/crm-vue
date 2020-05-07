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
      <div v-for="row in categoriesData" :key="row.category.id">
        <p>
          <strong>{{ row.category.title }}:</strong>
          {{ row.totalInDefaultCurrency }} из {{
          row.category.defaultLimit | currency(row.category.currency)
          }}
        </p>
        <div class="progress">
          <div
            class="determinate"
            :class="row.progressColor"
            :style="{ width: row.progressPercent + '%' }"
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
    categoriesData: [],
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
    calculateCategoriesData() {
      const { categories, transactionsList: transactions } = this;
      const currentDate = new Date();

      if (transactions.length) {
        categories.forEach((cat) => {
          const total = {};
          transactions
            .filter((t) => {
              const tDate = new Date(t.date * 1000);

              return (tDate.getMonth() === currentDate.getMonth())
                && (tDate.getFullYear() === currentDate.getFullYear());
            })
            .filter((t) => t.categoryId === cat.id)
            .filter((t) => t.type === 'outcome')
            .forEach((t) => {
              Object.keys(t.amountByCurrency).forEach((code) => {
                total[code] = total[code] || 0;
                total[code] += +t.amountByCurrency[code];
              });
            });

          const { currency } = cat;
          const totalInDefaultCurrency = total[currency];
          const percent = (totalInDefaultCurrency / cat.defaultLimit) * 100;
          const progressPercent = percent > 100 ? 100 : percent;

          // eslint-disable-next-line no-nested-ternary
          const progressColor = percent < 60
            ? 'green'
            : (percent < 100 ? 'yellow' : 'red');

          this.categoriesData.push({
            category: cat,
            progressPercent,
            progressColor,
            totalInDefaultCurrency,
          });
        });
      }
    },
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
    this.calculateCategoriesData();
  },
  watch: {
    transactionsList() {
      this.calculateCategoriesData();
    },
  },
};
</script>

<style scoped>

</style>
