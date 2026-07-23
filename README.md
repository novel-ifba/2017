# Novel
Um Software Educacional para a Aprendizagem Autônoma de Ortografia

Projeto desenvolvido por:

Italo Miranda de Novais,
Breno Antonivaldo Lessa Andrade,
Leandro Lopes Ramos,

Orientado por: 

Msc. Pablo Freire Matos,
Esp. Sinval Araújo Medeiros Júnior

## Deploy no Coolify

Use o `Dockerfile.coolify` e configure as variaveis de ambiente com base no
arquivo `.env.coolify.example`.

Variaveis principais:

- `APP_BASE_URL=https://novel.ifbavca.dev.br`
- `DB_HOST=<nome-do-servico-mysql-ou-mariadb-no-coolify>`
- `DB_PORT=3306`
- `DB_DATABASE=novel`
- `DB_USERNAME=<usuario-do-banco>`
- `DB_PASSWORD=<senha-do-banco>`
- `DB_DEBUG=false`

Antes do primeiro acesso, importe o arquivo `novel_create_insert.sql` no banco
`novel`. Se a pagina ficar branca, altere temporariamente `DB_DEBUG=true` e
veja os logs da aplicacao no Coolify; normalmente isso indica erro de conexao
com o banco.
