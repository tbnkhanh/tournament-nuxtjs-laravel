<script lang="ts" setup>
import {definePageMeta} from "#imports";

definePageMeta({
  middleware: ['guest']
})
</script>

<template>
  <div>
    Only Guest
  </div>
</template>

<style scoped></style>