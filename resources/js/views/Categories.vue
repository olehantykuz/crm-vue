<template>
  <div>
    <div class="page-title">
      <h3>Категории</h3>
    </div>
    <section>
      <loader v-if="loading"></loader>
      <div v-else class="row">
        <category-create
          @created="addCategory"
        ></category-create>
        <category-edit
          :categories="categories"
          :displayedCategory="updatedId"
          @updated="updateCategory"
          :key="categories.length + updateCount"
        ></category-edit>
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
    categories: [],
    loading: true,
    updateCount: 0,
    updatedId: null,
  }),
  computed: {
    ...mapGetters(['error']),
  },
  methods: {
    ...mapActions(['fetchCategories']),
    addCategory(e) {
      this.categories.push(e.category);
    },
    updateCategory(e) {
      const { category } = e;
      this.categories = this.categories.map((cat) => (cat.id === category.id ? category : cat));
      this.updateCount += 1;
      this.updatedId = category.id;
    },
  },
  async mounted() {
    try {
      this.categories = await this.fetchCategories();
    } catch (e) {
      this.$error(this.error);
    }
    this.loading = false;
  },
};
</script>

<style scoped>

</style>
