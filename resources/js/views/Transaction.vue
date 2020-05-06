<template>
  <div>
    <div class="page-title">
      <h3>Новая запись</h3>
    </div>
    <loader v-show="loading"></loader>
    <p
      class="center"
      v-show="!loading && !categories.length"
    >
      Категорий пока нет. <router-link to="/categories">Добавить новую категорию</router-link>
    </p>
    <form
      class="form"
      v-show="!loading && categories.length"
      @submit.prevent="submitHandler"
    >
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

      <div class="row">
        <div class="input-field  col s8 amount">
          <input
            id="amount"
            type="number"
            step="0.01"
            v-model.number="amount"
            :class="{invalid: $v.amount.$dirty && !$v.amount.positive}"
          >
          <label for="amount">Сумма</label>
          <span
            v-if="$v.amount.$dirty && !$v.amount.positive"
            class="helper-text invalid"
          >
            Значение суммы должно быть больше 0
          </span>
        </div>
        <div class="input-field col s4 amount_currency">
          <input
            id="category_currency"
            type="text"
            :disabled="true"
            :value="currency"
          >
          <label for="category_currency">Валюта</label>
        </div>
      </div>

      <div class="input-field">
        <input
          id="description"
          type="text"
          v-model="description"
          :class="{invalid: $v.description.$dirty && !$v.description.required}"
        >
        <label for="description">Описание</label>
        <span
          v-if="$v.description.$dirty && !$v.description.required"
          class="helper-text invalid"
        >
          Введите описание
        </span>
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
import { required } from 'vuelidate/lib/validators';
import SelectCategory from '../components/app/SelectCategory';

export default {
  name: 'Transaction',
  data: () => ({
    loading: true,
    type: 'outcome',
    amount: 1,
    description: '',
  }),
  validations: {
    description: { required },
    amount: { positive: (v) => v > 0 },
  },
  components: { SelectCategory },
  computed: {
    ...mapGetters(['currencyConversation', 'error', 'categories', 'currentCategory', 'monthlyBill']),
    bill() {
      return this.monthlyBill;
    },
    categoryId() {
      return this.currentCategory;
    },
    currency() {
      const index = this.categories.findIndex((cat) => (cat.id === this.categoryId));

      return index > -1 ? this.categories[index].currency : null;
    },
    canCreateTransaction() {
      if (this.type === 'income') {
        return true;
      }

      return this.bill >= this.amount;
    },
  },
  methods: {
    ...mapActions(['fetchCategories', 'createTransaction']),
    async submitHandler() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      }
      const formData = {
        amount: this.amount,
        description: this.description,
        type: this.type,
        currencyId: this.currencyConversation[this.currency].id,
      };

      if (this.canCreateTransaction) {
        try {
          await this.createTransaction({ categoryId: this.categoryId, data: formData });
          this.description = '';
          this.amount = 1;
          this.$v.$reset();
          this.$message('Транзакция успешно создана');
        } catch (e) {
          this.$error(this.error);
        }
      } else {
        this.$message(`Недостаточно средств на счете ${this.amount - this.bill}`);
      }
    },
  },
  async mounted() {
    if (!this.categories.length) {
      await this.fetchCategories();
    }
    this.loading = false;
    window.M.updateTextFields();
  },
};
</script>

<style scoped>
  .amount {
    padding-left: 0;
  }
  .amount_currency {
    padding-right: 0;
  }
</style>
