<?php
include 'templates/header.php';
include 'functions/api_functions.php';

// Obtém o ID do campeonato a partir dos parâmetros da URL
$campeonato_id = $_GET['campeonato_id'] ?? '';

if (!empty($campeonato_id)) {
    // Função para obter jogos e resultados do campeonato
    $dados_campeonato = obterDadosCampeonato($campeonato_id);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Campeonato</title>
    <link rel="stylesheet" href="templates/styles.css">
</head>
<body>
    <div class="container">
        <h1>Buscar Campeonato</h1>
        <!-- Formulário para selecionar o campeonato -->
        <form action="campeonato.php" method="GET">
            <label for="campeonato_id">Selecione o campeonato:</label>
            <select name="campeonato_id" id="campeonato_id" required>
                <option value="WC">FIFA World Cup</option>
                <option value="CL">UEFA Champions League</option>
                <option value="PL">Premier League</option>
                <option value="CLI">Copa Libertadores</option>
            </select>
            <button type="submit">Buscar</button>
        </form>

        <?php if (!empty($dados_campeonato)): ?>
            <h2><?php echo $campeonato_id; ?> - Jogos e Resultados</h2>
            
            <h3>Próximos Jogos</h3>
            <ul>
                <?php if (!empty($dados_campeonato['proximos_jogos'])): ?>
                    <?php foreach ($dados_campeonato['proximos_jogos'] as $jogo): ?>
                        <li>
                            <strong><?php echo date('d/m/Y H:i', strtotime($jogo['data'])); ?></strong><br>
                            <strong><?php echo $jogo['time_mandante']; ?></strong> (Mandante) vs <strong><?php echo $jogo['time_visitante']; ?></strong> (Visitante)<br>
                            <?php if (!empty($jogo['venue']) && $jogo['venue'] !== 'N/A'): ?>
                                <small>Estádio: <?php echo $jogo['venue']; ?></small>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Sem partidas definidas.</li>
                <?php endif; ?>
            </ul>

            <h3>Últimos Resultados</h3>
            <ul>
                <?php foreach ($dados_campeonato['ultimos_resultados'] as $resultado): ?>
                    <li>
                        <strong><?php echo date('d/m/Y H:i', strtotime($resultado['data'])); ?></strong><br>
                        <strong><?php echo $resultado['time_mandante']; ?></strong> (Mandante) <?php echo $resultado['gols_mandante']; ?> x <?php echo $resultado['gols_visitante']; ?> <strong><?php echo $resultado['time_visitante']; ?></strong> (Visitante)<br>
                        <?php if (!empty($resultado['venue']) && $resultado['venue'] !== 'N/A'): ?>
                            <small>Estádio: <?php echo $resultado['venue']; ?></small>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php include 'templates/footer.php'; ?>
</body>
</html>