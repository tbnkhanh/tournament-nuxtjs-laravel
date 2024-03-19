<template>
    <div v-if="open" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white w-1/2 p-6 rounded shadow-md" style="width: 500px; text-align: center;">
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
                <h2 class="text-2xl font-bold mb-4" >Delete Tournament</h2>
                <p>Are you confirm delete tournament: {{ tournamentName }} ?</p>
                <div>
                    <button @click="handleDelete(tournamentId, close)"
                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-3 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Confirm</button>
                    <button @click="close" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancel</button>
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

async function handleDelete(id, close) {
    try {
        await useApiFetch("/sanctum/csrf-cookie");
        const { status, data } = await useApiFetch(`/api/tournament/delete/${id}`, {
            method: "DELETE"
        });
        if (status.value === 'success') {
            notify(data.value.message)
            await useApiFetch("/api/tournament/getAll");
            close();
        }

    } catch (error) {
        console.log(error);
    }
}
</script>

<script>
export default {
    props: {
        open: Boolean,
        tournamentName: String,
        tournamentId: {
            type: Number,
            required: true
        }
    },
    methods: {
        close() {
            this.$emit('close');
        }
    }
};
</script>
