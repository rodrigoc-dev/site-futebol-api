<?php

function obterDadosCampeonato($campeonato) {

$apiKey = 'b78be40bc9dd45d68fd9c976952844e9';

$url = "https://api.football-data.org/v4/competitions/{$campeonato}/matches";


$options = [

"http" => [

"header" => "X-Auth-Token: $apiKey"

]

];


$context = stream_context_create($options);

$response = file_get_contents($url, false, $context);



if ($response === FALSE) {

die('Erro ao buscar dados da API');

}



$data = json_decode($response, true);



$proximos_jogos = [];

$ultimos_resultados = [];



foreach ($data['matches'] as $match) {

$time_mandante = isset($match['homeTeam']['name']) ? $match['homeTeam']['name'] : 'Time não definido';

$time_visitante = isset($match['awayTeam']['name']) ? $match['awayTeam']['name'] : 'Time não definido';



if ($match['status'] == 'SCHEDULED') {

$proximos_jogos[] = [

'data' => $match['utcDate'],

'time_mandante' => $time_mandante,

'time_visitante' => $time_visitante

];

} elseif ($match['status'] == 'FINISHED') {

$ultimos_resultados[] = [

'data' => $match['utcDate'],

'time_mandante' => $time_mandante,

'gols_mandante' => isset($match['score']['fullTime']['homeTeam']) ? $match['score']['fullTime']['homeTeam'] : 0,

'gols_visitante' => isset($match['score']['fullTime']['awayTeam']) ? $match['score']['fullTime']['awayTeam'] : 0,

'time_visitante' => $time_visitante

];

}

}



return [

'proximos_jogos' => $proximos_jogos,

'ultimos_resultados' => $ultimos_resultados

];

}



function obterDadosTime($time_id) {

$apiKey = 'b78be40bc9dd45d68fd9c976952844e9';

$url = "https://api.football-data.org/v4/teams/{$time_id}";


$options = [

"http" => [

"header" => "X-Auth-Token: $apiKey"

]

];


$context = stream_context_create($options);

$response = file_get_contents($url, false, $context);



if ($response === FALSE) {

die('Erro ao buscar dados da API');

}



$data = json_decode($response, true);



$proximos_jogos = [];

$ultimos_resultados = [];


// Obter próximos jogos e últimos resultados do time

$url_matches = "https://api.football-data.org/v4/teams/{$time_id}/matches";

$response_matches = file_get_contents($url_matches, false, $context);



if ($response_matches !== FALSE) {

$data_matches = json_decode($response_matches, true);



foreach ($data_matches['matches'] as $match) {

$time_mandante = isset($match['homeTeam']['name']) ? $match['homeTeam']['name'] : 'Time não definido';

$time_visitante = isset($match['awayTeam']['name']) ? $match['awayTeam']['name'] : 'Time não definido';



if ($match['status'] == 'SCHEDULED') {

$proximos_jogos[] = [

'data' => $match['utcDate'],

'time_mandante' => $time_mandante,

'time_visitante' => $time_visitante

];

} elseif ($match['status'] == 'FINISHED') {

$ultimos_resultados[] = [

'data' => $match['utcDate'],

'time_mandante' => $time_mandante,

'gols_mandante' => isset($match['score']['fullTime']['homeTeam']) ? $match['score']['fullTime']['homeTeam'] : 0,

'gols_visitante' => isset($match['score']['fullTime']['awayTeam']) ? $match['score']['fullTime']['awayTeam'] : 0,

'time_visitante' => $time_visitante

];

}

}

}



return [

'name' => $data['name'],

'shortName' => $data['shortName'],

'crest' => $data['crest'],

'founded' => $data['founded'],

'clubColors' => $data['clubColors'],

'venue' => $data['venue'],

'website' => $data['website'],

'proximos_jogos' => $proximos_jogos,

'ultimos_resultados' => $ultimos_resultados,

'squad' => $data['squad']

];

}



// Função para obter o ID do time a partir do nome

function obterIdTime($time_nome) {

$apiKey = 'b78be40bc9dd45d68fd9c976952844e9';

$url = "https://api.football-data.org/v4/teams";



$options = [

"http" => [

"header" => "X-Auth-Token: $apiKey"

]

];



$context = stream_context_create($options);

$response = file_get_contents($url, false, $context);



if ($response === FALSE) {

die('Erro ao buscar dados da API');

}



$data = json_decode($response, true);



foreach ($data['teams'] as $team) {

if (stripos($team['name'], $time_nome) !== false) {

return $team['id'];

}

}



return false;

}



// Função para obter o ID do campeonato a partir do nome

function obterIdCampeonato($campeonato_nome) {

$apiKey = 'b78be40bc9dd45d68fd9c976952844e9';

$url = "https://api.football-data.org/v4/competitions";



$options = [

"http" => [

"header" => "X-Auth-Token: $apiKey"

]

];



$context = stream_context_create($options);

$response = file_get_contents($url, false, $context);



if ($response === FALSE) {

die('Erro ao buscar dados da API');

}



$data = json_decode($response, true);



foreach ($data['competitions'] as $competition) {

if (stripos($competition['name'], $campeonato_nome) !== false) {

return $competition['id'];

}

}



return false;

}



// Função para obter algumas partidas recentes de diversos campeonatos com filtros

function obterPartidasRecentes($limit = 5, $competitions = 'BSA,PL,CLI') {

$apiKey = 'b78be40bc9dd45d68fd9c976952844e9';


$url = "https://api.football-data.org/v4/matches?status=FINISHED&limit={$limit}&competitions={$competitions}";



$options = [

"http" => [

"header" => "X-Auth-Token: $apiKey"

]

];



$context = stream_context_create($options);

$response = file_get_contents($url, false, $context);



if ($response === FALSE) {

die('Erro ao buscar dados da API');

}



$data = json_decode($response, true);



$recent_matches = [];



foreach ($data['matches'] as $match) {

$recent_matches[] = [

'data' => $match['utcDate'],

'time_mandante' => $match['homeTeam']['name'] ?? 'Time não definido',

'gols_mandante' => $match['score']['fullTime']['homeTeam'] ?? 0,

'gols_visitante' => $match['score']['fullTime']['awayTeam'] ?? 0,

'time_visitante' => $match['awayTeam']['name'] ?? 'Time não definido'

];

}



return $recent_matches;

}

?>