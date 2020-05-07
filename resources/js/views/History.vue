<template>
  <div>
    <div class="page-title">
      <h3>История записей</h3>
    </div>

    <div class="history-chart">
      <canvas></canvas>
    </div>

    <section>
      <history-table></history-table>
    </section>
  </div>
</template>

<script>
import HistoryTable from '../components/HistoryTable';

export default {
  name: 'History',
  components: { HistoryTable },
};
</script>

<style scoped>

</style>
