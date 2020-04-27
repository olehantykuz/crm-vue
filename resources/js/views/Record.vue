<template>
  <div>
    <div class="page-title">
      <h3>Новая запись</h3>
    </div>
    <loader v-if="loading"></loader>
    <p
      class="center"
      v-else-if="!categories.length"
    >
      Категорий пока нет. <router-link to="/categories">Добавить новую категорию</router-link>
    </p>
    <form class="form" v-else>
      <select-category
        v-if="categories.length"
        :categories="categories"
      ></select-category>

      <p>
        <label>
          <input
            class="with-gap"
            name="type"
            type="radio"
            value="income"
            v-model="type"
          />
          <span>Доход</span>
        </label>
      </p>

      <p>
        <label>
          <input
            class="with-gap"
            name="type"
            type="radio"
            value="outcome"
            v-model="type"
          />
          <span>Расход</span>
        </label>
      </p>

      <div class="input-field">
        <input
          id="amount"
          type="number"
        >
        <label for="amount">Сумма</label>
        <span class="helper-text invalid">amount пароль</span>
      </div>

      <div class="input-field">
        <input
          id="description"
          type="text"
        >
        <label for="description">Описание</label>
        <span
          class="helper-text invalid">description пароль</span>
      </div>

      <button class="btn waves-effect waves-light" type="submit">
        Создать
        <i class="material-icons right">send</i>
      </button>
    </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import SelectCategory from '../components/app/SelectCategory';

export default {
  name: 'Record',
  data: () => ({
    loading: true,
    type: 'outcome',
  }),
  components: { SelectCategory },
  computed: {
    ...mapGetters(['categories', 'currentCategory']),
    categoryId() {
      return this.currentCategory;
    },
  },
  methods: {
    ...mapActions(['fetchCategories']),
  },
  async mounted() {
    if (!this.categories.length) {
      await this.fetchCategories();
    }
    this.loading = false;
  },
};
</script>

<style scoped>

</style>
