<script setup>
const { data } = await useApiFetch("/api/tournament/getAll");

</script>

<template>
    <div style="text-align: center; margin-top: 10px; font-size: 25px;">
        All Tournament
    </div>
    <div class="bg-gray-100 py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div v-for="tournament in data" :key="tournament.id">
                <div class="py-5">
                    <div style="margin: auto; min-width: 1000px; text-align: center;" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tournament Name: {{tournament.tournament_name}}</h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">Tournament Description: {{tournament.tournament_description}}</p>
                        <p class="font-normal text-gray-700 dark:text-gray-400">Tournament Time: {{tournament.start_date}} - {{tournament.end_date}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<style scoped></style>