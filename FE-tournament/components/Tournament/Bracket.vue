<script setup>
import { useAuthStore } from "~/stores/useAuthStore";
const auth = useAuthStore();

const props = defineProps({
    tournament: Object
});
const tournament = props.tournament
let team_winner = ref(tournament.winner_team)

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

//Call api get data
const { data } = await useApiFetch(`/api/matches/getAllMatches/${tournament.id}`);
let matches_winner = data.value.matches_winner
let matches_loser = data.value.matches_loser

// Winner Bracket
const roundWinner = tournament.team_number === null ? 0 : Math.log2(tournament.team_number);
let winnerMatchesInRound = ref({});
let winnerNumberNeedMatches = {}

const handleDataWinnerBracket = (matches_winner) => {
    // Tạo các key ban đầu cho mỗi round
    for (let i = 1; i <= roundWinner + 2; i++) {
        winnerMatchesInRound.value[i] = [];
        if (i <= roundWinner) {
            winnerNumberNeedMatches[i] = tournament.team_number / 2 ** i
        }
    }

    // Lặp qua từng trận đấu để phân loại vào các round tương ứng
    if (matches_winner.length > 0) {
        matches_winner.forEach(match => {
            winnerMatchesInRound.value[match.round_number].push(match);
        });
    }
}

handleDataWinnerBracket(matches_winner)

async function handleGenerateBracket() {
    const { data, status } = await useApiFetch(`/api/matches/generateBracket/${tournament.id}`, {
        method: "POST"
    });
    if (status.value === 'success') {
        notify(data.value.message)
        const { data: res } = await useApiFetch(`/api/matches/getAllMatches/${tournament.id}`);
        handleDataWinnerBracket(res.value.matches_winner)
    }
}

// Loser Bracket
const soMu = tournament.team_number === null ? 0 : Math.log2(tournament.team_number);
const roundLoser = soMu !== 0 ? (soMu - 1) * 2 : 0;
let loserMatchesInRound = ref({});
let loserNumberNeedMatches = {}

const handleDataLoserBracket = (matches_loser) => {
    // Tạo các key ban đầu cho mỗi round
    for (let i = 1; i <= roundLoser; i++) {
        loserMatchesInRound.value[i] = [];

        let b = i % 2 === 0 ? i : i + 1;
        let match_number_loser = tournament.team_number / 4;
        for (let c = 1; c <= b / 2 - 1; c++) {
            match_number_loser = match_number_loser / 2;
        }
        loserNumberNeedMatches[i] = match_number_loser
    }

    // Lặp qua từng trận đấu để phân loại vào các round tương ứng
    if (matches_loser.length > 0) {
        matches_loser.forEach(match => {
            loserMatchesInRound.value[match.round_number].push(match);
        });
    }
}

handleDataLoserBracket(matches_loser)


async function updateData(data) {
    handleDataWinnerBracket(data.value.matches_winner) 
    handleDataLoserBracket(data.value.matches_loser)
    const { data: res } = await useApiFetch(`/api/tournament/findById/${tournament.id}`);
    if(res.value.winner_team != team_winner) {
        team_winner.value = res.value.winner_team
    }

};

</script>

<template>
    <TournamentModalSelectTeamWin v-if="modalSelectTeamWin" :open="modalSelectTeamWin" @close="closeModal"
        @updateData="updateData" :dataModal="dataModal" />
    <div class="py-2" style="text-align: center">
        <div class="bg-gray-100 py-5">
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 150rem">
            <div style="font-size: 22px">
                <b>Tournament Bracket</b>
            </div>
            
            <div>
                <button type="button" v-if="tournament.is_generate_bracket === '0' &&  auth.user?.user_type === 'admin'" @click="handleGenerateBracket"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-3 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Generate
                    Bracket</button>
            </div>

            <div class="theme theme-dark">
                <div style="font-size: 20px; margin-top:10px">
                    <p v-if="roundWinner > 0">Winner Bracket</p>
                    <p v-if="roundWinner === 0">There are currently no tournament brackets to display</p>
                </div>

                <div class="bracket" v-if="roundWinner > 0">

                    <div class="column" v-for="i in roundWinner + 2" :key="i">
                        <div class="match" v-for="match in winnerMatchesInRound[i]" :key="match.id"
                            @click="match.team1_id !== null && match.team2_id !== null && match.winner_team_id === null && openModalSelectTeamWin(match)">
                            <div class="match-infor">Round: {{ match.round_number }} - Match:
                                {{ match.match_number }}</div>
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
                            <div class="match-lines" v-if="match.round_number < roundWinner">
                                <div class="line one"></div>
                                <div class="line two winner"></div>
                            </div>
                            <div class="match-lines" v-if="match.round_number <= roundWinner + 1">
                                <div class="line one"></div>
                            </div>
                            <div class="match-lines alt">
                                <div class="line one"></div>
                            </div>
                        </div>

                        <!-- Nếu số lượng match trong round ít hơn số lượng match yêu cầu, tạo ra các div rỗng -->
                        <template v-if="winnerMatchesInRound[i].length < winnerNumberNeedMatches[i]">
                            <template v-for="j in (winnerNumberNeedMatches[i] - winnerMatchesInRound[i].length)"
                                :key="j">
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
                                    <div class="match-lines" v-if="i != roundWinner">
                                        <div class="line one"></div>
                                        <div class="line two winner"></div>
                                    </div>
                                    <div class="match-lines alt">
                                        <div class="line one"></div>
                                    </div>
                                </div>
                            </template>
                        </template>


                    </div>

                    <div class="column">
                        <template v-if="team_winner !== null">
                            <div style=" text-align: center; width: 150px">
                                <b>Champion</b>
                                <img src="/trophy-cup.png" alt="Discover Nuxt 3" />
                                <b>{{ team_winner }}</b>
                            </div>
                        </template>
                    </div>
                </div>
            </div>



            <div class="theme theme-dark">
                <div style="font-size: 20px; margin-top:10px">
                    <p v-if="roundLoser > 0"> Loser Bracket</p>
                </div>

                <div class="bracket" v-if="roundLoser > 0">

                    <div class="column" v-for="i in roundLoser" :key="i">
                        <div class="match" v-for="match in loserMatchesInRound[i]" :key="match.id"
                            @click="match.team1_id !== null && match.team2_id !== null && match.winner_team_id === null && openModalSelectTeamWin(match)">
                            <div class="match-infor">Round: {{ match.round_number }} - Match:
                                {{ match.match_number }}</div>
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
                            <template v-if="i % 2 !== 0">
                                <div class="match-lines">
                                    <div class="line one"></div>
                                </div>
                            </template>

                            <template v-if="i % 2 === 0">
                                <div class="match-lines">
                                    <div class="line one"></div>
                                    <div class="line two loser"></div>
                                </div>
                            </template>

                            <div class="match-lines alt">
                                <div class="line one"></div>
                            </div>
                        </div>

                        <template v-if="loserMatchesInRound[i].length < loserNumberNeedMatches[i]">
                            <template v-for="j in (loserNumberNeedMatches[i] - loserMatchesInRound[i].length)" :key="j">
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
                                    <template v-if="i % 2 !== 0">
                                        <div class="match-lines">
                                            <div class="line one"></div>
                                        </div>
                                    </template>

                                    <template v-if="i % 2 === 0">
                                        <div class="match-lines">
                                            <div class="line one"></div>
                                            <div class="line two loser"></div>
                                        </div>
                                    </template>
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