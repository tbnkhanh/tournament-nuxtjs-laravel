<script lang="ts" setup>
import { useRoute } from "vue-router";

const route = useRoute();
const tournamentId = route.params.id;
try {
  await useApiFetch("/sanctum/csrf-cookie");
  const { data, error, status } = await useApiFetch(`/api/tournament/findById/${tournamentId}`);
  const tournament = data.value
  console.log(tournament);
  
} catch (error) {
  console.log(error);
}
</script>

<template>
  <div style="text-align: center; margin-top: 10px; font-size: 25px">
    Welcome to Tournament
  </div>
  <div class="py-2">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 85rem">
        <div style="text-align: center; font-size: 20px">
            <b>Tournament Information</b>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="card text-center mb-2">
                    <div class="card-header">Tournament name: {{ tournament }}</div>
                    <div class="card-body">
                        <div>
                            <b class="card-title">Tournament Description:</b>
                            <p class="card-text"> {{ tournament }} </p>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  <div class="bg-gray-100 py-2">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"></div>
  </div>
</template>

<style scoped></style>
