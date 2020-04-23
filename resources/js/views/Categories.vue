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
  methods: {
    addCategory(e) {
      this.categories.push(e.category);
    },
  },
};
</script>

<style scoped>

</style>
