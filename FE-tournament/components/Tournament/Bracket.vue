<script setup>
const props = defineProps({
    tournament: Object
});

const notify = (message) => {
    useNuxtApp().$toast.info(message, {
        autoClose: 1000,
        dangerouslyHTMLString: true,
    });
};

//Modal select team win
const modalSelectTeamWin = ref(false)
let dataModal = {}
const openModalSelectTeamWin = (match) => {
    modalSelectTeamWin.value = true
    dataModal = match
}
const closeModal = () => {
    modalSelectTeamWin.value = false
}


let tournament = props.tournament
const { data } = await useApiFetch(`/api/matches/getAllMatches/${tournament.id}`);
let matches_winner = data.value.matches_winner
let matches_loser = data.value.matches_loser


const round = Math.log2(tournament.team_number);
let matchesInThisRound = {};
let numberNeedMatches = {}

// Tạo các key ban đầu cho mỗi round
for (let i = 1; i <= round; i++) {
    matchesInThisRound[i] = [];
    numberNeedMatches[i] = tournament.team_number / 2 ** i
}

// Lặp qua từng trận đấu để phân loại vào các round tương ứng
if (matches_winner.length > 0) {
    matches_winner.forEach(match => {
        matchesInThisRound[match.round_number].push(match);
    });
}


async function handleGenerateBracket() {
    const { data, status } = await useApiFetch(`/api/matches/generateBracket/${tournament.id}`, {
        method: "POST"
    });
    if (status.value === 'success') {
        notify(data.value.message)
        await useApiFetch(`/api/matches/getAllMatches/${tournament.id}`);
    }
}
</script>

<template>
    <TournamentModalSelectTeamWin v-if="modalSelectTeamWin" :open="modalSelectTeamWin" @close="closeModal"
        :dataModal="dataModal" />
    <div class="py-2" style="text-align: center">
        <div class="bg-gray-100 py-5">
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 150rem">
            <div style="font-size: 22px">
                <b>Tournament Bracket</b>
            </div>

            <div>
                <button type="button" @click="handleGenerateBracket"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-3 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Generate
                    Bracket</button>
            </div>

            <div class="theme theme-dark">
                <div style="font-size: 20px; margin-top:10px">
                    <p> Winner Bracket</p>
                </div>

                <div class="bracket">

                    <div class="column" v-for="i in round" :key="i">
                        <div class="match" v-for="match in matchesInThisRound[i]" :key="match.id"
                        @click="match.team1_id !== null && match.team2_id !== null && openModalSelectTeamWin(match)">
                            <div class="match-top team">
                                <template v-if="match.team1_id !== null">
                                    <span class="seed">{{ match.team1.seed }}</span>
                                    <span class="name">{{ match.team1.team_name }}</span>
                                    <span class="winner" v-if="match.winner_team_id == match.team1.id">Winner</span>
                                </template>
                            </div>
                            <div class="match-bottom team">
                                <template v-if="match.team2_id !== null">
                                    <span class="seed">{{ match.team2.seed }}</span>
                                    <span class="name">{{ match.team2.team_name }}</span>
                                    <span class="winner" v-if="match.winner_team_id == match.team2.id">Winner</span>
                                </template>
                            </div>
                            <div class="match-lines">
                                <div class="line one"></div>
                                <div class="line two"></div>
                            </div>
                            <div class="match-lines alt">
                                <div class="line one"></div>
                            </div>
                        </div>

                        <!-- Nếu số lượng match trong round ít hơn số lượng match yêu cầu, tạo ra các div rỗng -->
                        <template v-if="matchesInThisRound[i].length < numberNeedMatches[i]">
                            <template v-for="i in (numberNeedMatches[i] - matchesInThisRound[i].length)" :key="i">
                                <div class="match winner-top">
                                    <div class="match-top team">
                                        <span class="seed"></span>
                                        <span class="name"></span>
                                        <span class="winner"></span>
                                    </div>
                                    <div class="match-bottom team">
                                        <span class="seed"></span>
                                        <span class="name"></span>
                                        <span class="winner"></span>
                                    </div>
                                    <div class="match-lines">
                                        <div class="line one"></div>
                                        <div class="line two"></div>
                                    </div>
                                    <div class="match-lines alt">
                                        <div class="line one"></div>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>