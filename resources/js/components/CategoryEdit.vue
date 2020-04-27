<template>
  <div class="col s12 m6">
    <div>
      <div class="page-subtitle">
        <h4>Редактировать</h4>
      </div>

      <form @submit.prevent="submitHandler">
        <select-category
          :categories="categories"
        ></select-category>

        <div class="input-field">
          <input
            id="name"
            type="text"
            v-model="title"
            :class="{invalid: $v.title.$dirty && !$v.title.required}"
          >
          <label for="name">Название</label>
          <span
            v-if="$v.title.$dirty && !$v.title.required"
            class="helper-text invalid"
          >
            Введите название категории
          </span>
        </div>

        <div class="row">
          <div class="input-field col s8">
            <input
              id="limit"
              type="number"
              step="0.01"
              v-model="limit"
              :class="{invalid: $v.limit.$dirty && !$v.limit.positive}"
            >
            <label for="limit">Лимит</label>
            <span
              v-if="$v.limit.$dirty && !$v.limit.positive"
              class="helper-text invalid"
            >
              Значение лимита должно быть больше 0
            </span>
          </div>
          <div class="input-field col s4">
            <input
              id="category_currency"
              type="text"
              :disabled="true"
              :value="currency"
            >
            <label for="limit">Валюта</label>
          </div>
        </div>

        <button class="btn waves-effect waves-light" type="submit">
          Обновить
          <i class="material-icons right">send</i>
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators';
import { mapGetters, mapActions } from 'vuex';
import SelectCategory from './app/SelectCategory';

export default {
  name: 'CategoryEdit',
  components: { SelectCategory },
  props: {
    categories: {
      type: Array,
      required: true,
    },
    displayedCategory: {
      type: Number,
      required: false,
    },
  },
  validations: {
    title: { required },
    limit: { positive: (v) => v > 0 },
  },
  data: () => ({
    title: '',
    limit: 1,
    currency: '',
  }),
  computed: {
    ...mapGetters(['currencyConversation', 'error', 'currentCategory']),
  },
  methods: {
    ...mapActions(['updateCategory']),
    async submitHandler() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      }
      const formData = {
        title: this.title.trim(),
        limit: this.limit,
        currencyId: this.currencyConversation[this.currency].id,
      };

      try {
        const category = await this.updateCategory({
          categoryId: this.currentCategory, data: formData,
        });
        this.title = category.title;
        this.limit = category.defaultLimit;
        this.currency = category.currency;
        this.$v.$reset();
        this.$message('Категория успешно обновлена');
        this.$emit('updated');
      } catch (e) {
        this.$error(this.error);
      }
    },
    setFormData() {
      const index = this.currentCategory
        ? this.categories.findIndex((c) => c.id === this.currentCategory)
        : 0;
      const {
        title, defaultLimit, currency,
      } = this.categories[index];
      this.title = title;
      this.limit = defaultLimit;
      this.currency = currency;
    },
  },
  watch: {
    currentCategory() {
      this.setFormData();
    },
  },
  created() {
    this.setFormData();
  },
  mounted() {
    window.M.updateTextFields();
  },
};
</script>

<style scoped>

</style>
