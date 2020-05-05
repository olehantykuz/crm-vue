<template>
  <div class="col s12 m6 l4">
    <div class="card light-blue bill-card">
      <div class="card-content white-text">
        <span class="card-title">Счет в валюте</span>

        <p
          class="currency-line"
          v-for="curr of billByCurrencies"
          :key="curr.code"
        >
          <span>
            {{curr.value | currency(curr.code)}}
          </span>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'HomeBill',
  props: ['rates', 'baseCurrency', 'currencyCodes'],
  data: () => ({
    billByCurrencies: [],
  }),
  computed: {
    ...mapGetters(['totals', 'fetchingTotals', 'defaultBudget', 'sortedCurrencyCodes']),
    base() {
      const { defaultBudget: bill } = this;

      return bill.currency
        ? bill.total / (this.rates[bill.currency].rate / this.rates[this.baseCurrency].rate)
        : null;
    },
  },
  methods: {
    getCurrency(code) {
      return (this.base * this.rates[code].rate).toFixed(2);
    },
    getBillInCurrency(code) {
      const date = new Date();
      const key = `${date.getFullYear()}_${date.getMonth() + 1}`;
      let sumTransactions = 0;
      if (this.totals && this.totals[key]) {
        if (this.totals[key].income) {
          sumTransactions += this.totals[key].income[code] || 0;
        }
        if (this.totals[key].outcome) {
          sumTransactions -= this.totals[key].outcome[code] || 0;
        }
      }

      return (this.base * this.rates[code].rate + sumTransactions).toFixed(2);
    },
    calculateBillInCurrencies() {
      const result = [];
      this.sortedCurrencyCodes.forEach((code) => {
        result.push({ code, value: this.getBillInCurrency(code) });
      });

      this.billByCurrencies = result;
    },
  },
  mounted() {
    this.calculateBillInCurrencies();
  },
  watch: {
    fetchingTotals(status) {
      if (!status) {
        this.calculateBillInCurrencies();
      }
    },
  },
};
</script>

<style scoped>

</style>
