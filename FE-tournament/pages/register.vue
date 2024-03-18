<script lang="ts" setup>
import {useAuthStore} from "~/stores/useAuthStore";
import {definePageMeta} from "#imports";

definePageMeta({
  middleware: ['guest']
})

const form = ref({
  name: "",
  email: "",
  password: ""
});

const auth = useAuthStore();

async function handleRegister() {
  const {error} = await auth.register(form.value);
  if (!error.value) {
    navigateTo("/");
  }
}
</script>

<template>
  <form @submit.prevent="handleRegister">
    <section class="bg-gray-50 dark:bg-gray-900" style="margin-top: -56px">
      <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div
          class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
              Register new account
            </h1>
              <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                  email</label>
                <input type="email" v-model="form.email" name="email" id="email"
                  class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
              </div>
              <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                  Name</label>
                <input v-model="form.name" name="name" id="name"
                  class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
              </div>
              <div>
                <label for="password"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" v-model="form.password" name="password" id="password" 
                  class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
              </div>

              <button type="submit"
                class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800;"
                style=" background-color: steelblue">Register</button>
              <div class="text-sm font-light text-gray-500 dark:text-gray-400">
                Already have account? 
                <NuxtLink to="/login" style="color: aqua">Go to Login page</NuxtLink>
              </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</template>

<style scoped></style>