<template>
  <div>
    <div class="page-title">
      <h3>Счет</h3>

      <button class="btn waves-effect waves-light btn-small" @click="refresh">
        <i class="material-icons">refresh</i>
      </button>
    </div>

    <loader v-if="loading"></loader>

    <div v-else class="row">
      <home-bill
        :rates="currencyConversation"
        :baseCurrency="baseCurrency"
        :currencyCodes="currencyCodes"
      ></home-bill>
      <home-currency
        :rates="currencyConversation"
        :baseCurrency="baseCurrency"
        :currencyCodes="currencyCodes"
      ></home-currency>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import HomeBill from '../components/HomeBill';
import HomeCurrency from '../components/HomeCurrency';

export default {
  name: 'Home',
  components: { HomeCurrency, HomeBill },
  methods: {
    ...mapActions(['fetchCurrencyConversation']),
    refresh() {
      this.fetchCurrencyConversation();
    },
  },
  computed: {
    ...mapGetters([
      'fetchingCurrencies', 'currencyConversation', 'baseCurrency', 'currencyCodes',
    ]),
    loading() {
      return this.fetchingCurrencies;
    },
  },
};
</script>

<style scoped>

</style>
