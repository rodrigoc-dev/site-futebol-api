<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$apiKey = $_ENV['API_KEY'];

function fazerRequisicao($url) {
    global $apiKey;
    $options = [
        "http" => [
            "header" => [
                "X-Auth-Token: $apiKey",
                "User-Agent: Mozilla/5.0"
            ]
        ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === FALSE) {
        $error = error_get_last();
        die('Erro ao buscar dados da API: ' . $error['message']);
    }

    // Log the response for debugging
    file_put_contents('response_log.txt', $response);

    return json_decode($response, true);
}

function obterPartidasRecentes($limit = 5, $competitions = '2013,PL,CLI') {
    $competitionsQuery = implode(',', explode(',', $competitions));
    $url = "https://api.football-data.org/v4/matches?status=FINISHED&limit={$limit}&competitions={$competitionsQuery}";
    $data = fazerRequisicao($url);

    if (empty($data['matches'])) {
        file_put_contents('error_log.txt', "No matches found for competitions: {$competitions}\n", FILE_APPEND);
    }

    $recent_matches = [];
    foreach ($data['matches'] as $match) {
        $recent_matches[] = [
            'data' => $match['utcDate'],
            'time_mandante' => $match['homeTeam']['name'] ?? 'Time não definido',
            'gols_mandante' => $match['score']['fullTime']['home'] ?? 0,
            'gols_visitante' => $match['score']['fullTime']['away'] ?? 0,
            'time_visitante' => $match['awayTeam']['name'] ?? 'Time não definido',
            'venue' => $match['venue'] ?? 'N/A'
        ];
    }

    return $recent_matches;
}

function obterDadosCampeonato($campeonato, $matchday = 1) {
    $url = "https://api.football-data.org/v4/competitions/{$campeonato}/matches?matchday={$matchday}";
    $data = fazerRequisicao($url);

    $proximos_jogos = [];
    $ultimos_resultados = [];

    foreach ($data['matches'] as $match) {
        $time_mandante = $match['homeTeam']['name'] ?? 'Time não definido';
        $time_visitante = $match['awayTeam']['name'] ?? 'Time não definido';
        $venue = $match['venue'] ?? 'N/A';

        if ($match['status'] == 'SCHEDULED') {
            $proximos_jogos[] = [
                'data' => $match['utcDate'],
                'time_mandante' => $time_mandante,
                'time_visitante' => $time_visitante,
                'venue' => $venue
            ];
        } elseif ($match['status'] == 'FINISHED') {
            $ultimos_resultados[] = [
                'data' => $match['utcDate'],
                'time_mandante' => $time_mandante,
                'gols_mandante' => $match['score']['fullTime']['home'] ?? 0,
                'gols_visitante' => $match['score']['fullTime']['away'] ?? 0,
                'time_visitante' => $time_visitante,
                'venue' => $venue
            ];
        }
    }

    return [
        'proximos_jogos' => $proximos_jogos,
        'ultimos_resultados' => $ultimos_resultados
    ];
}

function obterDadosTime($time_id) {
    $url = "https://api.football-data.org/v4/teams/{$time_id}";
    return fazerRequisicao($url);
}

function obterIdTime($time_nome) {
    $competitions = ['PL', '2013', 'PD', 'SA', 'BL1']; // Lista de competições para buscar times
    foreach ($competitions as $competition) {
        $url = "https://api.football-data.org/v4/competitions/{$competition}/teams";
        $data = fazerRequisicao($url);

        foreach ($data['teams'] as $team) {
            if (stripos($team['name'], $time_nome) !== false) {
                return $team['id'];
            }
        }
    }

    return false;
}

function obterPartidasPorTime($time_id, $status = 'SCHEDULED') {
    $url = "https://api.football-data.org/v4/teams/{$time_id}/matches?status={$status}";
    $data = fazerRequisicao($url);
    return $data['matches'];
}

function obterDetalhesPartida($match_id) {
    $url = "https://api.football-data.org/v4/matches/{$match_id}";
    return fazerRequisicao($url);
}

function obterPartidasPorJogador($player_id, $status = 'FINISHED') {
    $url = "https://api.football-data.org/v4/players/{$player_id}/matches?status={$status}";
    $data = fazerRequisicao($url);
    return $data['matches'];
}

function obterPartidasPorCampeonatoEJornada($campeonato, $matchday) {
    $url = "https://api.football-data.org/v4/competitions/{$campeonato}/matches?matchday={$matchday}";
    $data = fazerRequisicao($url);
    return $data['matches'];
}

function obterClassificacaoCampeonato($campeonato) {
    $url = "https://api.football-data.org/v4/competitions/{$campeonato}/standings";
    $data = fazerRequisicao($url);
    return $data['standings'][0]['table'];
}

function obterArtilheirosCampeonato($campeonato) {
    $url = "https://api.football-data.org/v4/competitions/{$campeonato}/scorers";
    $data = fazerRequisicao($url);
    return $data['scorers'];
}

function obterTimesCampeonato($campeonato) {
    $url = "https://api.football-data.org/v4/competitions/{$campeonato}/teams";
    $data = fazerRequisicao($url);
    return $data['teams'];
}