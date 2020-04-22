<template>
  <select
    :value="selectedCurrency"
    @input="selectCurrency"
    id="selectedCurrency"
  >
    <option
      v-for="(curr, code) in currencyConversation"
      :key="curr.id"
      :value="code"
      :selected="code === selectedCurrency"
    >
      {{code}}
    </option>
  </select>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'SelectCurrency',
  props: ['defaultCurrency'],
  data: () => ({
    selectedCurrency: '',
  }),
  computed: {
    ...mapGetters(['currencyConversation']),
  },
  methods: {
    setDefaultSelectedCurrency() {
      const baseCurrency = this.defaultCurrency || '';
      if (baseCurrency) {
        this.selectedCurrency = baseCurrency;
      }
    },
    selectCurrency(e) {
      const oldCurrency = this.defaultCurrency;
      const newCurrency = e.target.value;
      const oldRate = this.currencyConversation[oldCurrency].rate;
      const newRate = this.currencyConversation[newCurrency].rate;
      const rate = parseFloat((newRate / oldRate).toFixed(6));
      this.$emit('toggleCurrency', { oldCurrency, newCurrency, rate });
    },
  },
  mounted() {
    this.setDefaultSelectedCurrency();
  },
  watch: {
    currencyConversation() {
      this.setDefaultSelectedCurrency();
    },
    defaultCurrency() {
      this.setDefaultSelectedCurrency();
    },
  },
};
</script>

<style scoped>
  select {
    display: block;
  }
</style>
