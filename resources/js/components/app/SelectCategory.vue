<template>
  <div class="input-field">
    <select ref="select" v-model="selected">
      <option
        v-for="category of categories"
        :key="category.id"
        :value="category.id"
      >{{category.title}}</option>
    </select>
    <label>Выберите категорию</label>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'SelectCategory',
  data: () => ({
    select: null,
    selected: null,
  }),
  props: {
    categories: {
      type: Array,
      required: true,
    },
  },
  computed: {
    ...mapGetters(['currentCategory']),
  },
  methods: {
    ...mapActions(['setCurrentCategory']),
  },
  created() {
    this.selected = this.currentCategory;
  },
  mounted() {
    this.select = window.M.FormSelect.init(this.$refs.select);
  },
  beforeDestroy() {
    if (this.select && this.select.destroy) {
      this.select.destroy();
    }
  },
  watch: {
    currentCategory() {
      this.selected = this.currentCategory;
    },
    selected() {
      if (this.selected !== this.currentCategory) {
        this.setCurrentCategory(this.selected);
      }
    },
  },
};
</script>

<style scoped>

</style>
