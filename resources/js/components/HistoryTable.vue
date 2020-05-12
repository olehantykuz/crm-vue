<template>
  <table>
    <thead>
    <tr>
      <th>#</th>
      <th>Сумма</th>
      <th>Дата</th>
      <th>Категория</th>
      <th>Тип</th>
      <th>Открыть</th>
    </tr>
    </thead>

    <tbody>
    <tr v-for="(transaction, index) in transactions" :key="transaction.id">
      <td>{{ index + 1 }}</td>
      <td>{{ transaction.formattedAmount }}</td>
      <td>{{ transaction.formattedDate }}</td>
      <td>{{ transaction.categoryName }}</td>
      <td>
        <span
          class="white-text badge"
          :class="transaction.cssClass"
        >
          {{ transaction.typeText }}
        </span>
      </td>
      <td>
        <button
          v-tooltip="'Подробно'"
          class="btn-small btn"
          @click="$router.push(`/transactions/${transaction.id}`)"
        >
          <i class="material-icons">open_in_new</i>
        </button>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  name: 'HistoryTable',
  props: {
    transactions: {
      required: true,
      type: Array,
    },
  },
};
</script>

<style scoped>

</style>
