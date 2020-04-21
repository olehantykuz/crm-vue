<template>
  <div>
    <div class="page-title">
      <h3>Счет</h3>

      <button class="btn waves-effect waves-light btn-small">
        <i class="material-icons">refresh</i>
      </button>
    </div>

    <loader v-if="loading"></loader>

    <div v-else class="row">
      <home-bill
        :rates="currency.rates || {}"
        :baseCurrency="currency.baseCurrency"
      ></home-bill>
      <home-currency></home-currency>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex';

import HomeBill from '../components/HomeBill';
import HomeCurrency from '../components/HomeCurrency';

export default {
  name: 'Home',
  components: { HomeCurrency, HomeBill },
  data: () => ({
    loading: true,
    currency: null,
  }),
  methods: {
    ...mapActions(['fetchCurrencyConversation']),
  },
  async mounted() {
    this.currency = await this.fetchCurrencyConversation();
    this.loading = false;
  },
};
</script>

<style scoped>

</style>
