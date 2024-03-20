type Tournament =  {
  id: number;
  tournament_name: string;
  tournament_description: string;
  game_played: string;
  team_size: number;
  start_date: string;
  end_date: string;
};

type User = {
  id: number;
  name: string;
  email: string;
  user_type: string;
};

type Player = {
  id: number;
  user_id: number;
  team_id: number;
  in_game_name: string;
  user: User;
};

type Team = {
  id: number;
  team_name: string;
  tournament_id: number;
  seed: number;
  players: Array<Player>;
};

export {Tournament, User, Player, Team }