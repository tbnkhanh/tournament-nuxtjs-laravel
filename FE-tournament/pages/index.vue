<script lang="ts" setup>
import { useAuthStore } from "~/stores/useAuthStore";
const auth = useAuthStore();

</script>

<template>
  <div>
    <div class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4" style="max-width: 380px;">
            <div style="margin-left:75px; color:white">
                <p v-if="auth.isLoggedIn">You're logged in as {{ auth.user?.user_type }}</p>
                <p v-if="!auth.isLoggedIn">You are not logged in </p>
            </div>
        </div>
      </div>
  </div>
</template>