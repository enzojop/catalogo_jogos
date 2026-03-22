<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Jogo</title>
    <link rel="stylesheet" href="../style/detalhes.css">
</head>
<body>
    <?php
        require "db.php";
    ?>

        <main class="details-page">
        <section id="detalhes" class="details-card">
        <?php
            if(isset($_GET['jogo'])) {
                $jogoId = intval($_GET['jogo']);
                $sql = "SELECT j.nome, j.capa, g.genero, p.produtora, p.pais, j.nota, j.descricao
                        FROM jogos j
                        INNER JOIN generos g ON j.genero = g.id
                        INNER JOIN produtora p ON j.produtora = p.id
                        WHERE j.id = $jogoId";

                $busca = $db->query($sql);
                if($busca && $busca->num_rows > 0) {
                    $reg = $busca->fetch_object();

                    $nome = htmlspecialchars($reg->nome ?? 'Sem nome', ENT_QUOTES, 'UTF-8');
                    $genero = htmlspecialchars($reg->genero ?? 'Não informado', ENT_QUOTES, 'UTF-8');
                    $produtora = htmlspecialchars($reg->produtora ?? 'Não informada', ENT_QUOTES, 'UTF-8');
                    $pais = htmlspecialchars($reg->pais ?? 'Não informado', ENT_QUOTES, 'UTF-8');
                    $descricao = htmlspecialchars($reg->descricao ?? 'Sem descrição cadastrada.', ENT_QUOTES, 'UTF-8');
                    $nota = htmlspecialchars($reg->nota ?? 'Não informada', ENT_QUOTES, 'UTF-8');
                    $capa = !empty($reg->capa) ? "../images/{$reg->capa}" : null;

                    echo "<h1 class='details-title'>{$nome}</h1>";
                    echo "<div class='details-content'>";
                    echo $capa
                        ? "<img class='details-cover' src='{$capa}' alt='Capa do jogo {$nome}'>"
                        : "<div class='details-cover details-cover-empty'>Sem foto</div>";
                    echo "<div class='details-info'>";
                    echo "<p><strong>Gênero:</strong> {$genero}</p>";
                    echo "<p><strong>Produtora:</strong> {$produtora}</p>";
                    echo "<p><strong>País:</strong> {$pais}</p>";
                    echo "<p><strong>Nota:</strong> {$nota}</p>";
                    echo "<p><strong>Descrição:</strong> {$descricao}</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "<button class='back-btn' onclick='window.history.back()'>Voltar</button>";
                } else {
                    echo "<p class='message'>Jogo não encontrado.</p>";
                }
            } else {
                echo "<p class='message'>ID do jogo não fornecido.</p>";
            }   
        ?>
        </section>
        </main>
    <footer>
        <p> Desenvolvido por Enzo Oliveira &copy; 2026.</p>
    </footer>
</body>
</html>

