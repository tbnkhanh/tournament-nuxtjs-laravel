<script setup>
import { useRoute } from "vue-router";
const route = useRoute();
const tournamentId = route.params.id;

await useApiFetch("/sanctum/csrf-cookie");
const { data: tournament } = await useApiFetch(`/api/tournament/findById/${tournamentId}`);
const { data: teamsWithPlayers } = await useApiFetch(`/api/team/getTeamsWithPlayers/${tournamentId}`);

const modalCreate = ref(false);
const modalDelete = ref(false);
const modalEdit = ref(false);

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

let dataCreate = ref({});
const createDispatch = ref(false);
const handleCreateTeam = (tournamentId, teamSize) => {
  openModalCreate()
  createDispatch.value = true
  dataCreate.value = {
    tournamentId,
    teamSize
  }
}

const dataDelete = ref({})
const handleDelete = (teamId, name, tournamentId) => {
  openModalDelete()
  dataDelete.value = {
    teamId,
    name,
    tournamentId
  }
}

const closeModalEdit = () => {
  modalEdit.value = false;
  editDispatch.value = false;
}

let dataEdit = ref({});
const editDispatch = ref(false);
const handleEdit = (team) => {
  openModalEdit()
  editDispatch.value = true
  dataEdit.value = team
}
</script>

<template>
  <TeamModalCreate v-if="createDispatch" :open="modalCreate" @close="closeModal" :dataCreate="dataCreate" />
  <TeamModalDelete :open="modalDelete" @close="closeModal" :dataDelete="dataDelete" />
  <TeamModalEdit v-if="editDispatch" :open="modalEdit" @close="closeModalEdit" :dataEdit="dataEdit" />

  <div style="text-align: center; margin-top: 30px; font-size: 25px">
    <b>
      Welcome to Tournament: {{ tournament.tournament_name }}
    </b>
    <div class="py-2">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 85rem">
        <div style="text-align: center; font-size: 20px">
          <div>Tournament Name: {{ tournament.tournament_name }}</div>
          <div>Tournament Description: {{ tournament.tournament_description }}</div>
          <div>Game Played: {{ tournament.game_played }}</div>
          <div>Tournament Time: {{ tournament.start_date }} - {{ tournament.end_date }}</div>
        </div>
      </div>
    </div>
  </div>

  <TournamentBracket :tournament="tournament"/>

  <div class="py-2">
    <div class="bg-gray-100 py-5">
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 85rem">
      <div style="text-align: center; font-size: 20px">
        <div>All Teams Participate</div>

        <button @click="handleCreateTeam(tournament.id, tournament.team_size)"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3" style="font-size: 16px;">
          Add New Team
        </button>

        <div v-for="team in teamsWithPlayers" :key="team.id" class="relative overflow-x-auto mt-8">
          <div>{{ team.team_name }}</div>

          <div style="float: right; margin-bottom: 10px;">
            <button type="button" @click="handleEdit(team)"
              class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-3 dark:focus:ring-yellow-900">Edit</button>

            <button type="button" @click="handleDelete(team.id, team.team_name, tournament.id)"
              class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-3 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
          </div>

          <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                <th scope="col" class="px-6 py-3">
                  Player ID
                </th>
                <th scope="col" class="px-6 py-3">
                  Name
                </th>
                <th scope="col" class="px-6 py-3">
                  In Game Name
                </th>
                <th scope="col" class="px-6 py-3">
                  Email
                </th>
              </tr>
            </thead>
            <tbody v-for="player of team.players" :key="player.id">
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{ player.id }}
                </th>
                <td class="px-6 py-4">
                  {{ player.user.name }}
                </td>
                <td class="px-6 py-4">
                  {{ player.in_game_name }}
                </td>
                <td class="px-6 py-4">
                  {{ player.user.email }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped></style>
