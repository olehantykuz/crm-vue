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
    ...mapGetters(['current']),
  },
  methods: {
    ...mapActions(['setCurrentCategory']),
  },
  created() {
    this.selected = this.current;
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
    current() {
      this.selected = this.current;
    },
    selected() {
      if (this.selected !== this.current) {
        this.setCurrentCategory(this.selected);
      }
    },
  },
};
</script>

<style scoped>

</style>
