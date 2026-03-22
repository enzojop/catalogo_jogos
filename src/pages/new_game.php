<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Novo Jogo</title>
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>
    <?php
    session_start();
    require "../includes/db.php";

    if (!isset($_SESSION['user_id']) || $_SESSION['tipo'] !== 'admin') {
        header('Location: login.php?erro=acesso_negado');
        exit;
    }
    ?>

    <main class="login-page">
        <section class="login-card" style="max-width: 600px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h1 style="margin: 0;">Adicionar Novo Jogo</h1>
                <a href="login.php?logout=1" style="color: #ff5a1f; text-decoration: none; font-size: 0.9rem;">Sair</a>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nome = trim($_POST['nome'] ?? '');
                $genero = intval($_POST['genero'] ?? 0);
                $produtora = intval($_POST['produtora'] ?? 0);
                $descricao = trim($_POST['descricao'] ?? '');
                $nota = floatval($_POST['nota'] ?? 0);
                $capa = $_FILES['capa'] ?? null;

                if (!empty($nome) && $genero > 0 && $produtora > 0 && !empty($descricao)) {
                    $nome = $db->real_escape_string($nome);
                    $descricao = $db->real_escape_string($descricao);
                    $nomeCapa = null;

                    if ($capa && $capa['size'] > 0 && $capa['error'] === 0) {
                        $ext = pathinfo($capa['name'], PATHINFO_EXTENSION);
                        $nomeCapa = uniqid() . '.' . $ext;
                        $caminhoDestino = "../images/" . $nomeCapa;
                        if (move_uploaded_file($capa['tmp_name'], $caminhoDestino)) {
                            // Imagem salva
                        } else {
                            $nomeCapa = null;
                        }
                    }

                    $sql = "INSERT INTO jogos (nome, genero, produtora, descricao, nota" . ($nomeCapa ? ", capa" : "") . ") 
                            VALUES ('$nome', $genero, $produtora, '$descricao', $nota" . ($nomeCapa ? ", '$nomeCapa'" : "") . ")";

                    if ($db->query($sql)) {
                        echo "<p style='color: #90EE90; text-align: center; margin: 20px 0;'>Jogo adicionado com sucesso!</p>";
                        echo "<p style='text-align: center;'><a href='../index.php' style='color: #ff5a1f; text-decoration: none;'>Voltar ao catálogo</a></p>";
                    } else {
                        echo "<p style='color: #ffb3b3; text-align: center;'>Erro ao adicionar: " . $db->error . "</p>";
                    }
                } else {
                    echo "<p style='color: #ffb3b3; text-align: center;'>Preencha todos os campos obrigatórios.</p>";
                }
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['nome'])) {
            ?>

            <form method="POST" enctype="multipart/form-data" class="login-form" style="gap: 12px;">
                <label for="nome">Nome do Jogo</label>
                <input type="text" id="nome" name="nome" required>

                <label for="genero">Gênero</label>
                <select id="genero" name="genero" required>
                    <option value="">Selecione um gênero</option>
                    <?php
                    $generos = $db->query("SELECT id, genero FROM generos ORDER BY genero ASC");
                    while ($g = $generos->fetch_object()) {
                        echo "<option value='{$g->id}'>{$g->genero}</option>";
                    }
                    ?>
                </select>

                <label for="produtora">Produtora</label>
                <select id="produtora" name="produtora" required>
                    <option value="">Selecione uma produtora</option>
                    <?php
                    $produtoras = $db->query("SELECT id, produtora FROM produtora ORDER BY produtora ASC");
                    while ($p = $produtoras->fetch_object()) {
                        echo "<option value='{$p->id}'>{$p->produtora}</option>";
                    }
                    ?>
                </select>

                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="3" required></textarea>

                <label for="nota">Nota (0-10)</label>
                <input type="number" id="nota" name="nota" min="0" max="10" step="0.1">

                <label for="capa">Capa (opcional)</label>
                <input type="file" id="capa" name="capa" accept="image/*">

                <button type="submit">Adicionar Jogo</button>
            </form>

            <?php } ?>
        </section>
    </main>

    <footer>
        <p> Desenvolvido por Enzo Oliveira &copy; 2026.</p>
    </footer>
</body>
</html>