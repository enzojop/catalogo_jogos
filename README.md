# Catalogo de Jogos

Projeto web em PHP + MySQL para listar jogos, visualizar detalhes e cadastrar novos jogos com controle de acesso por perfil.

## Estado Atual

O projeto atualmente possui:
- listagem dinamica de jogos na home
- ordenacao e busca por nome direto na home
- pagina de detalhes por jogo
- login com sessao
- restricao de cadastro: apenas usuario admin pode adicionar jogos
- upload opcional de capa no cadastro

## Tecnologias

- PHP (mysqli)
- MySQL
- HTML5
- CSS3
- XAMPP (Apache + MySQL)

## Estrutura Atual

```text
src/
  index.php
  database/
    database.db
  images/
  includes/
    auth.php
    db.php
    detalhes.php
  pages/
    login.php
    new_game.php
  style/
    style.css
    detalhes.css
    login.css
    new_game.css
```

## Paginas e Funcionalidades

### Home
- arquivo: [src/index.php](src/index.php)
- lista jogos com capa, nome, genero, produtora, pais e nota
- link do nome abre os detalhes do jogo
- ordenacao por: nome, genero, produtora, pais e nota
- busca por nome via parametro `search`
- exibe botoes de login/logout conforme sessao

### Detalhes do Jogo
- arquivo: [src/includes/detalhes.php](src/includes/detalhes.php)
- exibe nome, capa, genero, produtora, pais, nota e descricao
- recebe o id por query string: `?jogo=ID`

### Login
- arquivo: [src/pages/login.php](src/pages/login.php)
- autentica usando tabela `usuarios`
- cria sessao com `user_id`, `nome` e `tipo`
- faz logout com `?logout=1`

### Novo Jogo (Admin)
- arquivo: [src/pages/new_game.php](src/pages/new_game.php)
- protegido por sessao (somente `tipo = admin`)
- cadastra jogo em `jogos`
- upload opcional de capa para [src/images](src/images)

## Banco de Dados

Script SQL em: [src/database/database.db](src/database/database.db)

Banco:
- `bd_catalogo`

Tabelas principais:
- `usuarios`
- `generos`
- `produtora`
- `jogos`

Observacao:
- apesar do nome `database.db`, o arquivo contem comandos SQL para MySQL.

## Configuracao de Conexao

Arquivo de conexao: [src/includes/db.php](src/includes/db.php)

Configuracao atual:
- host: `localhost`
- usuario: `root`
- senha: vazia
- banco: `bd_catalogo`

Se seu ambiente for diferente, ajuste os valores em [src/includes/db.php](src/includes/db.php).

## Como Rodar Localmente (XAMPP)

1. Coloque o projeto em `c:/xampp/htdocs/catalogo-jogos`.
2. Inicie Apache e MySQL no XAMPP.
3. Crie o banco `bd_catalogo` no phpMyAdmin.
4. Execute o script de [src/database/database.db](src/database/database.db).
5. (Opcional) Crie um usuario admin para liberar cadastro de jogos:

```sql
INSERT INTO usuarios (usuario, nome, senha, tipo)
VALUES ('admin', 'Administrador', 'admin123', 'admin');
```

6. Acesse no navegador:
   - `http://localhost/catalogo-jogos/src/index.php`

## Fluxo de Acesso

- usuario nao autenticado: pode listar e ver detalhes
- usuario autenticado comum: continua sem permissao para cadastrar
- usuario admin: pode acessar [src/pages/new_game.php](src/pages/new_game.php) e adicionar jogos

## Proximos Passos Sugeridos

- hash de senha (password_hash/password_verify)
- validacao de tipo de arquivo no upload de capa
- pagina de edicao e exclusao de jogos (CRUD completo)
- paginacao na listagem para muitos registros