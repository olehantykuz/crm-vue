<template>
  <div>
    <loader v-if="loading"></loader>

    <p class="center" v-else-if="!transaction">Транзакция не найдена</p>

    <div v-else>
      <div class="breadcrumb-wrap">
        <router-link to="/history" class="breadcrumb">История</router-link>
        <a @click.prevent class="breadcrumb">
          {{ transaction.type === 'outcome' ? 'Расход' : 'Доход' }}
        </a>
      </div>
      <div class="row">
        <div class="col s12 m6">
          <div
            class="card"
            :class="transaction.type === 'outcome' ? 'red' : 'green'"
          >
            <div class="card-content white-text">
              <p>Описание: {{ transaction.description }}</p>
              <p>Сумма: {{ transaction.amount | currency(transaction.currency) }}</p>
              <p>Категория: {{ transaction.category.title }}</p>

              <small>{{ (new Date(transaction.date * 1000)) | date('datetime') }}</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'DetailRecord',
  data: () => ({
    transaction: null,
    loading: true,
  }),
  computed: {
    ...mapGetters(['error', 'fetchingTransaction']),
  },
  methods: {
    ...mapActions(['fetchTransaction']),
  },
  async mounted() {
    try {
      if (!this.fetchingTransaction) {
        this.transaction = await this.fetchTransaction(this.$route.params.id);
      }
    } catch (e) {
      this.$error(this.error);
    }
    this.loading = false;
  },
};
</script>

<style scoped>

</style>
