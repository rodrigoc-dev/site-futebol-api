<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FuteNews - Início</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../futebol/templates/styles.css">
    <style>
        /* Estilo para a lista de partidas */
        .match-list li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
        }
        .match-list li strong {
            color: #007bff;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/templates/header.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">FuteNews - O seu site de esportes</h1>

        <?php
        include __DIR__ . '/functions/api_functions.php';

        // Obter as últimas partidas da FIFA World Cup, Premier League e Copa Libertadores
        $wc_matches = obterDadosCampeonato('WC');
        $pl_matches = obterDadosCampeonato('PL');
        $cli_matches = obterDadosCampeonato('CLI');
        ?>

        <div class="row">
            <div class="col-md-4">
                <h2 class="mb-4 text-center">Últimos Jogos da FIFA World Cup</h2>
                <ul class="list-unstyled match-list">
                    <?php foreach (array_slice($wc_matches['ultimos_resultados'], 0, 10) as $match): ?>
                        <li>
                            <strong><?php echo date('d/m/Y H:i', strtotime($match['data'])); ?></strong><br>
                            <?php echo $match['time_mandante']; ?> <strong>(Mandante)</strong> <strong><?php echo $match['gols_mandante']; ?></strong> x 
                            <strong><?php echo $match['gols_visitante']; ?></strong> <?php echo $match['time_visitante']; ?> <strong>(Visitante)</strong><br>
                            <?php if (!empty($match['venue']) && $match['venue'] !== 'N/A'): ?>
                                <small>Estádio: <?php echo $match['venue']; ?></small>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-md-4">
                <h2 class="mb-4 text-center">Últimos Jogos da Premier League</h2>
                <ul class="list-unstyled match-list">
                    <?php foreach (array_slice($pl_matches['ultimos_resultados'], 0, 10) as $match): ?>
                        <li>
                            <strong><?php echo date('d/m/Y H:i', strtotime($match['data'])); ?></strong><br>
                            <?php echo $match['time_mandante']; ?> <strong>(Mandante)</strong> <strong><?php echo $match['gols_mandante']; ?></strong> x 
                            <strong><?php echo $match['gols_visitante']; ?></strong> <?php echo $match['time_visitante']; ?> <strong>(Visitante)</strong><br>
                            <?php if (!empty($match['venue']) && $match['venue'] !== 'N/A'): ?>
                                <small>Estádio: <?php echo $match['venue']; ?></small>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-md-4">
                <h2 class="mb-4 text-center">Últimos Jogos da Copa Libertadores</h2>
                <ul class="list-unstyled match-list">
                    <?php foreach (array_slice($cli_matches['ultimos_resultados'], 0, 10) as $match): ?>
                        <li>
                            <strong><?php echo date('d/m/Y H:i', strtotime($match['data'])); ?></strong><br>
                            <?php echo $match['time_mandante']; ?> <strong>(Mandante)</strong> <strong><?php echo $match['gols_mandante']; ?></strong> x 
                            <strong><?php echo $match['gols_visitante']; ?></strong> <?php echo $match['time_visitante']; ?> <strong>(Visitante)</strong><br>
                            <?php if (!empty($match['venue']) && $match['venue'] !== 'N/A'): ?>
                                <small>Estádio: <?php echo $match['venue']; ?></small>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p>Quer buscar o seu campeonato ou time específico? <a href="campeonato.php" class="btn btn-primary">Clique aqui</a> para campeonatos ou <a href="time.php" class="btn btn-primary">aqui</a> para times.</p>
        </div>
    </div>

    <?php include 'templates/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>