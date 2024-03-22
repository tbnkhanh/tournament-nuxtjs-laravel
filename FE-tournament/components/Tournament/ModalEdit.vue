<template>
    <div v-if="open" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white w-1/2 p-6 rounded shadow-md" style="width: 500px;">
                <div class="flex justify-end">
                    <!-- Close Button -->
                    <button @click="close" class="text-gray-700 hover:text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <h2 class="text-2xl font-bold mb-4" style="text-align: center;">Edit Tournament</h2>
                <!-- Your contact form content here -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Tournament Name</label>
                    <input type="text" id="name" name="name" v-model="form.tournamentName"
                        class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Tournament
                        Description</label>
                    <textarea id="message" name="message" v-model="form.tournamentDescription"
                        class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label for="countries" class="block text-gray-700 text-sm font-bold mb-2">Select Game Played</label>
                    <select id="countries" v-model="form.selectedGame"
                        class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500"
                        style="background-color: white;">
                        <option value="PUBG">PUBG</option>
                        <option value="FIFA">FIFA</option>
                        <option value="LOL">LOL</option>
                        <option value="CS:GO">CS:GO</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="countries" class="block text-gray-700 text-sm font-bold mb-2">Select Team Size</label>
                    <select id="countries" v-model="form.selectedTeamSize"
                        class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500"
                        style="background-color: white;">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="birthday">Start date:</label>
                    <input type="date" id="birthday" name="start_date" v-model="form.startDate"
                        class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500"
                        style="width: 100%;">
                </div>

                <div class="mb-4">
                    <label for="birthday">End date:</label>
                    <input type="date" id="birthday" name="start_date" v-model="form.endDate"
                        class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500"
                        style="width: 100%;">
                </div>

                <div style="text-align: center;">
                    <button @click="handleSave(close)"
                        class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">Save
                        Tournament</button>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    open: Boolean,
    tournament: Object
});
const tournament = props.tournament
const form = ref({
    id: tournament?.id,
    tournamentName: tournament?.tournament_name,
    tournamentDescription: tournament?.tournament_description,
    selectedGame: tournament?.game_played,
    selectedTeamSize: tournament?.team_size,
    startDate: tournament?.start_date,
    endDate: tournament?.end_date,
});
const emits = defineEmits();
const notify = (message) => {
    useNuxtApp().$toast.info(message, {
        autoClose: 1000,
        dangerouslyHTMLString: true,
    });
};

async function handleSave(close) {
    try {
        await useApiFetch("/sanctum/csrf-cookie");
        const { data, error, status } = await useApiFetch("/api/tournament/update", {
            method: "POST",
            body: form.value,
        });

        if (status.value === 'success') {
            notify(data.value.message)
            await useApiFetch("/api/tournament/getAll");
            close();
        } else {
            notify(error.value.data.message)
        }
    } catch (error) {
        console.log(error);
    }
};
</script>


<script>
export default {

    methods: {
        close() {
            this.$emit('close');
        }
    }
};
</script>
