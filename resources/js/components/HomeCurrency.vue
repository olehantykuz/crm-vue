<template>
  <div class="col s12 m6 l8">
    <div class="card orange darken-3 bill-card">
      <div class="card-content white-text">
        <div class="card-header">
          <span class="card-title col s12 m10">Курс валют {{selected}}</span>
          <select v-model="selected" class="col s12 m2">
            <option
              v-for="curr of currencyCodes"
              :key="curr"
              :value="curr"
            >
              {{curr}}
            </option>
          </select>
        </div>
        <table>
          <thead>
          <tr>
            <th>Валюта</th>
            <th>Курс</th>
            <th>Дата</th>
          </tr>
          </thead>

          <tbody>
          <tr v-for="curr of currencyCodes" :key="curr">
            <td>{{curr}}</td>
            <td>{{ getConversation(curr) }}</td>
            <td>{{ (rates[curr].date * 1000) | date('date') }}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeCurrency',
  props: ['rates', 'baseCurrency', 'currencyCodes'],
  data: () => ({
    selected: '',
  }),
  methods: {
    getConversation(currentCode) {
      return this.selected
        ? (this.rates[currentCode].rate / this.rates[this.selected].rate).toFixed(6)
        : null;
    },
  },
  mounted() {
    this.selected = this.baseCurrency;
  },
};
</script>

<style scoped>
  select {
    display: block;
  }
</style>
