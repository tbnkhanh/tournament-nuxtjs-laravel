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
                <h2 class="text-2xl font-bold mb-4" style="text-align: center;">Add New Team</h2>

                <!-- Your contact form content here -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Team Name</label>
                    <input type="text" id="name" name="team_name" v-model="formData.team_name"
                        class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Team Seed</label>
                    <input type="text" id="name" name="seed" v-model="formData.seed"
                        class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>

                <div v-for="(n, i) in dataCreate?.teamSize" :key="i">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Player {{ i + 1 }}
                            Email</label>
                        <input type="text" id="name" name="player_email" v-model="formData.players[i].email"
                            class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Player {{ i + 1 }} Name In
                            Game</label>
                        <input type="text" id="name" name="player_in_game" v-model="formData.players[i].gameName"
                            class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div style="text-align: center;">
                    <button type="submit" @click="handleCreate(close)"
                        class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Add Team</button>
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
    dataCreate: Object
});
const dataCreate = props.dataCreate

let formData = ref({
    tournament_id : dataCreate?.tournamentId,
    team_name: '',
    seed: '',
    players: [{
        email: '',
        gameName: ''
    }]
});

for (let i = 0; i < dataCreate?.teamSize - 1; i++) {
    formData.value.players.push({
        email: '',
        gameName: ''
    });
}

const handleCreate = async (close) => {
    let validate = true;
    let errorMessage = "Please fill in all field"
    if (!formData.value.team_name.trim() || !formData.value.seed.trim()) {
        validate = false;
    } else {
        for (const player of formData.value.players) {
            if (!player.email.trim() || !player.gameName.trim()) {
                errorMessage = "Please fill in all field"
                validate = false;
                break;
            }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(player.email)) {
                errorMessage = "Please enter a valid email"
                validate = false;
            }
        }
    }

    if (!validate) {
        notify(errorMessage)
    } else {
        // console.log(formData.value); 
        try {
            await useApiFetch("/sanctum/csrf-cookie");
            const { data, error, status } = await useApiFetch("/api/team/create", {
                method: "POST",
                body: formData.value
            });
            if (status.value === 'success') {
                notify(data.value?.message)
                await useApiFetch(`/api/team/getTeamsWithPlayers/${formData.value.tournament_id}`);

                close();
                // formData = ({})
            } else {
                notify(error.value?.data.message)
            }
        } catch (error) {
            console.log(error);
        }
    }
}
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
