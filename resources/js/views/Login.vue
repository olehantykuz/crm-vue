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
          {{password.length}} символов
        </small>
      </div>
    </div>
    <div class="card-action">
      <div>
        <button
          class="btn waves-effect waves-light auth-submit"
          type="submit"
        >
          Войти
          <i class="material-icons right">send</i>
        </button>
      </div>

      <p class="center">
        Нет аккаунта?
        <router-link :to="{name: 'register'}">
          Зарегистрироваться
        </router-link>
      </p>
    </div>
  </form>
</template>

<script>
import { mapActions } from 'vuex';
import { email, required, minLength } from 'vuelidate/lib/validators';
import messages from '../utils/messages';

export default {
  name: 'Login',
  data: () => ({
    email: '',
    password: '',
  }),
  validations: {
    email: { email, required },
    password: { required, minLength: minLength(6) },
  },
  methods: {
    ...mapActions(['login']),
    async submitHandler() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      }

      const formData = {
        email: this.email,
        password: this.password,
      };
      await this.login(formData);
    },
  },
  mounted() {
    const key = this.$route.query.message;
    if (messages[key]) {
      this.$message(messages[key]);
    }
  },
};
</script>

<style scoped>

</style>
