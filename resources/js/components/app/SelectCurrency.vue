<template>
  <select
    ref="select"
    v-model="selectedCurrency"
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
export default {
  name: 'SelectCurrency',
  props: ['defaultCurrency', 'currencyConversation'],
  data: () => ({
    selectedCurrency: '',
    select: null,
  }),
  created() {
    this.selectedCurrency = this.defaultCurrency;
  },
  mounted() {
    this.select = window.M.FormSelect.init(this.$refs.select);
  },
  beforeDestroy() {
    if (this.select && this.select.destroy) {
      this.select.destroy();
    }
  },
  watch: {
    selectedCurrency(newCurrency, oldCurrency) {
      if (newCurrency && oldCurrency) {
        const oldRate = this.currencyConversation[oldCurrency].rate;
        const newRate = this.currencyConversation[newCurrency].rate;
        const rate = newRate / oldRate;
        this.$emit('toggleCurrency', { oldCurrency, newCurrency, rate });
      }
    },
  },
};
</script>

<style scoped>

</style>
