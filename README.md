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

| Arquivo                  | Papel                                              |
|--------------------------|----------------------------------------------------|
| `Dockerfile`             | PHP 7.4 + Apache, `mysqli` e `mod_rewrite`         |
| `docker-compose.yml`     | Serviços `web` e `db` (MariaDB 10.6)               |
| `docker/apache/novel.conf` | `AllowOverride All`, para o `.htaccess` valer     |
| `docker/php/novel.ini`   | Ajustes de sessão exigidos pelo CodeIgniter 3.1.0  |

### Por que PHP 7.4 e não 8.x

O projeto usa CodeIgniter 3.1.0, que não é compatível com PHP 8. Além disso,
o CI 3.1.0 valida o id de sessão com `/^[0-9a-f]{40}$/` — o tamanho do SHA-1
que o PHP até a versão 7.0 usava. A partir do PHP 7.1 o id passou a ter 32
caracteres, o que fazia o framework descartar o cookie e criar uma sessão nova
a cada requisição, impedindo o login. `docker/php/novel.ini` devolve o id para
40 caracteres hexadecimais, resolvendo isso sem alterar o framework.

### Configuração fora do Docker

`application/config/database.php` e `config.php` aceitam as variáveis
`DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` e `BASE_URL`. Sem elas, os
valores padrão de instalação local (XAMPP/WAMP) continuam valendo.
