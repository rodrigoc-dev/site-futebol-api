<?php
include 'templates/header.php';
include 'functions/api_functions.php';

$time_nome = $_GET['time_nome'] ?? '';

if (!empty($time_nome)) {
    // Função para obter o ID do time a partir do nome
    $time_id = obterIdTime($time_nome);
    if ($time_id) {
        // Função para obter dados do time
        $dados_time = obterDadosTime($time_id);
        // Função para obter próximos jogos do time
        $proximos_jogos = obterPartidasPorTime($time_id, 'SCHEDULED');
        // Função para obter últimos resultados do time
        $ultimos_resultados = obterPartidasPorTime($time_id, 'FINISHED');
    } else {
        $mensagem_erro = 'Time não encontrado. Pesquise algo como Manchester United, Chelsea, Real Madrid...';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Time</title>
    <link rel="stylesheet" href="templates/styles.css">
    <style>
        .alerta {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buscar Time</h1>
        <form action="time.php" method="GET">
            <label for="time_nome">Nome do time:</label>
            <input type="text" name="time_nome" id="time_nome" required>
            <button type="submit">Buscar</button>
        </form>

        <?php if (isset($mensagem_erro)): ?>
            <p class="alerta"><?php echo $mensagem_erro; ?></p>
        <?php elseif (!empty($dados_time)): ?>
            <h2><?php echo $dados_time['name']; ?></h2>
            <img src="<?php echo $dados_time['crest']; ?>" alt="Logo do Time">
            
            <h3>Informações do Time</h3>
            <p><strong>Nome Completo:</strong> <?php echo $dados_time['name']; ?></p>
            <p><strong>Apelido:</strong> <?php echo $dados_time['shortName']; ?></p>
            <p><strong>Fundação:</strong> <?php echo $dados_time['founded']; ?></p>
            <p><strong>Cores do Clube:</strong> <?php echo $dados_time['clubColors']; ?></p>
            <p><strong>Estádio:</strong> <?php echo $dados_time['venue']; ?></p>
            <p><strong>Site:</strong> <a href="<?php echo $dados_time['website']; ?>" target="_blank"><?php echo $dados_time['website']; ?></a></p>

            <h3>Próximos Jogos</h3>
            <ul>
                <?php if (!empty($proximos_jogos)): ?>
                    <?php foreach ($proximos_jogos as $jogo): ?>
                        <li><?php echo date('d/m/Y H:i', strtotime($jogo['utcDate'])); ?> - <?php echo $jogo['homeTeam']['name']; ?> vs <?php echo $jogo['awayTeam']['name']; ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Nenhum jogo encontrado.</li>
                <?php endif; ?>
            </ul>

            <h3>Últimos Resultados</h3>
            <ul>
                <?php if (!empty($ultimos_resultados)): ?>
                    <?php foreach ($ultimos_resultados as $resultado): ?>
                        <li><?php echo date('d/m/Y H:i', strtotime($resultado['utcDate'])); ?> - <?php echo $resultado['homeTeam']['name']; ?> <?php echo $resultado['score']['fullTime']['home']; ?> x <?php echo $resultado['score']['fullTime']['away']; ?> <?php echo $resultado['awayTeam']['name']; ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Nenhum resultado encontrado.</li>
                <?php endif; ?>
            </ul>

            <h3>Elenco</h3>
            <ul>
                <?php foreach ($dados_time['squad'] as $jogador): ?>
                    <li><?php echo $jogador['name']; ?> - <?php echo $jogador['position']; ?> (<?php echo $jogador['nationality']; ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php include 'templates/footer.php'; ?>
</body>
</html>