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
  computed: {
    ...mapGetters(['bill']),
    billByCurrencies() {
      return this.bill.map((item) => {
        const { value } = item;

        return { ...item, value: value.toFixed(2) };
      });
    },
  },
};
</script>

<style scoped>

</style>
