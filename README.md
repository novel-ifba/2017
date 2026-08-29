# Novel
Um Software Educacional para a Aprendizagem Autônoma de Ortografia

Projeto desenvolvido por:

Italo Miranda de Novais,
Breno Antonivaldo Lessa Andrade,
Leandro Lopes Ramos,

Orientado por: 

Msc. Pablo Freire Matos,
Esp. Sinval Araújo Medeiros Júnior

---

## Rodando com Docker

Pré-requisito: Docker com Compose v2.

```bash
docker compose up -d --build
```

A aplicação sobe em **http://localhost:8080**. O banco é criado e populado
automaticamente a partir de `novel_create_insert.sql` na primeira subida.

Credenciais do banco de demonstração:

| Acesso        | Login      | Senha       |
|---------------|------------|-------------|
| Jogador       | `teste`    | `123@Mudar` |
| Administrador | `teste123` | `123@Mudar` |

Comandos úteis:

```bash
docker compose logs -f web          # logs do Apache/PHP
docker compose exec db mysql -uroot -padmnovel novel   # console do MySQL
docker compose down                 # para os containers (mantém o banco)
docker compose down -v              # para e apaga o banco, forçando reimportação
```

O código é montado como volume, então alterações nos arquivos aparecem sem
rebuild. O `novel_create_insert.sql` só é executado quando o volume do banco
está vazio — depois de alterá-lo, use `docker compose down -v`.

### Estrutura

| Arquivo                    | Papel                                             |
|----------------------------|---------------------------------------------------|
| `Dockerfile`               | PHP 7.4 + Apache, `mysqli` e `mod_rewrite`        |
| `docker-compose.yml`       | Serviços `web` e `db` (MariaDB 10.6)              |
| `docker/apache/novel.conf` | `AllowOverride All` e `PassEnv CI_ENV`            |
| `docker/php/novel.ini`     | `session.save_path`, ausente na imagem oficial    |

### Versões

| Componente   | Versão  |
|--------------|---------|
| CodeIgniter  | 3.1.13  |
| jQuery       | 3.7.1   |
| Bootstrap    | 3.4.1   |
| PHP          | 7.4     |
| MariaDB      | 10.6    |

O CodeIgniter 3 é fim de linha — 3.1.13 é a última versão da série. Migrar para
o CI 4 significaria reescrever controllers, models, views e rotas.

### Por que PHP 7.4 e não 8.x

O CodeIgniter 3 não é compatível com PHP 8. A 7.4 é a versão mais recente que
o framework suporta.

### Ambiente

`CI_ENV` controla a exibição de erros (`index.php`). O compose define
`production`, que oculta avisos do PHP do visitante; use `development` para
depurar. Como o CodeIgniter lê `$_SERVER['CI_ENV']`, o Apache precisa repassar
a variável — daí o `PassEnv CI_ENV` em `docker/apache/novel.conf`.

### Senhas

As senhas são gravadas com `password_hash()` (bcrypt). Contas antigas em `md5`
ou em texto puro continuam entrando e são regravadas no formato novo no
primeiro login, tanto para jogadores quanto para administradores.

### Proteção CSRF

`csrf_protection` está ligado. Todo formulário POST inclui o campo escondido
gerado por `$this->security->get_csrf_hash()`; um POST sem o token recebe 403.

### Configuração fora do Docker

`application/config/database.php` e `config.php` aceitam as variáveis
`DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` e `BASE_URL`. Sem elas, os
valores padrão de instalação local (XAMPP/WAMP) continuam valendo.

O banco precisa ser criado com o nome `novel`. Os nomes de tabela respeitam a
caixa do `CREATE TABLE`, então o projeto roda em MySQL/MariaDB no Linux com a
configuração padrão (`lower_case_table_names = 0`).
