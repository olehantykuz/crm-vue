<template>
  <div>
    <div class="page-title">
      <h3>Категории</h3>
    </div>
    <section>
      <loader v-if="loading"></loader>
      <div v-else class="row">
        <category-create
        ></category-create>
        <category-edit
          v-if="categories.length"
          :categories="categories"
          :displayedCategory="updatedId"
          @updated="updateCategory"
          :key="categories.length + updateCount"
        ></category-edit>
        <p v-else class="center">
          Категорий пока нет
        </p>
      </div>
    </section>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import CategoryCreate from '../components/CategoryCreate';
import CategoryEdit from '../components/CategoryEdit';

export default {
  name: 'Categories',
  components: {
    CategoryEdit,
    CategoryCreate,
  },
  data: () => ({
    loading: true,
    updateCount: 0,
    updatedId: null,
  }),
  computed: {
    ...mapGetters(['error', 'categories']),
  },
  methods: {
    ...mapActions(['fetchCategories']),
    updateCategory() {
      this.updateCount += 1;
    },
  },
  async mounted() {
    try {
      if (!this.categories.length) {
        await this.fetchCategories();
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
