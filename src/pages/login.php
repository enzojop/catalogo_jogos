<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>
    <?php
    session_start();
    require "../includes/db.php";

    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: login.php');
        exit;
    }

    $erro = '';
    $logout = $_GET['logout'] ?? '';
    $acesso = $_GET['erro'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = trim($_POST['usuario'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if (!empty($usuario) && !empty($senha)) {
            $usuario = $db->real_escape_string($usuario);
            $sql = "SELECT usuario, nome, tipo FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha'";
            $resultado = $db->query($sql);

            if ($resultado && $resultado->num_rows > 0) {
                $user = $resultado->fetch_object();
                $_SESSION['user_id'] = $user->usuario;
                $_SESSION['nome'] = $user->nome;
                $_SESSION['tipo'] = $user->tipo;

                if ($user->tipo === 'admin') {
                    header('Location: new_game.php');
                    exit;
                } else {
                    header('Location: ../index.php');
                    exit;
                }
            } else {
                $erro = 'Usuário ou senha inválidos.';
            }
        } else {
            $erro = 'Preencha usuario e senha.';
        }
    }

    if (isset($_SESSION['user_id'])) {
        header('Location: ../index.php');
        exit;
    }
    ?>

    <main class="login-page">
        <section class="login-card">
            <h1>Login</h1>
            <p class="subtitle">Acesse sua conta para gerenciar o catálogo de jogos.</p>

            <?php if ($acesso === 'acesso_negado'): ?>
                <p style="color: #ffb3b3; text-align: center; margin: 15px 0;">Acesso negado. Apenas administradores podem adicionar jogos.</p>
            <?php endif; ?>

            <?php if ($erro): ?>
                <p style="color: #ffb3b3; text-align: center; margin: 15px 0;"><?php echo htmlspecialchars($erro); ?></p>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <label for="usuario">Usuário</label>
                <input type="text" id="usuario" name="usuario" placeholder="Digite seu usuário" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

                <button type="submit">Entrar</button>
            </form>
        </section>
    </main>

    <footer>
        <p> Desenvolvido por Enzo Oliveira &copy; 2026.</p>
    </footer>
</body>
</html>