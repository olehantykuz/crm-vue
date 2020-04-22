<template>
  <form class="card auth-card" @submit.prevent="submitHandler">
    <div class="card-content">
      <span class="card-title">Домашняя бухгалтерия</span>
      <div class="input-field">
        <input
          id="email"
          type="text"
          v-model.trim="email"
          :class="{
            invalid: ($v.email.$dirty && !$v.email.required)
            || ($v.email.$dirty && !$v.email.email)
          }"
        >
        <label for="email">Email</label>
        <small
          class="helper-text invalid"
          v-if="$v.email.$dirty && !$v.email.required"
        >
          Поле Email не должно быть пустым
        </small>
        <small
          class="helper-text invalid"
          v-else-if="$v.email.$dirty && !$v.email.email"
        >
          Введите корректный Email
        </small>
      </div>
      <div class="input-field">
        <input
          id="password"
          type="password"
          v-model.trim="password"
          :class="{
            invalid: ($v.password.$dirty && !$v.password.required)
            || ($v.password.$dirty && !$v.password.minLength)
          }"
        >
        <label for="password">Пароль</label>
        <small
          class="helper-text invalid"
          v-if="$v.password.$dirty && !$v.password.required"
        >
          Поле Password не должно быть пустым
        </small>
        <small
          class="helper-text invalid"
          v-else-if="$v.password.$dirty && !$v.password.minLength"
        >
          Длина пароля дожна быть как минимум
          {{` ${$v.password.$params.minLength.min} `}} символов. Сейчас длина пароля
          {{` ${password.length} `}} символов
        </small>
      </div>
      <div class="input-field">
        <input
          id="name"
          type="text"
          v-model.trim="name"
          :class="{invalid: ($v.name.$dirty && !$v.name.required)}"
        >
        <label for="name">Имя</label>
        <small
          class="helper-text invalid"
          v-if="$v.name.$dirty && !$v.name.required"
        >
          Поле Name не должно быть пустым
        </small>
      </div>
      <div class="input-field row">
        <div class="col s6">
          <label class="currency-label">Валюта</label>
          <select-currency
            :defaultCurrency="selectedCurrency"
            @toggleCurrency="toggleCurrency"
          >
          </select-currency>
        </div>
        <div class="col s6">
          <label for="monthly_budget">Месячный бюджет</label>
          <input
            id="monthly_budget"
            type="number"
            step="0.01"
            v-model="monthlyBudget"
            :class="{
              invalid: ($v.monthlyBudget.$dirty && !$v.monthlyBudget.required)
              || ($v.monthlyBudget.$dirty && !$v.monthlyBudget.numeric)
              || ($v.monthlyBudget.$dirty && !$v.monthlyBudget.minValue)
            }"
          >
          <small
            class="helper-text invalid"
            v-if="$v.monthlyBudget.$dirty && !$v.monthlyBudget.required"
          >
            Поле месячный бюджет не должно быть пустым
          </small>
          <small
            class="helper-text invalid"
            v-else-if="$v.monthlyBudget.$dirty && !$v.monthlyBudget.numeric"
          >
            Месячный бюджет должен быть числом
          </small>
          <small
            class="helper-text invalid"
            v-else-if="$v.monthlyBudget.$dirty && !$v.monthlyBudget.minValue"
          >
            Месячный бюджет должен быть положительным числом
          </small>
        </div>
      </div>
      <p>
        <label>
          <input type="checkbox" v-model="agree"/>
          <span>С правилами согласен</span>
        </label>
      </p>
    </div>
    <div class="card-action">
      <div>
        <button
          class="btn waves-effect waves-light auth-submit"
          type="submit"
        >
          Зарегистрироваться
          <i class="material-icons right">send</i>
        </button>
      </div>

      <p class="center">
        Уже есть аккаунт?
        <router-link :to="{name: 'login'}">Войти!</router-link>
      </p>
    </div>
  </form>
</template>

<script>
import {
  email, minLength, required,
} from 'vuelidate/lib/validators';
import { mapActions, mapGetters } from 'vuex';
import SelectCurrency from '../components/app/SelectCurrency';

export default {
  name: 'Register',
  components: { SelectCurrency },
  data: () => ({
    email: '',
    password: '',
    name: '',
    monthlyBudget: 1000,
    agree: false,
    selectedCurrency: '',
  }),
  validations: {
    email: { email, required },
    password: { required, minLength: minLength(6) },
    name: { required },
    agree: { checked: (v) => v },
    monthlyBudget: { required, numeric: (v) => !!parseFloat(v), minValue: (v) => v > 0 },
  },
  methods: {
    ...mapActions(['register']),
    toggleCurrency(e) {
      const { newCurrency, rate } = e;
      this.monthlyBudget = parseFloat(((this.monthlyBudget * rate)).toFixed(2));
      this.selectedCurrency = newCurrency;
    },
    setDefaultSelectedCurrency() {
      if (this.baseCurrency) {
        this.selectedCurrency = this.baseCurrency;
      }
    },
    async submitHandler() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      }

      const formData = {
        email: this.email,
        password: this.password,
        name: this.name,
        monthlyBudget: this.monthlyBudget,
        currencyId: this.currencyConversation[this.selectedCurrency].id,
      };
      await this.register(formData);
    },
  },
  computed: {
    ...mapGetters(['currencyConversation', 'baseCurrency']),
  },
  mounted() {
    window.M.updateTextFields();
    this.setDefaultSelectedCurrency();
  },
  watch: {
    currencyConversation() {
      this.setDefaultSelectedCurrency();
    },
  },
};
</script>

<style scoped>

</style>
