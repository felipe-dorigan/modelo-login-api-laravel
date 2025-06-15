<!-- Precisa ter o Composer e o Docker instalado previamente na máquina -->

<!-- Comando do laravel para iniciar um projeto -->

### composer create-project laravel/laravel example-app

<!-- Comando para iniciar o docker com os containers -->

### docker compose up --build

<!-- Mudei o arquivo .env para adicionar o banco de dados que irei usar -->
<!-- Criei o arquivo docker-compose.yaml na raiz do projeto para configurar os containers -->
<!-- Criei uma pasta na raiz do projeto, chamada docker -->
<!-- Dentro tem uma pasta e um arquivo -->
<!-- Dentro da pasta tem o arquivo de configuração -->

```
- docker
-- nginx
--- default.confg
-- Dockerfile
```

<!-- Arrumei o arquivo app->Http->Moddleware->Authenticate.php
Como nesse projeto vou usar ele somente como API, não tem porque redirecionar para o login, como em uma aplicação WEB, então setei como null o retorno -->

<!-- Comando para instalção da biblioteca laravel/sanctum -->

### composer require laravel/sanctum

<!-- Descomentei a linha
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    do arquivo:
    app/Http/Kernel.php
 -->

<!-- Criei o controller de user, comando para criar o controller -->
<!-- "--api": Cria o arquivo base de controller com os métodos - index, store, show, update e destroy -->

### php artisan make:controller UserController --api

<!-- Criei as rotas da aplicação no arquivo: routes->api.php -->

<!-- Criei a migration para adicionar lógica para expirar o token -->

### php artisan make:migration add_expires_at_to_personal_access_tokens

<!-- Criei seed de usuario padrão -->

### php artisan make:seeder DefaultUserSeeder

<!-- Adicionei a chamada da seed criada, no arquivo database->seeders->DatabaseSeeder.php -->

```
$this->call([
    DefaultUserSeeder::class,
]);
```

<!-- Criei o arquivo app->Http->Middleware->CheckTokenInactivity.php -->

<!-- Precisa adicionar a configuração do novo arquivo no caminho: app->Http->Kernel.php | Dentro do array $routeMiddleware -->

```
'check.inactivity' => \App\Http\Middleware\CheckTokenInactivity::class,
```

<!-- Criei duas funções no arquivo app->Exceptions->Handler.php função:
unauthenticated () e render()
-->

<!-- Antes de executar as migrations, precisa criar o banco de dados -->
<!-- Criar o server no postgres com a porta correta e criar o banco de dados -->

### modelo-login-api-laravel

<!-- Comando para executar as migrations -->
<!-- O comando php artisan não funciona no local, para rodar usando o container tem que rodar o outro -->

### php artisan migrate

### docker-compose exec app php artisan migrate

<!-- Executando uma seed -->

### php artisan db:seed

### docker-compose exec app php artisan db:seed

<!-- Desfazer uma migration -->

### docker-compose exec app php artisan migrate:rollback

<!-- DOCUMENTAÇÃO DE ROTAS -->
<!-- Login -->

```
    curl --location 'http://localhost:8080/api/login' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "email": "webmaster@gmail.com",
        "password": "admin1234"
    }'
```

<!-- Logout -->

```
    curl --location --request POST 'http://localhost:8080/api/logout' \
    --header 'Authorization: Bearer 2|JNsdGsXv2QDiqQ3OUuNFzJ0ImnOPn2SBnk6gUkez'
```

<!-- Refresh -->

```
    curl --location --request POST 'http://localhost:8080/api/refresh' \
    --header 'Authorization: Bearer 2|JNsdGsXv2QDiqQ3OUuNFzJ0ImnOPn2SBnk6gUkez'
```

<!-- Rotas de usuário -->
<!-- Listar todos os usuários -->

```
    curl --location 'http://localhost:8080/api/user' \
    --header 'Authorization: Bearer 2|JNsdGsXv2QDiqQ3OUuNFzJ0ImnOPn2SBnk6gUkez'
```

<!-- Criar usuário -->

```
    curl --location 'http://localhost:8080/api/user' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer 2|JNsdGsXv2QDiqQ3OUuNFzJ0ImnOPn2SBnk6gUkez' \
    --data-raw '{
        "name": "Anderson Silva",
        "email": "anderson.silva@gmail.com",
        "password": "anderson1234"
    }'
```

<!-- Obter usuário -->

```
    curl --location 'http://localhost:8080/api/user/2' \
    --header 'Authorization: Bearer 2|JNsdGsXv2QDiqQ3OUuNFzJ0ImnOPn2SBnk6gUkez'
```

<!-- Atualizar usuário -->

```
    curl --location --request PUT 'http://localhost:8080/api/user/1' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer 2|JNsdGsXv2QDiqQ3OUuNFzJ0ImnOPn2SBnk6gUkez' \
    --data-raw '{
        "name": "Anderson Silva",
        "email": "anderson.silva@gmail.com",
        "password": "anderson12345"
    }'
```

<!-- Atualizar usuário -->

```
    curl --location --request PATCH 'http://localhost:8080/api/user/1' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer 2|JNsdGsXv2QDiqQ3OUuNFzJ0ImnOPn2SBnk6gUkez' \
    --data-raw '{
        "name": "Anderson Silva Souza",
        "email": "anderson.silva2@gmail.com",
        "password": "anderson12345"
    }'
```

<!-- Obter usuário -->

```
    curl --location --request DELETE 'http://localhost:8080/api/user/2' \
    --header 'Authorization: Bearer 2|JNsdGsXv2QDiqQ3OUuNFzJ0ImnOPn2SBnk6gUkez'
```
