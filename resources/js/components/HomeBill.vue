<template>
  <div class="col s12 m6 l4">
    <div class="card light-blue bill-card">
      <div class="card-content white-text">
        <span class="card-title">Счет в валюте</span>

        <p
          class="currency-line"
          v-for="curr of currencies"
          :key="curr"
        >
          <span>
            {{getCurrency(curr) | currency(curr)}}
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
  props: ['rates', 'baseCurrency'],
  computed: {
    ...mapGetters(['info']),
    base() {
      const { defaultBudget: bill } = this.info;

      return bill
        ? bill.total / (this.rates[bill.currency].rate / this.rates[this.baseCurrency].rate)
        : null;
    },
    currencies() {
      return Object.keys(this.rates);
    },
  },
  methods: {
    getCurrency(code) {
      return (this.base * this.rates[code].rate).toFixed(2);
    },
  },
};
</script>

<style scoped>

</style>
