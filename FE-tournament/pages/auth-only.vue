<script lang="ts" setup>
import {definePageMeta} from "#imports";
 
definePageMeta({
  middleware: ['auth']
})
</script>
 
<template>
  <div>
    Only Auth
  </div>
</template>
 
<style scoped></style>