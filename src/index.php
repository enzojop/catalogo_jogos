<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de Jogos</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php 
    session_start();
    require "includes/db.php"; 
    ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <button class="login-btn" style="right: 130px;" onclick="location.href='pages/new_game.php'">Adicionar Jogo</button>
        <button class="login-btn" onclick="location.href='pages/login.php?logout=1'">Sair (<?php echo htmlspecialchars($_SESSION['nome']); ?>)</button>
    <?php else: ?>
        <button class="login-btn" onclick="location.href='pages/login.php'">Fazer login</button>
    <?php endif; ?>
    <header>
        <h1>Catálogo de Jogos</h1>
    </header>
    <main>

        <p class="welcome">Bem-vindo ao nosso catálogo de jogos! Aqui você encontrará uma variedade de jogos para todas as idades e gostos. Explore nossa coleção e encontre o jogo perfeito para você!</p>
        
        <form method="GET" class="controls">
            <label for="sort-options">Ordenar por:</label>
            <select id="sort-options" name="sort" onchange="this.form.submit()">
                <option value="name" <?php echo ($_GET['sort'] ?? 'name') === 'name' ? 'selected' : ''; ?>>Nome</option>
                <option value="genre" <?php echo ($_GET['sort'] ?? '') === 'genre' ? 'selected' : ''; ?>>Gênero</option>
                <option value="producer" <?php echo ($_GET['sort'] ?? '') === 'producer' ? 'selected' : ''; ?>>Produtora</option>
                <option value="country" <?php echo ($_GET['sort'] ?? '') === 'country' ? 'selected' : ''; ?>>País</option>
                <option value="rating" <?php echo ($_GET['sort'] ?? '') === 'rating' ? 'selected' : ''; ?>>Nota</option>
            </select>
            <label for="search-input">Buscar:</label>
            <input type="text" id="search-input" name="search" placeholder="Buscar por nome do jogo..." value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
        </form>
        <div class="games">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome do Jogo</th>
                        <th>Gênero</th>
                        <th>Produtoras</th>
                        <th>País</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody id="game-list">
                <?php
                $sort = $_GET['sort'] ?? 'name';
                $search = $_GET['search'] ?? '';
                
                $orderBy = match($sort) {
                    'genre' => 'g.genero ASC',
                    'producer' => 'p.produtora ASC',
                    'country' => 'p.pais ASC',
                    'rating' => 'j.nota DESC',
                    default => 'j.nome ASC'
                };
                
                $sql = "SELECT j.id, j.nome, j.capa, g.genero, p.produtora, p.pais, j.nota FROM jogos j
                        INNER JOIN generos g ON j.genero = g.id
                        INNER JOIN produtora p ON j.produtora = p.id";
                
                if (!empty($search)) {
                    $search = $db->real_escape_string($search);
                    $sql .= " WHERE j.nome LIKE '%$search%'";
                }
                
                $sql .= " ORDER BY $orderBy";

                $busca = $db->query($sql);
                if(!$busca) {
                    echo "Erro na consulta: " . $db->error;
                } else {
                    if($busca->num_rows === 0) {
                        echo "Nenhum jogo encontrado.";
                    } else {
                    while($reg = $busca->fetch_object()) {
                        $capa = property_exists($reg, 'capa') && !empty($reg->capa) ? "images/{$reg->capa}" : null;
                        $nome = htmlspecialchars($reg->nome ?? 'Sem nome', ENT_QUOTES, 'UTF-8');
                        $genero = htmlspecialchars($reg->genero ?? 'Nao informado', ENT_QUOTES, 'UTF-8');
                        $produtora = htmlspecialchars($reg->produtora ?? 'Nao informada', ENT_QUOTES, 'UTF-8');
                        $pais = htmlspecialchars($reg->pais ?? 'Nao informado', ENT_QUOTES, 'UTF-8');
                        $nota = htmlspecialchars($reg->nota ?? 'Nao informada', ENT_QUOTES, 'UTF-8');

                        echo "<tr>";
                        echo $capa
                            ? "<td><img src='{$capa}' alt='{$nome}'></td>"
                            : "<td>Sem foto</td>";
                        echo "<td><a href='includes/detalhes.php?jogo={$reg->id}'>{$nome}</a></td>";
                        echo "<td>{$genero}</td>";
                        echo "<td>{$produtora}</td>";
                        echo "<td>{$pais}</td>";
                        echo "<td>{$nota}</td>";
                        echo "</tr>";
                    }
                    }
                }

                ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <p> Desenvolvido por Enzo Oliveira &copy; 2026.</p>
    </footer>
</body>
</html>