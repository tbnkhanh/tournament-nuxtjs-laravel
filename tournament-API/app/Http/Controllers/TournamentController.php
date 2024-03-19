<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tournament;
use Illuminate\Support\Facades\Validator;
class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tournaments = DB::table("tournaments")->get();
        return response()->json($tournaments);
    }

    public function findById($id)
    {
        $tournament = Tournament::find($id);
        return response()->json($tournament);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tournamentName' => 'required|string|min:3',
            'tournamentDescription' => 'required|string',
            'selectedGame' => 'required|string',
            'selectedTeamSize' => 'required',
            'startDate' => 'required|string',
            'endDate' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['message' => $errors], 422);
        }    

        $tournament = new Tournament();
        // $tournament->user_id = Auth::id();
        $tournament->tournament_name = $request->input('tournamentName');
        $tournament->tournament_description = $request->input('tournamentDescription');
        $tournament->game_played = $request->input('selectedGame');
        $tournament->team_size = $request->input('selectedTeamSize');
        $tournament->start_date = $request->input('startDate');
        $tournament->end_date = $request->input('endDate');
        $tournament->save();

        if($tournament !== null){
            return response()->json(["message" => "Create Tournament Successfully"]);
        }
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tournamentName' => 'required|string|min:3',
            'tournamentDescription' => 'required|string',
            'selectedGame' => 'required|string',
            'selectedTeamSize' => 'required',
            'startDate' => 'required|string',
            'endDate' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['message' => $errors], 422);
        }    

        $tournament = new Tournament();
        $tournament = Tournament::find($request->input('id'));
        $tournament->tournament_name = $request->input('tournamentName');
        $tournament->tournament_description = $request->input('tournamentDescription');
        $tournament->game_played = $request->input('selectedGame');
        $tournament->team_size = $request->input('selectedTeamSize');
        $tournament->start_date = $request->input('startDate');
        $tournament->end_date = $request->input('endDate');
        $tournament->save();
        return response()->json(["message" => "Update Tournament Successfully"]);

    }

    public function destroy($id)
    {
        // $id = $request->input('id');
        $response = DB::table('tournaments')->where('id', $id )->delete();
        return response()->json(["message" => "Delete tournament successfully"]);
    }
}
