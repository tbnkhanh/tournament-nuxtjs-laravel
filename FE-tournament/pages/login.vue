<script lang="ts" setup>
import {useAuthStore} from "~/stores/useAuthStore";

const form = ref({
  email: "admin@gmail.com",
  password: "12345678"
});

const auth = useAuthStore();

async function handleLogin() {
  const {error} = await auth.login(form.value);

  console.log(error);
}
</script>

<template>
  <form @submit.prevent="handleLogin">
    <label>
      Email
      <input v-model="form.email" type="email">
    </label>
    <label>
      Password
      <input v-model="form.password" type="password">
    </label>

    <button>Login</button>
  </form>
</template>

<style scoped></style>
