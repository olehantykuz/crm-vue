<template>
  <form class="card auth-card" @submit.prevent="submitHandler">
    <div class="card-content">
      <span class="card-title">Домашняя бухгалтерия</span>
      <div class="input-field">
        <input
          id="email"
          type="text"
          v-model.trim="email"
          :class="{invalid: ($v.email.$dirty && !$v.email.required) || ($v.email.$dirty && !$v.email.email)}"
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
          :class="{invalid: ($v.password.$dirty && !$v.password.required) || ($v.password.$dirty && !$v.password.minLength)}"
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
          Длина пароля дожна быть как минимум {{$v.password.$params.minLength.min}} символов. Сейчас длина пароля
          {{password.length}} символов
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
  import { email, minLength, required } from 'vuelidate/lib/validators';
  import { authService } from '../api/auth';

  export default {
    name: 'Register',
    data: () => ({
      email: '',
      password: '',
      name: '',
      agree: false,
    }),
    validations: {
      email: { email, required },
      password: { required, minLength: minLength(6) },
      name: { required },
      agree: { checked: v => v },
    },
    methods: {
      submitHandler() {
        this.$v.$touch();
        if (this.$v.$invalid) {
          return;
        }

        const formData = {
          email: this.email,
          password: this.password,
          name: this.name,
        };
        authService.register(formData).then(response => {
          this.$router.push({ name: 'login', query: { message: 'register' } });
        }).catch(error => {
          console.log({ error });
        });
      }
    }
  }
</script>

<style scoped>

</style>
