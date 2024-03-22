<template>
    <div v-if="open" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white w-1/2 p-6 rounded shadow-md" style="width: 500px; text-align: center;">
                <div class="flex justify-end">
                    <!-- Close Button -->
                    <button @click=" emits('close');" class="text-gray-700 hover:text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <h2 class="text-2xl font-bold mb-4">Select Winning Team</h2>

                <div>
                    <div class="mb-4">
                        <input id="team1-radio" type="radio" :value="match.team1.id" name="selectedTeam"
                            v-model="selectedTeam"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="team1-radio" style="color: black;"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ match.team1.team_name
                            }}</label>
                    </div>
                    <div class="mb-4">
                        <input id="team2-radio" type="radio" :value="match.team2.id" name="selectedTeam"
                            v-model="selectedTeam"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="team2-radio" style="color: black;"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ match.team2.team_name
                            }}</label>
                    </div>
                </div>
                <div>
                    <button @click="handleSelectTeam()"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-3 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Confirm</button>
                    <button @click=" emits('close');" 
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancel</button>
                </div>

            </div>
        </div>
    </div>

</template>

<script setup>
const notify = (message) => {
    useNuxtApp().$toast.info(message, {
        autoClose: 1000,
        dangerouslyHTMLString: true,
    });
};
const props = defineProps({
    open: Boolean,
    dataModal: Object
});
const emits = defineEmits();

const selectedTeam = ref(null)
const match = props.dataModal

async function handleSelectTeam() {
    if (selectedTeam.value === null) {
        notify("Please select a winning team")
    } else {
        await useApiFetch("/sanctum/csrf-cookie");
        const { data, error, status } = await useApiFetch(`/api/matches/selectWinningTeam/${match.id}/${selectedTeam.value}`, {
            method: "POST",
        });
        if (status.value === 'success') {
            notify(data.value.message)
            const {data : res} = await useApiFetch(`/api/matches/getAllMatches/${match.tournament_id}`);
            emits('updateData', res);
            emits('close');
        } else {
            notify(error.value.data.message)
        }
    }
}
</script>
