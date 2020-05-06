<template>
  <div class="col s12 m6">
    <div>
      <div class="page-subtitle">
        <h4>Создать</h4>
      </div>

      <form @submit.prevent="submitHandler">
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
            <select-currency
              v-if="(selectedCurrency && Object.keys(currencyConversation).length)"
              :defaultCurrency="selectedCurrency"
              :currencyConversation="currencyConversation"
              @toggleCurrency="toggleCurrency"
            ></select-currency>
          </div>
        </div>
        <button class="btn waves-effect waves-light" type="submit">
          Создать
          <i class="material-icons right">send</i>
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators';
import { mapGetters, mapActions } from 'vuex';
import SelectCurrency from './app/SelectCurrency';

export default {
  name: 'CategoryCreate',
  components: { SelectCurrency },
  data: () => ({
    title: '',
    limit: 1,
    selectedCurrency: '',
  }),
  computed: {
    ...mapGetters(['currencyConversation', 'error', 'defaultCurrency']),
  },
  validations: {
    title: { required },
    limit: { positive: (v) => v > 0 },
  },
  methods: {
    ...mapActions(['createCategory']),
    async submitHandler() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      }
      const formData = {
        title: this.title.trim(),
        limit: this.limit,
        currencyId: this.currencyConversation[this.selectedCurrency].id,
      };

      try {
        await this.createCategory(formData);
        this.title = '';
        this.limit = 1;
        this.$v.$reset();
        this.$message('Категория успешно создана');
      } catch (e) {
        this.$error(this.error);
      }
    },
    toggleCurrency(e) {
      const { newCurrency, rate } = e;
      this.limit = parseFloat(((this.limit * rate)).toFixed(2));
      this.selectedCurrency = newCurrency;
    },
    setDefaultSelectedCurrency() {
      this.selectedCurrency = this.defaultCurrency || '';
    },
  },
  mounted() {
    this.setDefaultSelectedCurrency();
    window.M.updateTextFields();
  },
  watch: {
    selectedCurrency() {
      this.setDefaultSelectedCurrency();
    },
    currencyConversation() {
      this.setDefaultSelectedCurrency();
    },
  },
};
</script>

<style scoped>

</style>
