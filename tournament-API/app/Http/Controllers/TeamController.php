<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TeamController extends Controller
{
    public function getTeamsWithPlayers($tournamentId) {
        $teamsWithPlayers = Team::with('players.user')
        ->where('tournament_id', $tournamentId)   
        ->get();
        return response()->json($teamsWithPlayers);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'team_name' => 'required|string',
            'tournament_id' => 'required',
            'seed' => 'required',
            'players'=>'required'
        ]);

        $playersRequest = $request->input('players');
        $usersId = [];
        $emails = [];
        foreach ($playersRequest as $key => $player) {
            array_push($emails, $player['email']);
        }
        $checkedEmails = [];

        $usersRemain = User::where('user_type', 'player')
            ->whereDoesntHave('player')
            ->pluck('email')
            ->toArray();

        foreach ($emails as $email) {
            if (in_array($email, $checkedEmails)) {
               return response()->json(['message' => "Email $email is duplicate"],409);
            }

            $user = User::where([['email','=', $email], ['user_type','=','player']])->first();
            if (is_null($user)) {
               return response()->json(['message' => "Player with email $email doesn't exist"],409);
            }
            
            if (!in_array($email, $usersRemain)) {
               return response()->json(['message' => "Player with email $email already in other team"],409);
            }

            array_push($usersId, $user->id);
            array_push($checkedEmails, $email);
        }

        $team = Team::create([
            'tournament_id' => $request->input('tournament_id'),
            'seed' => $request->input('seed'),
            'team_name' => $request->input('team_name'),
        ]);
        $data = [];
        for ($i = 1; $i <= count($playersRequest); $i++) {
            array_push($data, ['team_id' => $team->id, 'in_game_name' => $playersRequest[$i-1]['gameName'], 'user_id' => $usersId[$i - 1]]);
        }
        Player::insert($data);
        return response()->json(['message' => "Add New Team Success"]);
    }

    public function destroy($id)
    {
        $response = DB::table('teams')->where('id', $id )->delete();
        return response()->json(["message" => "Delete team successfully"]);
    }
}
