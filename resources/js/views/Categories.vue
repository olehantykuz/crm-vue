<template>
  <div>
    <div class="page-title">
      <h3>Категории</h3>
    </div>
    <section>
      <div class="row">
        <category-create
          @created="addCategory"
        ></category-create>
        <category-edit></category-edit>
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
  }),
  computed: {
    ...mapGetters(['error']),
  },
  methods: {
    ...mapActions(['fetchCategories']),
    addCategory(e) {
      this.categories.push(e.category);
    },
  },
  async mounted() {
    try {
      this.categories = await this.fetchCategories();
    } catch (e) {
      this.$error(this.error);
    }
  },
};
</script>

<style scoped>

</style>
