<script setup>
import { useAuthStore } from "~/stores/useAuthStore";

const auth = useAuthStore();
const { data, refresh } = await useApiFetch("/api/tournament/getAll");

const modalCreate = ref(false);
const modalDelete = ref(false);
const modalEdit = ref(false);

const dataDelete = ref({
    id: 0,
    name: ""
})

const dataEdit = ref({});

const openModalCreate = () => {
    modalCreate.value = true;
};

const openModalDelete = () => {
    modalDelete.value = true;
};

const openModalEdit = () => {
    modalEdit.value = true;
};


const closeModal = () => {
    modalCreate.value = false;
    modalDelete.value = false;
    modalEdit.value = false;
};

const closeModalEdit = () => {
    modalEdit.value = false;
    edit.value = false
}

const handleDelete = (id, name) => {
    openModalDelete()
    dataDelete.value.id = id
    dataDelete.value.name = name
}

const edit = ref(false);
const handleEdit = (tournament) => {
    openModalEdit()
    edit.value = true
    dataEdit.value = tournament;
}

</script>


<template>
    <TournamentModalCreate :open="modalCreate" @close="closeModal" />
    <TournamentModalDelete :open="modalDelete" @close="closeModal" :tournamentName="dataDelete.name"
        :tournamentId="dataDelete.id" />
    <TournamentModalEdit v-if="edit" :open="modalEdit" @close="closeModalEdit" :tournament="dataEdit" :refresh="refresh"/>

    <div style="text-align: center; margin-top: 10px; font-size: 25px;">
        All Tournaments
    </div>
    <div class="bg-gray-100 py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="text-align: center;" class="mt-3">
                <button @click="openModalCreate" v-if="auth.user?.user_type === 'admin'"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create New Tournament
                </button>
            </div>
            <div v-for="tournament in data" :key="tournament.id">
                <div class="py-5">
                    <div style="margin: auto; min-width: 1000px; text-align: center;"
                        class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tournament
                            Name: {{ tournament.tournament_name }}</h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">Tournament Description:
                            {{ tournament.tournament_description }}</p>
                        <p class="font-normal text-gray-700 dark:text-gray-400">Game Played:
                            {{ tournament.game_played }}</p>
                        <p class="font-normal text-gray-700 dark:text-gray-400">Tournament Time:
                            {{ tournament.start_date }} - {{ tournament.end_date }}</p>

                        <NuxtLink :to="`/tournament/detail/${tournament.id}`">
                            <button type="button" 
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-3 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Detail</button>
                        </NuxtLink>
                       
                        <button type="button" @click="handleEdit(tournament)" v-if="auth.user?.user_type === 'admin'"
                            class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-3 dark:focus:ring-yellow-900">Edit</button>

                        <button type="button" @click="handleDelete(tournament.id, tournament.tournament_name)" v-if="auth.user?.user_type === 'admin'"
                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-3 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<style scoped></style>