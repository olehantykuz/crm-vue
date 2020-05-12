<template>
  <div>
    <div class="page-title">
      <h3>История записей</h3>
    </div>

    <div class="history-chart">
      <canvas></canvas>
    </div>

    <p v-if="!transactions.length" class="center">
      Транзакций пока нет.
      <router-link to="/transactions"> Добавить новую транзакцию.</router-link>
    </p>

    <section v-else>
      <history-table
        :transactions="transactions"
      ></history-table>
    </section>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import HistoryTable from '../components/HistoryTable';
import currencyFilter from '../filters/currency.filter';
import dateFilter from '../filters/date.filter';

export default {
  name: 'History',
  components: { HistoryTable },
  computed: {
    ...mapGetters(['categories', 'currentInterval', 'transactionsList']),
    transactions() {
      const currentDate = new Date();

      return this.transactionsList.filter((t) => {
        const tDate = new Date(t.date * 1000);

        return (tDate.getMonth() === currentDate.getMonth())
          && (tDate.getFullYear() === currentDate.getFullYear());
      }).map((t) => {
        const category = this.categories.find((cat) => cat.id === t.categoryId);

        return {
          ...t,
          categoryName: category.title,
          formattedAmount: currencyFilter(t.amount, t.currency),
          formattedDate: dateFilter(new Date(t.date * 1000)),
          cssClass: t.type === 'outcome' ? 'red' : 'green',
          typeText: t.type === 'outcome' ? 'Расход' : 'Доход',
        };
      });
    },
  },
};
</script>

<style scoped>

</style>
