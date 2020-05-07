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
        <div
          class="progress"
          v-tooltip="row.tooltipText"
        >
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
import currencyFilter from '../filters/currency.filter';

export default {
  name: 'Planning',
  data: () => ({
    loading: true,
    categoriesData: [],
  }),
  computed: {
    ...mapGetters(['categories', 'transactionsList', 'bill', 'defaultCurrency', 'monthlyBill', 'currencyCodes', 'currencyConversation']),
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
          const { currency, defaultLimit } = cat;
          const ballanceInCurr = {};
          const baseRate = this.currencyConversation[currency].rate;
          this.currencyCodes.forEach((code) => {
            const { rate } = this.currencyConversation[code];
            ballanceInCurr[code] = parseFloat(((defaultLimit * rate) / baseRate).toFixed(2));
          });
          const total = {
            spend: {},
            balance: ballanceInCurr,
          };

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
                total.spend[code] = total.spend[code] || 0;
                total.spend[code] += +t.amountByCurrency[code];
                total.balance[code] -= (t.amountByCurrency[code]);
                total.balance[code] = parseFloat((total.balance[code]).toFixed(2));
                total.spend[code] = parseFloat((total.spend[code]).toFixed(2));
              });
            });

          const totalInDefaultCurrency = total.spend[currency];
          const percent = (totalInDefaultCurrency / defaultLimit) * 100;
          const progressPercent = percent > 100 ? 100 : percent;

          // eslint-disable-next-line no-nested-ternary
          const progressColor = percent < 60
            ? 'green'
            : (percent < 100 ? 'yellow' : 'red');

          let tooltipText = '<table><tr><td>Остаток</td><td>Затраты</td></tr>';
          this.currencyCodes.forEach((code) => {
            tooltipText += `<tr>
                                <td>${currencyFilter(total.balance[code], code)}; </td>
                                <td>${currencyFilter(total.spend[code], code)};</td>
                            </tr>`;
          });
          tooltipText += '</table>';

          this.categoriesData.push({
            category: cat,
            progressPercent,
            progressColor,
            totalInDefaultCurrency,
            total,
            tooltipText,
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
